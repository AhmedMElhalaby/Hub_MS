<?php

namespace Database\Factories;

use App\Enums\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'specialization' => fake()->randomElement(Specialization::cases()),
        ];
    }
}