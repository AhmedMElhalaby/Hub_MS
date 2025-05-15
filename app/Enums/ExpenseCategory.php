<?php

namespace App\Enums;

enum ExpenseCategory: int
{
    case Electricity = 1;
    case Water = 2;
    case Rent = 3;
    case Internet = 4;
    case Supplies = 5;
    case Salaries = 6;
    case Others = 0;

    public function label(): string
    {
        return match($this) {
            self::Electricity => __('crud.expenses.categories.electricity'),
            self::Water => __('crud.expenses.categories.water'),
            self::Rent => __('crud.expenses.categories.rent'),
            self::Internet => __('crud.expenses.categories.internet'),
            self::Supplies => __('crud.expenses.categories.supplies'),
            self::Salaries => __('crud.expenses.categories.salaries'),
            self::Others => __('crud.expenses.categories.others'),
        };
    }
}
