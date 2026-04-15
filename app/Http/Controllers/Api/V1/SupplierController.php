<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Supplier;

class SupplierController extends CrudController
{
    protected string $modelClass = Supplier::class;
}
