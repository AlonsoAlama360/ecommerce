<?php

namespace Tests\Unit\Cart;

use App\Application\Cart\UseCases\RemoveFromCart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveFromCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_removes_product_from_cart(): void
    {
        $product = Product::factory()->create();
        session(['cart' => [$product->id => ['quantity' => 3]]]);

        $removeFromCart = new RemoveFromCart();
        $count = $removeFromCart->execute($product->id);

        $this->assertSame(0, $count);
        $this->assertArrayNotHasKey($product->id, session('cart'));
    }

    public function test_returns_remaining_count(): void
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        session(['cart' => [
            $product1->id => ['quantity' => 2],
            $product2->id => ['quantity' => 3],
        ]]);

        $removeFromCart = new RemoveFromCart();
        $count = $removeFromCart->execute($product1->id);

        $this->assertSame(3, $count);
    }
}
