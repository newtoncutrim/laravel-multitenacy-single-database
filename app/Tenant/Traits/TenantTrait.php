<?php

namespace App\Tenant\Traits;

use App\Scope\Tenant\TenantScope;
use App\TenantObserver\TenantObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait TenantTrait
{
    protected static function bootTenantTrait(): void
    {
        static::addGlobalScope(new TenantScope());
        static::observe(TenantObserver::class);
    }
}
