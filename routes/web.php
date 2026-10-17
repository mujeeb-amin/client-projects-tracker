<?php

use App\Http\Controllers\ClientController;

use App\Http\Controllers\ProjectController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/clients', [ClientController::class, 'index']);

Route::get('/clients/create', [ClientController::class, 'create']);

Route::post('/clients', [ClientController::class, 'store']);

Route::get('/clients/{id}/edit', [ClientController::class, 'edit']);

Route::put('/clients/{id}', [ClientController::class, 'update']);

Route::delete('/clients/{id}', [ClientController::class, 'destroy']);



Route::get('/projects/create', [ProjectController::class, 'create']);

Route::post('/projects',[ProjectController::class, 'store']);


Route::get('/projects', [ProjectController::class, 'index']);

Route::get('/projects/{id}/edit', [ProjectController::class, 'edit']);

Route::put('/projects/{id}', [ProjectController::class, 'update']);

Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);


Route::get('/payments', [PaymentController::class, 'index']);

Route::get('/payments/create', [PaymentController::class, 'create']);

Route::post('/payments', [PaymentController::class, 'store']);


Route::get('/payments/{id}/edit', [PaymentController::class, 'edit']);

Route::put('/payments/{id}', [PaymentController::class, 'update']);

Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');
Route::get('/register', [AuthController::class, 'showRegister']);

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('auth')
    ->name('reports.index');


// Route::ger(text)