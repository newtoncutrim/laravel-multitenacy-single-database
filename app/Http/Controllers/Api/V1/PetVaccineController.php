<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PetVaccine;

class PetVaccineController extends CrudController
{
    protected string $modelClass = PetVaccine::class;
}
