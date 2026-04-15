<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_returns_platform_area_for_super_admin(): void
    {
        $user = $this->createPlatformUser(User::ROLE_SUPER_ADMIN);

        $this->withHeaders($this->spaHeaders())->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonPath('data.area', 'platform')
            ->assertJsonPath('data.home_path', '/platform/dashboard');
    }

    public function test_api_login_returns_support_area_for_support_user(): void
    {
        $user = $this->createPlatformUser(User::ROLE_SUPPORT, 'suporte@example.com');

        $this->withHeaders($this->spaHeaders())->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonPath('data.area', 'support')
            ->assertJsonPath('data.home_path', '/support/dashboard');
    }

    public function test_api_register_creates_tenant_user_for_clinic_area(): void
    {
        $this->withHeaders($this->spaHeaders())->postJson('/api/auth/register', [
            'tenant_name' => 'Clinica Central',
            'name' => 'Dra. Ana',
            'email' => 'ana@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertCreated()
            ->assertJsonPath('data.area', 'clinic')
            ->assertJsonPath('data.home_path', '/app/dashboard');

        $this->assertDatabaseHas('tenants', [
            'name' => 'Clinica Central',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ana@example.com',
        ]);
    }

    public function test_api_me_returns_current_authenticated_user_area(): void
    {
        $tenant = Tenant::create(['name' => 'Clinica Central']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Dra. Ana',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('data.area', 'clinic')
            ->assertJsonPath('data.tenant_id', $tenant->id);
    }

    private function createPlatformUser(string $roleSlug, string $email = 'admin@example.com'): User
    {
        $role = Role::create([
            'name' => $roleSlug,
            'slug' => $roleSlug,
            'scope' => 'platform',
            'is_system' => true,
        ]);

        $user = User::create([
            'name' => 'Platform User',
            'email' => $email,
            'password' => 'password',
        ]);

        $user->roles()->attach($role);

        return $user;
    }

    private function spaHeaders(): array
    {
        return [
            'Origin' => 'http://localhost:5173',
            'Referer' => 'http://localhost:5173',
        ];
    }
}
