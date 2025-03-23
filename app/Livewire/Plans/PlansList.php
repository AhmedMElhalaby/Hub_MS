<?php

namespace App\Livewire\Plans;

use App\Repositories\PlanRepository;
use App\Services\NotificationService;
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
    use WithPagination, WithSorting, WithModal, NotificationService;

    public $type = '';
    public $price = '';
    public $mikrotikEnabled = false;
    public $mikrotik_profile;
    public $availableProfiles = [];
    public $types = [];
    public $planId;

    protected PlanRepository $planRepository;

    public function boot(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

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

    public function edit($planId)
    {
        $plan = $this->planRepository->findById($planId);
        $this->planId = $plan->id;
        $this->type = $plan->type->value;
        $this->price = $plan->price;
        $this->mikrotik_profile = $plan->mikrotik_profile;
        $this->openModal();
    }

    public function confirmDelete($planId)
    {
        $this->planId = $planId;
        $this->openDeleteModal();
    }

    public function resetForm()
    {
        $this->reset(['planId', 'type', 'price', 'mikrotik_profile']);
    }

    public function save()
    {
        $validated = $this->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
            'mikrotik_profile' => 'nullable|string'
        ]);

        try {
            if ($this->planId) {
                $this->planRepository->update($this->planId, $validated);
            } else {
                $this->planRepository->create($validated);
            }

            $this->notifySuccess('messages.plan.saved');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.plan.save_error');
        }
    }

    public function delete()
    {
        try {
            $this->planRepository->delete($this->planId);
            $this->notifySuccess('messages.plan.deleted');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.plan.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.plans.plans-list', [
            'plans' => $this->planRepository->getAllPaginatedWithMikrotik(
                $this->search,
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            )
        ]);
    }
}
