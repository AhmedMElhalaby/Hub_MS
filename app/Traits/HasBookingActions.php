<?php

namespace App\Traits;

use App\Enums\PlanType;
use App\Models\Plan;

trait HasBookingActions
{
    public function updatedPlanId()
    {
        if ($this->planId) {
            $plan = Plan::find($this->planId);
            $now = now();

            // Set default start time based on plan type
            if ($plan->type === PlanType::Hourly) {
                // Start from next hour
                $this->startedAt = $now->addHour()->startOfHour()->format('Y-m-d\TH:i');
            } else {
                // For daily, weekly, monthly plans, start at 9 AM next day
                $this->startedAt = $now->addDay()->setHour(9)->setMinute(0)->format('Y-m-d\TH:i');
            }
        }

        $this->calculateEndDate();
        $this->calculateTotal();
    }
    public function updatedStartedAt()
    {
        $this->calculateEndDate();
        $this->calculateTotal();
    }
    public function updatedDuration()
    {
        $this->calculateEndDate();
        $this->calculateTotal();
    }
    public function calculateEndDate()
    {
        if ($this->planId && $this->startedAt && $this->duration) {
            $plan = Plan::find($this->planId);
            $start = \Carbon\Carbon::parse($this->startedAt);
            $duration = (int) $this->duration;

            // Calculate end date based on plan type and duration
            switch ($plan->type) {
                case PlanType::Hourly:
                    $this->endedAt = $start->addHours($duration);
                    break;
                case PlanType::Daily:
                    $this->endedAt = $start->addDays($duration);
                    break;
                case PlanType::Weekly:
                    $this->endedAt = $start->addWeeks($duration);
                    break;
                case PlanType::Monthly:
                    $this->endedAt = $start->addMonths($duration);
                    break;
            }
        }
    }
    public function calculateTotal()
    {
        if ($this->planId && $this->duration) {
            $plan = Plan::find($this->planId);
            $this->total = $plan->price * $this->duration;
        }
    }
}
