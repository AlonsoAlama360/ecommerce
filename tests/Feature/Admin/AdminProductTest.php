<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
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

    public function test_admin_can_list_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/products');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'Nuevo Producto',
            'sku' => 'NP-001',
            'category_id' => $category->id,
            'price' => 49.90,
            'stock' => 20,
            'is_featured' => false,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Nuevo Producto',
            'sku' => 'NP-001',
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->put('/admin/products/' . $product->id, [
            'name' => 'Producto Actualizado',
            'sku' => $product->sku,
            'category_id' => $product->category_id,
            'price' => 59.90,
            'stock' => 15,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Producto Actualizado',
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/products/' . $product->id);

        $response->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_customer_cannot_access_admin_products(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/products');

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $response = $this->get('/admin/products');

        $response->assertRedirect('/login');
    }

    public function test_product_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/products', []);

        $response->assertSessionHasErrors(['name', 'sku', 'category_id', 'price', 'stock']);
    }

    private function seedRolesAndPermissions(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => 'products.view', 'display_name' => 'Ver productos', 'module' => 'products', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
