<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('support.dashboard', [
            'tenantsCount' => Tenant::query()->count(),
            'tenantUsersCount' => User::query()->whereNotNull('tenant_id')->count(),
            'clientsCount' => Client::query()->count(),
            'petsCount' => Pet::query()->count(),
            'appointmentsCount' => Appointment::query()->count(),
        ]);
    }
}
