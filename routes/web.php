<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::get('/debug-users', function () {
        $users = \App\Models\User::all(['id', 'name', 'email', 'role', 'is_active']);

        return response()->json([
            'count' => $users->count(),
            'users' => $users,
            'test_password_admin' => \Illuminate\Support\Facades\Hash::check('password', \App\Models\User::where('email', 'admin@cbs.bt')->first()?->password ?? ''),
        ]);
    });
}

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vehicle show page accessible to all authenticated users
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

    // Allow all users to create and manage their own vehicles
    Route::get('/my-vehicles/create', [VehicleController::class, 'create'])->name('my-vehicles.create');
    Route::post('/my-vehicles', [VehicleController::class, 'store'])->name('my-vehicles.store');
    Route::get('/my-vehicles', [VehicleController::class, 'myVehicles'])->name('my-vehicles.index');
    Route::get('/my-vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('my-vehicles.edit');
    Route::put('/my-vehicles/{vehicle}', [VehicleController::class, 'update'])->name('my-vehicles.update');
    Route::delete('/my-vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('my-vehicles.destroy');

    Route::middleware('role:admin,agent')->group(function () {
        Route::resource('vehicles', VehicleController::class)->except('show');
        Route::resource('buyers', BuyerController::class)->except('show');
        Route::resource('sellers', SellerController::class)->except('show');
        Route::resource('transactions', TransactionController::class)->except('show');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::resource('inquiries', InquiryController::class)->except('show');
});
