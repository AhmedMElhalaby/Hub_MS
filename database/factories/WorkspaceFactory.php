<?php

namespace Database\Factories;

use App\Enums\WorkspaceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'desk' => fake()->unique()->numberBetween(100, 999),
            'status' => WorkspaceStatus::Available,
        ];
    }
}