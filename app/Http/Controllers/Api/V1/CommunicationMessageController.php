<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CommunicationMessage;

class CommunicationMessageController extends CrudController
{
    protected string $modelClass = CommunicationMessage::class;
}
