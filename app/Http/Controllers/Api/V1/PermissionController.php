<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Permission;

class PermissionController extends CrudController
{
    protected string $modelClass = Permission::class;
}
