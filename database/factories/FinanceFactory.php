<?php

namespace Database\Factories;

use App\Enums\FinanceType;
use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(FinanceType::cases());
        return [
            'type' => $type,
            'booking_id' => $type === FinanceType::Income ? Booking::factory() : null,
            'expense_id' => $type === FinanceType::Expense ? Expense::factory() : null,
            'amount' => fake()->randomFloat(2, 10, 1000),
            'note' => fake()->sentence(),
            'payment_method' => fake()->randomElement(PaymentMethod::cases()),
        ];
    }
}