<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_increment_creates_entrada_movement(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $movement = StockService::increment($product, 5);

        $this->assertSame('entrada', $movement->type);
        $this->assertSame(5, $movement->quantity);
        $this->assertSame(10, $movement->stock_before);
        $this->assertSame(15, $movement->stock_after);
        $this->assertSame(15, $product->fresh()->stock);
    }

    public function test_decrement_creates_salida_movement(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $movement = StockService::decrement($product, 3);

        $this->assertSame('salida', $movement->type);
        $this->assertSame(-3, $movement->quantity);
        $this->assertSame(10, $movement->stock_before);
        $this->assertSame(7, $movement->stock_after);
        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_decrement_floors_at_zero(): void
    {
        $product = Product::factory()->create(['stock' => 2]);

        $movement = StockService::decrement($product, 10);

        $this->assertSame(0, $product->fresh()->stock);
    }

    public function test_adjust_creates_ajuste_movement(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $movement = StockService::adjust($product, 25);

        $this->assertSame('ajuste', $movement->type);
        $this->assertSame(15, $movement->quantity);
        $this->assertSame(10, $movement->stock_before);
        $this->assertSame(25, $movement->stock_after);
        $this->assertSame(25, $product->fresh()->stock);
    }

    public function test_adjust_decreases_stock(): void
    {
        $product = Product::factory()->create(['stock' => 20]);

        $movement = StockService::adjust($product, 5);

        $this->assertSame(-15, $movement->quantity);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_increment_with_reference(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $order = \App\Models\Order::factory()->create();

        $movement = StockService::increment($product, 5, $order);

        $this->assertSame(get_class($order), $movement->reference_type);
        $this->assertSame($order->id, $movement->reference_id);
    }

    public function test_movement_records_persisted(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        StockService::increment($product, 5);
        StockService::decrement($product, 3);

        $this->assertSame(2, StockMovement::where('product_id', $product->id)->count());
    }
}
