<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function view(User $user, Client $client): bool
    {
        return $this->belongsToSameTenant($user, $client);
    }

    public function create(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function update(User $user, Client $client): bool
    {
        return $this->belongsToSameTenant($user, $client);
    }

    public function delete(User $user, Client $client): bool
    {
        return $this->belongsToSameTenant($user, $client);
    }

    private function belongsToSameTenant(User $user, Client $client): bool
    {
        return $client->tenant_id === $user->tenant_id;
    }
}
