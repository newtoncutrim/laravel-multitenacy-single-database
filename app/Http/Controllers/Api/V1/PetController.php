<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Pet;

class PetController extends CrudController
{
    protected string $modelClass = Pet::class;
}
