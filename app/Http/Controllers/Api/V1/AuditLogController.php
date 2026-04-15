<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AuditLog;

class AuditLogController extends CrudController
{
    protected string $modelClass = AuditLog::class;
}
