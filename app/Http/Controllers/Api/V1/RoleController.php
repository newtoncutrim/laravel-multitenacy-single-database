<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;

class RoleController extends CrudController
{
    protected string $modelClass = Role::class;
}
