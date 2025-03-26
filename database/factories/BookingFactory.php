<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $total = fake()->randomFloat(2, 50, 500);
        return [
            'customer_id' => Customer::factory(),
            'workspace_id' => Workspace::factory(),
            'plan_id' => Plan::factory(),
            'started_at' => fake()->dateTimeBetween('now', '+1 week'),
            'ended_at' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'total' => $total,
            'balance' => $total,
            'status' => fake()->randomElement(BookingStatus::cases()),
            'hotspot_username' => fake()->userName(),
            'hotspot_password' => fake()->password(8),
        ];
    }
}