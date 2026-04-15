<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Branch;

class BranchController extends CrudController
{
    protected string $modelClass = Branch::class;
}
