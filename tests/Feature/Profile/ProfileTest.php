<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mi-perfil');

        $response->assertStatus(200);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/mi-perfil', [
            'first_name' => 'Nuevo Nombre',
            'last_name' => 'Nuevo Apellido',
            'email' => $user->email,
            'phone' => '999111222',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Nuevo Nombre',
            'last_name' => 'Nuevo Apellido',
        ]);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create(['password' => 'OldPassword1']);

        $response = $this->actingAs($user)->put('/mi-perfil/password', [
            'current_password' => 'OldPassword1',
            'password' => 'NewPassword1',
            'password_confirmation' => 'NewPassword1',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword1', $user->password));
    }

    public function test_password_update_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => 'OldPassword1']);

        $response = $this->actingAs($user)->put('/mi-perfil/password', [
            'current_password' => 'WrongPassword1',
            'password' => 'NewPassword1',
            'password_confirmation' => 'NewPassword1',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_unauthenticated_user_redirected_from_profile(): void
    {
        $response = $this->get('/mi-perfil');

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
