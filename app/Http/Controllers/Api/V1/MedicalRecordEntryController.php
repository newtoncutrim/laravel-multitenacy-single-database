<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\MedicalRecordEntry;

class MedicalRecordEntryController extends CrudController
{
    protected string $modelClass = MedicalRecordEntry::class;
}
