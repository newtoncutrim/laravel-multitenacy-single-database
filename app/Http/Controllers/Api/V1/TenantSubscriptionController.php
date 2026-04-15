<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TenantSubscription;

class TenantSubscriptionController extends CrudController
{
    protected string $modelClass = TenantSubscription::class;
}
