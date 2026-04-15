<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Vaccine;

class VaccineController extends CrudController
{
    protected string $modelClass = Vaccine::class;
}
