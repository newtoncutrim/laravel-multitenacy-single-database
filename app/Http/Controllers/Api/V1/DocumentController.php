<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Document;

class DocumentController extends CrudController
{
    protected string $modelClass = Document::class;
}
