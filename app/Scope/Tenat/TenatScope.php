<?php

namespace App\Scope\Tenat;

use App\Tenat\ManagerTenat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenatScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $tenat = (new ManagerTenat)->getTenatId();
        $builder->where('tenat_id', $tenat);

    }
}