<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Order;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'success' => $this->faker->boolean(),
            'external_transaction_id' => $this->faker->uuid(),
            'response_payload' => ['fake'=> true],
        ];
    }
}
