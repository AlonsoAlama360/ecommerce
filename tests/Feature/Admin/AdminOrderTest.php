<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->create();
    }

    public function test_admin_can_list_orders(): void
    {
        Order::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/orders');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_order(): void
    {
        $product = Product::factory()->create(['stock' => 20, 'price' => 50.00]);

        $response = $this->actingAs($this->admin)->post('/admin/orders', [
            'customer_name' => 'Cliente Test',
            'customer_email' => 'cliente@test.com',
            'customer_phone' => '999888777',
            'payment_method' => 'efectivo',
            'payment_status' => 'pagado',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Cliente Test',
            'source' => 'admin',
        ]);
    }

    public function test_admin_can_update_order_status(): void
    {
        $order = Order::factory()->create(['status' => 'confirmado']);

        $response = $this->actingAs($this->admin)->putJson('/admin/orders/' . $order->id . '/status', [
            'status' => 'enviado',
        ]);

        $response->assertOk()
            ->assertJson(['status' => 'enviado']);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'enviado',
        ]);
    }

    public function test_admin_can_view_order_detail(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/admin/orders/' . $order->id);

        $response->assertOk();
    }

    public function test_customer_cannot_access_admin_orders(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/orders');

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_order(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/orders/' . $order->id);

        $response->assertRedirect();
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    private function seedRolesAndPermissions(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => 'orders.view', 'display_name' => 'Ver órdenes', 'module' => 'orders', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
