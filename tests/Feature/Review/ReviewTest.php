<?php

namespace Tests\Feature\Review;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_authenticated_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/producto/' . $product->id . '/review', [
            'rating' => 5,
            'comment' => 'Excelente producto, muy buena calidad y rápida entrega.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
        ]);
    }

    public function test_review_requires_rating(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/producto/' . $product->id . '/review', [
            'comment' => 'Un comentario sin rating.',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_review_requires_comment(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/producto/' . $product->id . '/review', [
            'rating' => 4,
        ]);

        $response->assertSessionHasErrors('comment');
    }

    public function test_review_comment_minimum_length(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/producto/' . $product->id . '/review', [
            'rating' => 4,
            'comment' => 'Corto',
        ]);

        $response->assertSessionHasErrors('comment');
    }

    public function test_rating_must_be_between_1_and_5(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/producto/' . $product->id . '/review', [
            'rating' => 6,
            'comment' => 'Un comentario largo suficiente para pasar la validación.',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_unauthenticated_user_cannot_create_review(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/producto/' . $product->id . '/review', [
            'rating' => 5,
            'comment' => 'Un comentario largo suficiente para pasar la validación.',
        ]);

        $response->assertRedirect('/login');
    }

    private function seedRoles(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
