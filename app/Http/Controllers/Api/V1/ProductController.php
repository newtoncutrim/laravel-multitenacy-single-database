<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;

class ProductController extends CrudController
{
    protected string $modelClass = Product::class;
}
