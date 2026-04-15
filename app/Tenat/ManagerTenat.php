<?php

namespace App\Tenat;

class ManagerTenat
{
    public static function getTenatId()
    {
        return auth()->user()->tenat_id;
    }
}