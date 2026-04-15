<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Integration;

class IntegrationController extends CrudController
{
    protected string $modelClass = Integration::class;
}
