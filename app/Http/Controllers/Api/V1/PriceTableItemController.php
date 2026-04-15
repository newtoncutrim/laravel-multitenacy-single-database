<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PriceTableItem;

class PriceTableItemController extends CrudController
{
    protected string $modelClass = PriceTableItem::class;
}
