<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\InventoryMovement;

class InventoryMovementController extends CrudController
{
    protected string $modelClass = InventoryMovement::class;
}
