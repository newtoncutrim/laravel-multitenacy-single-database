<?php

use App\Http\Controllers\TesteController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/a', [TesteController::class, 'teste']);
Route::get('/criar', [PostController::class, 'create']);
Route::get('/listone', [PostController::class, 'show']);

Route::get('/listall', [PostController::class, 'listAll']);

Route::get('/editar', [PostController::class, 'update']);

Route::get('/apagar', [PostController::class, 'destroy']);
