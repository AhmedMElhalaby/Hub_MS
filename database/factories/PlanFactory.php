<?php

namespace Database\Factories;

use App\Enums\PlanType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(PlanType::cases()),
            'price' => fake()->randomFloat(2, 10, 1000),
            'mikrotik_profile' => fake()->word(),
        ];
    }
}