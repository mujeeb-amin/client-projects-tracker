<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AccountDetailsController;

// Public routes
Route::get('/', fn () => view('welcome'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    // NOTE: Public registration creates an ordinary user. Disable these routes
    // if only Super Admin should be allowed to create accounts.
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// All application routes require a logged-in (and, via existing middleware, active) user.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')->name('dashboard');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->middleware('permission:clients.view');
    Route::get('/clients/create', [ClientController::class, 'create'])->middleware('permission:clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->middleware('permission:clients.create');
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->middleware('permission:clients.edit');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->middleware('permission:clients.edit');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->middleware('permission:clients.delete');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->middleware('permission:projects.view');
    Route::get('/projects/create', [ProjectController::class, 'create'])->middleware('permission:projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('permission:projects.create');
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->middleware('permission:projects.edit');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->middleware('permission:projects.edit');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->middleware('permission:projects.delete');

    // Payments: fixed routes before variable paths
    Route::get('/payments', [PaymentController::class, 'index'])->middleware('permission:payments.view');
    Route::get('/payments/create', [PaymentController::class, 'create'])->middleware('permission:payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->middleware('permission:payments.create');
    Route::get('/payments/{id}/receipt/pdf', [PaymentController::class, 'downloadReceipt'])
        ->middleware('permission:payments.view')->name('payments.receipt.pdf');
    Route::get('/payments/{id}/receipt', [PaymentController::class, 'receipt'])
        ->middleware('permission:payments.view')->name('payments.receipt');
    Route::get('/payments/{id}/edit', [PaymentController::class, 'edit'])->middleware('permission:payments.edit');
    Route::put('/payments/{id}', [PaymentController::class, 'update'])->middleware('permission:payments.edit');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->middleware('permission:payments.delete');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:reports.view')->name('reports.index');

    // Personal account routes: authenticated users can read their own profile,
    // update allowed details and change their own password.
    Route::get('/profile/me', [ProfileController::class, 'me'])->name('profile.me');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])
        ->name('profile.change-password');
    Route::put('/account/details', [AccountDetailsController::class, 'update'])
        ->name('account.details.update');

    // User Management: all methods must enforce Super Admin inside controller.
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status', [UserManagementController::class, 'toggleStatus'])->name('users.status');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/test-layout', fn () => view('test-layout'));
});
