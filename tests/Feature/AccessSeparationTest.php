<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_user_can_access_platform_area_only(): void
    {
        $user = $this->createPlatformUser(User::ROLE_SUPER_ADMIN);

        $this->actingAs($user)
            ->get('/platform/dashboard')
            ->assertOk()
            ->assertSee('Painel administrativo SaaS');

        $this->actingAs($user)
            ->get('/support/dashboard')
            ->assertOk()
            ->assertSee('Painel de atendimento aos clientes');

        $this->actingAs($user)
            ->get('/app/dashboard')
            ->assertForbidden();
    }

    public function test_support_user_can_access_support_area_but_not_platform_admin_area(): void
    {
        $user = $this->createPlatformUser(User::ROLE_SUPPORT, 'suporte@example.com');

        $this->actingAs($user)
            ->get('/support/dashboard')
            ->assertOk()
            ->assertSee('Painel de atendimento aos clientes');

        $this->actingAs($user)
            ->get('/platform/dashboard')
            ->assertForbidden();

        $this->actingAs($user)
            ->get('/app/dashboard')
            ->assertForbidden();
    }

    public function test_tenant_user_can_access_clinic_area_only(): void
    {
        $tenant = Tenant::create(['name' => 'Clinica Central']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Dra. Ana',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get('/app/dashboard')
            ->assertOk()
            ->assertSee('Clinica Central');

        $this->actingAs($user)
            ->get('/platform/dashboard')
            ->assertForbidden();
    }

    public function test_dashboard_redirects_to_the_authenticated_user_area(): void
    {
        $platformUser = $this->createPlatformUser(User::ROLE_SUPER_ADMIN);

        $this->actingAs($platformUser)
            ->get('/dashboard')
            ->assertRedirect('/platform/dashboard');

        $supportUser = $this->createPlatformUser(User::ROLE_SUPPORT, 'suporte@example.com');

        $this->actingAs($supportUser)
            ->get('/dashboard')
            ->assertRedirect('/support/dashboard');

        $tenant = Tenant::create(['name' => 'Clinica Central']);
        $tenantUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Dra. Ana',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($tenantUser)
            ->get('/dashboard')
            ->assertRedirect('/app/dashboard');
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
}
