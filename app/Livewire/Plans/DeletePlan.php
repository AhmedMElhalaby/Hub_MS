<?php

namespace App\Livewire\Plans;

use App\Repositories\PlanRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class DeletePlan extends Component
{
    use WithModal, NotificationService;

    public $planId;

    protected PlanRepository $planRepository;

    public function boot(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function delete()
    {
        try {
            $this->planRepository->delete($this->planId);
            $this->closeModal();
            $this->dispatch('plan-deleted');
            $this->notifySuccess(__('crud.plans.messages.deleted'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.delete_error', ['model' => __('crud.plans.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.plans.delete-plan');
    }

    #[On('open-delete-plan')]
    public function open($planId)
    {
        $this->planId = $planId;
        $this->openModal();
    }
}
