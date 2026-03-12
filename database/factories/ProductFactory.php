<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 10, 500);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'price' => $price,
            'sale_price' => null,
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-??')),
            'stock' => fake()->numberBetween(5, 100),
            'material' => fake()->word(),
            'is_featured' => false,
            'is_active' => true,
        ];
    }

    public function onSale(float $discount = 0.2): static
    {
        return $this->state(function (array $attributes) use ($discount) {
            $price = $attributes['price'];
            return [
                'sale_price' => round($price * (1 - $discount), 2),
            ];
        });
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
