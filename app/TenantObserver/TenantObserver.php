<?php

namespace App\TenantObserver;

use App\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Model;

class TenantObserver
{
    public function creating(Model $model): void
    {
        $tenantId = TenantManager::getTenantId();

        if ($tenantId !== null) {
            $model->setAttribute('tenant_id', $tenantId);
        }
    }
}
