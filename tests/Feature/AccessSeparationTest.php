<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_user_can_access_platform_area_only(): void
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get('/platform/dashboard')
            ->assertOk()
            ->assertSee('Painel administrativo SaaS');

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
        $platformUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($platformUser)
            ->get('/dashboard')
            ->assertRedirect('/platform/dashboard');

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
}
