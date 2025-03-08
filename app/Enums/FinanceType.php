<?php

namespace App\Enums;

enum FinanceType: int
{
    case Income = 1;
    case Expense = 2;

    public function label(): string
    {
        return match($this) {
            self::Income => __('Income'),
            self::Expense => __('Expense'),
        };
    }
}
