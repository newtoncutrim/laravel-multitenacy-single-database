<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\InventoryLocation;

class InventoryLocationController extends CrudController
{
    protected string $modelClass = InventoryLocation::class;
}
