<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_price_returns_sale_price_when_set(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => 79.99,
        ]);

        $this->assertEquals('79.99', $product->current_price);
    }

    public function test_current_price_returns_price_when_no_sale_price(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => null,
        ]);

        $this->assertEquals('100.00', $product->current_price);
    }

    public function test_discount_percentage_calculated_correctly(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => 75.00,
        ]);

        $this->assertSame(25, $product->discount_percentage);
    }

    public function test_discount_percentage_null_when_no_sale_price(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => null,
        ]);

        $this->assertNull($product->discount_percentage);
    }

    public function test_discount_percentage_null_when_sale_price_higher(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => 120.00,
        ]);

        $this->assertNull($product->discount_percentage);
    }

    public function test_scope_active(): void
    {
        Product::factory()->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);

        $this->assertCount(1, Product::active()->get());
    }

    public function test_scope_featured(): void
    {
        Product::factory()->create(['is_featured' => true]);
        Product::factory()->create(['is_featured' => false]);

        $this->assertCount(1, Product::featured()->get());
    }

    public function test_scope_in_stock(): void
    {
        Product::factory()->create(['stock' => 10]);
        Product::factory()->create(['stock' => 0]);

        $this->assertCount(1, Product::inStock()->get());
    }

    public function test_scope_on_sale(): void
    {
        Product::factory()->onSale()->create();
        Product::factory()->create(['sale_price' => null]);

        $this->assertCount(1, Product::onSale()->get());
    }

    public function test_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($product->category->is($category));
    }
}
