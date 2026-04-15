<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\MedicalRecord;

class MedicalRecordController extends CrudController
{
    protected string $modelClass = MedicalRecord::class;
}
