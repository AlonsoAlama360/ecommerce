<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_order_number_auto_generated_on_creation(): void
    {
        $order = Order::factory()->create();

        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    public function test_order_number_format(): void
    {
        $order = Order::factory()->create();

        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{4}$/', $order->order_number);
    }

    public function test_sequential_order_numbers(): void
    {
        $order1 = Order::factory()->create();
        $order2 = Order::factory()->create();

        $seq1 = (int) substr($order1->order_number, -4);
        $seq2 = (int) substr($order2->order_number, -4);

        $this->assertSame($seq1 + 1, $seq2);
    }

    public function test_scope_by_status(): void
    {
        Order::factory()->create(['status' => 'confirmado']);
        Order::factory()->create(['status' => 'pendiente']);

        $this->assertCount(1, Order::byStatus('confirmado')->get());
    }

    public function test_scope_by_source(): void
    {
        Order::factory()->create(['source' => 'web']);
        Order::factory()->fromAdmin()->create();

        $this->assertCount(1, Order::bySource('admin')->get());
    }

    public function test_status_label_attribute(): void
    {
        $order = Order::factory()->create(['status' => 'en_preparacion']);

        $this->assertSame('En preparación', $order->status_label);
    }

    public function test_payment_method_label_attribute(): void
    {
        $order = Order::factory()->create(['payment_method' => 'yape_plin']);

        $this->assertSame('Yape / Plin', $order->payment_method_label);
    }

    public function test_order_has_items(): void
    {
        $order = Order::factory()->create();
        OrderItem::factory()->count(3)->create(['order_id' => $order->id]);

        $this->assertCount(3, $order->items);
    }

    private function seedRoles(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'vendedor', 'display_name' => 'Vendedor', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
