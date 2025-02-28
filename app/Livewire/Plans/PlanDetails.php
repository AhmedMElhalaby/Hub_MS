<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class PlanDetails extends Component
{
    public Plan $plan;

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function render()
    {
        return view('livewire.plans.plan-details');
    }
}
