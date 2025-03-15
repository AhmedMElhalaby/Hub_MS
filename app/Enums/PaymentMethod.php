<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case Cash = 1;
    case Banking = 2;

    public function label(): string
    {
        return match($this) {
            self::Cash => __('Cash'),
            self::Banking => __('Banking'),
        };
    }
}
