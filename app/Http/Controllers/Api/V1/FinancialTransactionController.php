<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FinancialTransaction;

class FinancialTransactionController extends CrudController
{
    protected string $modelClass = FinancialTransaction::class;
}
