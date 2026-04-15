<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;

class UserController extends CrudController
{
    protected string $modelClass = User::class;
}
