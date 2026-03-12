<?php

namespace Tests\Unit\Cart;

use App\Application\Cart\UseCases\UpdateCartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCartItemTest extends TestCase
{
    use RefreshDatabase;

    private UpdateCartItem $updateCartItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->updateCartItem = new UpdateCartItem();
    }

    public function test_updates_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        session(['cart' => [$product->id => ['quantity' => 2]]]);

        $count = $this->updateCartItem->execute($product->id, 5);

        $this->assertSame(5, $count);
        $this->assertSame(5, session('cart')[$product->id]['quantity']);
    }

    public function test_quantity_capped_at_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        session(['cart' => [$product->id => ['quantity' => 1]]]);

        $count = $this->updateCartItem->execute($product->id, 10);

        $this->assertSame(3, $count);
    }

    public function test_ignores_nonexistent_cart_item(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        session(['cart' => []]);

        $count = $this->updateCartItem->execute($product->id, 5);

        $this->assertSame(0, $count);
    }
}
