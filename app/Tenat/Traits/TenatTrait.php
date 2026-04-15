<?php

namespace App\Tenat\Traits;

use App\Scope\Tenat\TenatScope;
use App\TenatObserver\TenatObserver;

trait TenatTrait
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenatScope);
        static::observe(new TenatObserver);
    }
}