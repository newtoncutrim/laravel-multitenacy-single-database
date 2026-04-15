<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Hospitalization;

class HospitalizationController extends CrudController
{
    protected string $modelClass = Hospitalization::class;
}
