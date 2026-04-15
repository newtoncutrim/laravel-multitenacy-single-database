<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('platform.dashboard', [
            'tenantsCount' => Tenant::query()->count(),
            'tenantUsersCount' => User::query()->whereNotNull('tenant_id')->count(),
            'plansCount' => SubscriptionPlan::query()->count(),
            'subscriptionsCount' => TenantSubscription::query()->count(),
        ]);
    }
}
