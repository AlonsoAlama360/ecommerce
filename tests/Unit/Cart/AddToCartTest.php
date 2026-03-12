<?php

namespace Tests\Unit\Cart;

use App\Application\Cart\UseCases\AddToCart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddToCartTest extends TestCase
{
    use RefreshDatabase;

    private AddToCart $addToCart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->addToCart = new AddToCart();
    }

    public function test_adds_product_to_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $count = $this->addToCart->execute($product->id, 2);

        $this->assertSame(2, $count);
        $cart = session('cart');
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertSame(2, $cart[$product->id]['quantity']);
    }

    public function test_quantity_capped_at_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $count = $this->addToCart->execute($product->id, 10);

        $this->assertSame(3, $count);
    }

    public function test_increments_quantity_for_existing_item(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->addToCart->execute($product->id, 2);
        $count = $this->addToCart->execute($product->id, 3);

        $this->assertSame(5, $count);
        $this->assertSame(5, session('cart')[$product->id]['quantity']);
    }

    public function test_increment_capped_at_stock(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->addToCart->execute($product->id, 3);
        $count = $this->addToCart->execute($product->id, 10);

        $this->assertSame(5, $count);
    }

    public function test_fails_for_inactive_product(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->addToCart->execute($product->id);
    }

    public function test_returns_total_count_across_products(): void
    {
        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 10]);

        $this->addToCart->execute($product1->id, 2);
        $count = $this->addToCart->execute($product2->id, 3);

        $this->assertSame(5, $count);
    }
}
