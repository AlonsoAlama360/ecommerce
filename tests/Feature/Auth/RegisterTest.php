<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_registration_page_is_displayed(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'terms' => true,
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
        ]);
    }

    public function test_registration_fails_without_required_fields(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password', 'terms']);
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'existing@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_fails_without_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => 'Secret123',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('password');
    }

    private function seedRoles(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrador', 'is_admin' => true, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cliente', 'display_name' => 'Cliente', 'is_admin' => false, 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
