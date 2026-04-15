<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;

class PostController extends CrudController
{
    protected string $modelClass = Post::class;
}
