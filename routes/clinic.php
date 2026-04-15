<?php

use App\Http\Controllers\Clinic\DashboardController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', DashboardController::class)->name('dashboard');

Route::resource('posts', PostController::class);
