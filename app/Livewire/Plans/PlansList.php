<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use App\Enums\PlanType;
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
    public $planId;

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
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
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
            'plans' => Plan::when($this->search, function($query) {
                $query->where('type', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage),
            'types' => PlanType::cases()
        ]);
    }
}
