<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends CrudController
{
    protected string $modelClass = SubscriptionPlan::class;
}
