<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FinancialAccount;

class FinancialAccountController extends CrudController
{
    protected string $modelClass = FinancialAccount::class;
}
