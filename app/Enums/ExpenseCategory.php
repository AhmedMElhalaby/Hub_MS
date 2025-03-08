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
            self::Electricity => __('Electricity'),
            self::Water => __('Water'),
            self::Rent => __('Rent'),
            self::Internet => __('Internet'),
            self::Supplies => __('Supplies'),
            self::Salaries => __('Salaries'),
            self::Others => __('Others'),
        };
    }
}
