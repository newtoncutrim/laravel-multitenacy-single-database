<?php

namespace App\Modules\Clinic\Actions;

use App\Models\Client;

class CreateClientAction
{
    public function execute(array $data): Client
    {
        return Client::create($data);
    }
}
