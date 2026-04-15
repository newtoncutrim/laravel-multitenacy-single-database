<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PriceTable;

class PriceTableController extends CrudController
{
    protected string $modelClass = PriceTable::class;
}
