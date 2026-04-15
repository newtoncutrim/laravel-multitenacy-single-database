<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Appointment;

class AppointmentController extends CrudController
{
    protected string $modelClass = Appointment::class;
}
