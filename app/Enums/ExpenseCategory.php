<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Electricity = 'electricity';
    case Water = 'water';
    case Rent = 'rent';
    case Internet = 'internet';
    case Supplies = 'supplies';
    case Salaries = 'salaries';
    case Others = 'other';

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
