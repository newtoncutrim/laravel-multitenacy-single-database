<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_client_for_their_tenant(): void
    {
        $user = $this->createUser();

        Sanctum::actingAs($user);

        $this->postJson('/api/v1/clients', [
            'name' => 'Maria Oliveira',
            'phone' => '(11) 99999-9999',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Maria Oliveira');

        $this->assertDatabaseHas('clients', [
            'tenant_id' => $user->tenant_id,
            'name' => 'Maria Oliveira',
        ]);
    }

    public function test_clients_index_is_scoped_to_the_authenticated_tenant(): void
    {
        $user = $this->createUser();
        $otherTenant = Tenant::create(['name' => 'Outra Clinica']);

        Client::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Cliente escondido',
        ]);

        Sanctum::actingAs($user);

        Client::create([
            'name' => 'Cliente visivel',
        ]);

        $this->getJson('/api/v1/clients')
            ->assertOk()
            ->assertSee('Cliente visivel')
            ->assertDontSee('Cliente escondido');
    }

    private function createUser(): User
    {
        $tenant = Tenant::create(['name' => 'Clinica Central']);

        return User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Dra. Ana',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);
    }
}
