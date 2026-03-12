<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_login_page_is_displayed(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect();
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect();
    }

    private function seedRoles(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
