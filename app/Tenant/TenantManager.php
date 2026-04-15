<?php

namespace App\Tenant;

class TenantManager
{
    public static function getTenantId(): ?int
    {
        return auth()->user()?->tenant_id;
    }
}
