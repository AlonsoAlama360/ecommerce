<?php

namespace Tests\Unit\Cart;

use App\Application\Cart\UseCases\GetCart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCartTest extends TestCase
{
    use RefreshDatabase;

    private GetCart $getCart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getCart = new GetCart();
    }

    public function test_empty_cart_returns_zero_values(): void
    {
        $result = $this->getCart->execute();

        $this->assertEmpty($result['cartItems']);
        $this->assertEquals(0, $result['subtotal']);
        $this->assertEquals(0, $result['totalDiscount']);
        $this->assertEquals(0, $result['total']);
        $this->assertSame(0, $result['totalItems']);
    }

    public function test_calculates_subtotal_correctly(): void
    {
        $product = Product::factory()->create(['price' => 50.00, 'sale_price' => null]);
        session(['cart' => [$product->id => ['quantity' => 3]]]);

        $result = $this->getCart->execute();

        $this->assertEquals(150.00, $result['subtotal']);
        $this->assertEquals(0, $result['totalDiscount']);
        $this->assertEquals(150.00, $result['total']);
        $this->assertSame(3, $result['totalItems']);
    }

    public function test_calculates_discount_for_sale_products(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => 80.00,
        ]);
        session(['cart' => [$product->id => ['quantity' => 2]]]);

        $result = $this->getCart->execute();

        $this->assertEquals(200.00, $result['subtotal']);
        $this->assertEquals(40.00, $result['totalDiscount']);
        $this->assertEquals(160.00, $result['total']);
    }

    public function test_skips_deleted_products(): void
    {
        $product = Product::factory()->create(['price' => 50.00]);
        session(['cart' => [$product->id => ['quantity' => 1], 999 => ['quantity' => 2]]]);

        $result = $this->getCart->execute();

        $this->assertCount(1, $result['cartItems']);
        // totalItems comes from session (includes nonexistent product IDs)
        $this->assertSame(3, $result['totalItems']);
    }

    public function test_returns_suggested_products(): void
    {
        Product::factory()->count(5)->create();
        $productInCart = Product::factory()->create();
        session(['cart' => [$productInCart->id => ['quantity' => 1]]]);

        $result = $this->getCart->execute();

        $this->assertLessThanOrEqual(4, $result['suggestedProducts']->count());
        $this->assertFalse($result['suggestedProducts']->contains('id', $productInCart->id));
    }
}
