<?php

namespace App\Enums;

enum Specialization: int
{
    case Student = 1;
    case Teacher = 2;
    case Developer = 3;
    case Designer = 4;
    public function label(): string
    {
        return match($this) {
            self::Student => __('Student'),
            self::Teacher => __('Teacher'),
            self::Developer => __('Developer'),
            self::Designer => __('Designer'),
        };
    }
}
