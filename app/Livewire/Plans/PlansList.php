<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use App\Enums\PlanType;
use App\Models\Setting;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;

#[Layout('components.layouts.app')]
class PlansList extends Component
{
    use WithPagination, WithSorting, WithModal;

    public $type = '';
    public $price = '';
    public $mikrotikEnabled = false;
    public $mikrotik_profile;
    public $availableProfiles = [];
    public $types = [];
    public $planId;

    public function mount()
    {
        $this->mikrotikEnabled = Setting::get('mikrotik_enabled', false);
        $this->types = PlanType::cases();

        if ($this->mikrotikEnabled) {
            try {
                $mikrotik = new MikrotikService();
                $this->availableProfiles = collect($mikrotik->getHotspotProfiles())
                    ->pluck('name')
                    ->toArray();
            } catch (\Exception $e) {
                Log::error('Failed to fetch Mikrotik profiles', ['error' => $e->getMessage()]);
                $this->availableProfiles = [];
            }
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit(Plan $plan)
    {
        $this->planId = $plan->id;
        $this->type = $plan->type->value;
        $this->price = $plan->price;
        $this->mikrotik_profile = $this->mikrotik_profile;
        $this->openModal();
    }

    public function confirmDelete(Plan $plan)
    {
        $this->planId = $plan->id;
        $this->openDeleteModal();
    }

    public function resetForm()
    {
        $this->reset([
            'planId',
            'type',
            'price',
            'mikrotik_profile'
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
            'mikrotik_profile' => 'nullable|string'
        ]);

        if ($this->planId) {
            Plan::findOrFail($this->planId)->update($validated);
        } else {
            Plan::create($validated);
        }

        session()->flash('message', __('Plan saved successfully.'));
        $this->closeModal();
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete()
    {
        try {
            $plan = Plan::findOrFail($this->planId);
            $plan->delete();
            session()->flash('message', __('Plan deleted successfully.'));
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while deleting the plan.'));
        }
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.plans.plans-list', [
            'plans' => Plan::query()
                ->when(!$this->mikrotikEnabled, function ($query) {
                    $query->where('mikrotik_profile', null);
                })
                ->paginate(10)
        ]);
    }
}
