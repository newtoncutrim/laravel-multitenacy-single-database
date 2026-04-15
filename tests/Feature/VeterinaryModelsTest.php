<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VeterinaryModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_veterinary_records_are_attached_to_the_authenticated_tenant(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $client = Client::create([
            'name' => 'Maria Oliveira',
            'phone' => '(11) 99999-9999',
        ]);

        $pet = Pet::create([
            'client_id' => $client->id,
            'name' => 'Rex',
            'species' => 'Cachorro',
            'breed' => 'SRD',
        ]);

        $appointment = Appointment::create([
            'client_id' => $client->id,
            'pet_id' => $pet->id,
            'user_id' => $user->id,
            'scheduled_at' => now()->addDay(),
            'reason' => 'Consulta inicial',
        ]);

        $medicalRecord = MedicalRecord::create([
            'client_id' => $client->id,
            'pet_id' => $pet->id,
            'user_id' => $user->id,
            'appointment_id' => $appointment->id,
            'recorded_at' => now(),
            'weight' => 12.5,
            'diagnosis' => 'Animal saudavel.',
        ]);

        $this->assertSame($user->tenant_id, $client->tenant_id);
        $this->assertSame($user->tenant_id, $pet->tenant_id);
        $this->assertSame($user->tenant_id, $appointment->tenant_id);
        $this->assertSame($user->tenant_id, $medicalRecord->tenant_id);
    }

    public function test_veterinary_queries_show_only_the_authenticated_tenant_records(): void
    {
        $user = $this->createUser();

        $otherTenant = Tenant::create(['name' => 'Outra Clinica']);
        Client::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Cliente escondido',
        ]);

        $this->actingAs($user);

        Client::create([
            'name' => 'Cliente visivel',
        ]);

        $clients = Client::query()->pluck('name')->all();

        $this->assertContains('Cliente visivel', $clients);
        $this->assertNotContains('Cliente escondido', $clients);
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
