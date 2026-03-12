<?php

namespace Tests\Feature\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_is_displayed(): void
    {
        $response = $this->get('/catalogo');

        $response->assertStatus(200);
    }

    public function test_catalog_shows_active_products(): void
    {
        $active = Product::factory()->create(['is_active' => true]);
        $inactive = Product::factory()->create(['is_active' => false]);

        $response = $this->get('/catalogo');

        $response->assertStatus(200);
        $response->assertSee($active->name);
        $response->assertDontSee($inactive->name);
    }

    public function test_catalog_filter_by_category(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $otherProduct = Product::factory()->create();

        $response = $this->get('/catalogo?category=' . $category->slug);

        $response->assertStatus(200);
    }

    public function test_search_returns_json(): void
    {
        Product::factory()->create([
            'name' => 'Collar de Plata',
            'is_active' => true,
        ]);

        $response = $this->getJson('/buscar?q=Collar');

        $response->assertOk()
            ->assertJsonStructure([]);
    }

    public function test_search_finds_matching_products(): void
    {
        Product::factory()->create([
            'name' => 'Pulsera Dorada',
            'is_active' => true,
        ]);
        Product::factory()->create([
            'name' => 'Anillo de Oro',
            'is_active' => true,
        ]);

        $response = $this->getJson('/buscar?q=Pulsera');

        $response->assertOk();
    }
}
