<?php

namespace App\Enums;

enum PlanType: string
{
    case Hourly = 'hourly';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';

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
