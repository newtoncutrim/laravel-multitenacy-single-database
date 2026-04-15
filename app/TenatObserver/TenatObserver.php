<?php

namespace App\TenatObserver;

use App\Tenat\ManagerTenat;
use Illuminate\Database\Eloquent\Model;

class TenatObserver
{
    public function creating(Model $model)
    {
        $model->setAttribute('tenat_id', (new ManagerTenat)->getTenatId());
    }
}