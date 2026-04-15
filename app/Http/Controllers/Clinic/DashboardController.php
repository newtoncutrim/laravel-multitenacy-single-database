<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('clinic.dashboard', [
            'clientsCount' => Client::query()->count(),
            'petsCount' => Pet::query()->count(),
            'appointmentsCount' => Appointment::query()->count(),
        ]);
    }
}
