<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_authenticated_user_can_view_their_orders(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/mis-pedidos');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_order_detail(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/mis-pedidos/' . $order->id);

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get('/mis-pedidos/' . $order->id);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/mis-pedidos');

        $response->assertRedirect('/login');
    }

    public function test_orders_filtered_by_status(): void
    {
        $user = User::factory()->create();
        Order::factory()->create(['user_id' => $user->id, 'status' => 'confirmado']);
        Order::factory()->create(['user_id' => $user->id, 'status' => 'entregado']);

        $response = $this->actingAs($user)->get('/mis-pedidos?status=confirmado');

        $response->assertStatus(200);
    }

    private function seedRoles(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
