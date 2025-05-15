<?php

namespace App\Enums;

enum PlanType: int
{
    case Hourly = 1;
    case Daily = 2;
    case Weekly = 3;
    case Monthly = 4;

    public function label(): string
    {
        return match($this) {
            self::Hourly => __('crud.plans.type.hourly'),
            self::Daily => __('crud.plans.type.daily'),
            self::Weekly => __('crud.plans.type.weekly'),
            self::Monthly => __('crud.plans.type.monthly'),
        };
    }
}
