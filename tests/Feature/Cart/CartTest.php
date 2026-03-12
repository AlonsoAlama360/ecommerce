<?php

namespace Tests\Feature\Cart;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_is_displayed(): void
    {
        $response = $this->get('/carrito');

        $response->assertStatus(200);
    }

    public function test_add_product_to_cart_via_json(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->postJson('/carrito/agregar', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['cart_count', 'message']);
    }

    public function test_add_product_to_cart_via_form(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->post('/carrito/agregar', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_add_product_validates_product_id(): void
    {
        $response = $this->postJson('/carrito/agregar', [
            'product_id' => 99999,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
    }

    public function test_add_product_validates_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->postJson('/carrito/agregar', [
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        $response->assertStatus(422);
    }

    public function test_update_cart_item(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        // First add to cart
        $this->postJson('/carrito/agregar', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->patchJson('/carrito/actualizar', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['cart_count', 'message']);
    }

    public function test_remove_product_from_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->postJson('/carrito/agregar', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->deleteJson('/carrito/eliminar', [
            'product_id' => $product->id,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['cart_count', 'message']);
    }

    public function test_get_cart_count(): void
    {
        $response = $this->getJson('/carrito/count');

        $response->assertOk()
            ->assertJsonStructure(['count']);
    }

    public function test_get_cart_items(): void
    {
        $response = $this->getJson('/carrito/items');

        $response->assertOk()
            ->assertJsonStructure(['items', 'total', 'count']);
    }

    public function test_cart_items_with_products(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 50.00]);

        $this->postJson('/carrito/agregar', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response = $this->getJson('/carrito/items');

        $response->assertOk();
        $data = $response->json();
        $this->assertSame(3, $data['count']);
        $this->assertCount(1, $data['items']);
    }
}
