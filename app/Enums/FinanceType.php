<?php

namespace App\Enums;

enum FinanceType: string
{
    case Income = 'income';
    case Expense = 'expense';

    public function label(): string
    {
        return match($this) {
            self::Income => __('Income'),
            self::Expense => __('Expense'),
        };
    }
}
