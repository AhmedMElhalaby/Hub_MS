<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'user_id' => User::factory(),
            'event_type' => fake()->randomElement(['Created', 'Updated', 'Confirmed', 'Cancelled', 'Payment', 'Renewed']),
            'metadata' => [
                'amount' => fake()->randomFloat(2, 10, 1000),
                'note' => fake()->sentence(),
            ],
        ];
    }
}