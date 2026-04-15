<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Brand;

class BrandController extends CrudController
{
    protected string $modelClass = Brand::class;
}
