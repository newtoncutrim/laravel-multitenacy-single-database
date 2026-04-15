<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;

class ServiceController extends CrudController
{
    protected string $modelClass = Service::class;
}
