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
            self::Hourly => __('Hourly'),
            self::Daily => __('Daily'),
            self::Weekly => __('Weekly'),
            self::Monthly => __('Monthly'),
        };
    }
}
