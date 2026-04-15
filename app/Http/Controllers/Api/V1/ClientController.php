<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Client;

class ClientController extends CrudController
{
    protected string $modelClass = Client::class;
}
