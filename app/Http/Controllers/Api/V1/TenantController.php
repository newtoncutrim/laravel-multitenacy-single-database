<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tenant;

class TenantController extends CrudController
{
    protected string $modelClass = Tenant::class;
}
