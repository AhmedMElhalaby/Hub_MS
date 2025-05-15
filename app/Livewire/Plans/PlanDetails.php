<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Repositories\PlanRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class PlanDetails extends Component
{
    use NotificationService;

    public $plan;
    protected PlanRepository $planRepository;

    public function boot(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function mount(Plan $plan)
    {
        try {
            $this->plan = $this->planRepository->findWithBookings($plan->id);
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.not_found', ['model' => __('crud.plans.model.singular')]));
            return $this->redirect(route('tenant.plans.index'));
        }
    }

    public function render()
    {
        return view('livewire.plans.plan-details');
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
