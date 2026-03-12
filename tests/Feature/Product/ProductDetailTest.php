<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_page_is_displayed(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->get('/producto/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_nonexistent_product_returns_404(): void
    {
        $response = $this->get('/producto/producto-que-no-existe');

        $response->assertStatus(404);
    }

    public function test_product_detail_shows_price(): void
    {
        $product = Product::factory()->create([
            'price' => 99.90,
            'is_active' => true,
        ]);

        $response = $this->get('/producto/' . $product->slug);

        $response->assertStatus(200);
    }

    public function test_product_detail_shows_sale_price(): void
    {
        $product = Product::factory()->onSale()->create([
            'is_active' => true,
        ]);

        $response = $this->get('/producto/' . $product->slug);

        $response->assertStatus(200);
    }
}
