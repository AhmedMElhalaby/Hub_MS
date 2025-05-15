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
            self::Student => __('crud.customers.specialization.student'),
            self::Teacher => __('crud.customers.specialization.teacher'),
            self::Developer => __('crud.customers.specialization.developer'),
            self::Designer => __('crud.customers.specialization.designer'),
        };
    }
}
