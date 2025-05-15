<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case Cash = 1;
    case Banking = 2;

    public function label(): string
    {
        return match($this) {
            self::Cash => __('crud.finances.payment_method.cash'),
            self::Banking => __('crud.finances.payment_method.banking'),
        };
    }
}
