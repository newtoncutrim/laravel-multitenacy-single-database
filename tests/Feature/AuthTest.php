<?php

namespace Tests\Feature;

use App\Models\Tenat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_pages_are_available_for_guests(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Entrar na conta');

        $this->get('/register')
            ->assertOk()
            ->assertSee('Criar conta');
    }

    public function test_user_can_register_with_a_tenant(): void
    {
        $response = $this->post('/register', [
            'tenant_name' => 'Acme',
            'name' => 'Ana Silva',
            'email' => 'ana@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('tenats', ['name' => 'Acme']);
        $this->assertDatabaseHas('users', ['email' => 'ana@example.com']);
    }

    public function test_user_can_login_and_logout(): void
    {
        $tenat = Tenat::create(['name' => 'Acme']);
        $user = User::create([
            'tenat_id' => $tenat->id,
            'name' => 'Ana Silva',
            'email' => 'ana@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post('/login', [
            'email' => 'ana@example.com',
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
