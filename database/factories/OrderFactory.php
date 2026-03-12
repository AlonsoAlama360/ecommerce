<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 1000);

        return [
            'user_id' => User::factory(),
            'source' => 'web',
            'status' => 'confirmado',
            'payment_method' => 'efectivo',
            'payment_status' => 'pagado',
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'shipping_cost' => 0,
            'total' => $subtotal,
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->safeEmail(),
            'shipping_address' => fake()->address(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pendiente',
            'payment_status' => 'pendiente',
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'entregado',
        ]);
    }

    public function fromAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'admin',
        ]);
    }
}
