<?php

use App\Http\Controllers\Admin\SellerRequestController;
use App\Http\Controllers\Admin\BrokerLicenseApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BrokerLicenseController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ValuationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VehicleReviewController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

if (app()->environment('local') && env('ENABLE_DEBUG_ROUTES', false)) {
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
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:register')
        ->name('register.store');
});

// Email verification routes
Route::get('/email/verify', [AuthController::class, 'showEmailVerification'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Two-Factor Authentication routes
Route::middleware('auth')->group(function () {
    Route::get('/auth/two-factor', [TwoFactorAuthenticationController::class, 'show'])->name('two-factor.show');
    Route::post('/auth/two-factor/enable', [TwoFactorAuthenticationController::class, 'enable'])
        ->middleware('throttle:5,1')
        ->name('two-factor.enable');
    Route::post('/auth/two-factor/verify', [TwoFactorAuthenticationController::class, 'verify'])
        ->middleware('throttle:5,1')
        ->name('two-factor.verify');
    Route::post('/auth/two-factor/disable', [TwoFactorAuthenticationController::class, 'disable'])
        ->middleware('throttle:2,1')
        ->name('two-factor.disable');
    Route::get('/auth/two-factor/download-recovery-codes', [TwoFactorAuthenticationController::class, 'downloadRecoveryCodes'])
        ->name('two-factor.download-recovery-codes');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Public home page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');

// Public vehicles browse - visible to all users (authenticated or not, redirect to login)
Route::middleware('auth')->get('/browse', [VehicleController::class, 'browse'])->name('vehicles.browse');

// Public unified vehicles page - accessible to all users
Route::get('/vehicles/unified', [VehicleController::class, 'unified'])->name('vehicles.unified');

// Public valuation calculator
Route::get('/valuation', [ValuationController::class, 'index'])->name('valuation.index');

// CarWale-style modern browse page - professional vehicle listing with filters
Route::get('/vehicles/shop', function () {
    return view('vehicles.browse-modern');
})->name('vehicles.shop');

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vehicle create page - MUST be before {vehicle} route to avoid 404
    Route::middleware('broker.approved')->group(function () {
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    });

    // Vehicle show page accessible to all authenticated users
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::post('/vehicles/{vehicle}/reviews', [VehicleReviewController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('vehicle-reviews.store');

    // Booking routes - for all authenticated users
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{vehicle}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/{vehicle}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Allow all authenticated users to create vehicles (brokers require license approval)
    Route::middleware('broker.approved')->group(function () {
        Route::get('/my-vehicles/create', [VehicleController::class, 'create'])->name('my-vehicles.create');
        Route::post('/my-vehicles', [VehicleController::class, 'store'])->name('my-vehicles.store');
    });
    Route::get('/my-vehicles', [VehicleController::class, 'myVehicles'])->name('my-vehicles.index');
    Route::middleware('broker.approved')->group(function () {
        Route::get('/my-vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('my-vehicles.edit');
        Route::put('/my-vehicles/{vehicle}', [VehicleController::class, 'update'])->name('my-vehicles.update');
        Route::delete('/my-vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('my-vehicles.destroy');
    });

    // Offer routes - for all authenticated users
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::post('/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::put('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');

    // API routes for vehicle details
    Route::get('/api/vehicles/{vehicle}', [VehicleController::class, 'showApi'])->name('api.vehicles.show');
    Route::get('/api/vehicles-search', [VehicleController::class, 'searchAjax'])->name('api.vehicles.search');

    Route::middleware('role:admin,broker,seller,buyer')->group(function () {
        Route::middleware('broker.approved')->group(function () {
            Route::resource('vehicles', VehicleController::class)->except('show', 'create', 'store');
        });

        // Buyer-safe transaction access (details restricted via policy + role-specific query filter)
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}/download-agreement', [TransactionController::class, 'downloadAgreement'])->name('transactions.download-agreement');
    });

    // Transaction management (create/update/delete) for admin, broker and seller
    Route::middleware('role:admin,broker,seller')->group(function () {
        Route::middleware('broker.approved')->group(function () {
            Route::post('/transactions', [TransactionController::class, 'store'])
                ->middleware('throttle:10,1')
                ->name('transactions.store');
            Route::get('/transactions/create', [TransactionController::class, 'create'])
                ->name('transactions.create');
            Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])
                ->middleware('throttle:10,1')
                ->name('transactions.update');
            Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])
                ->name('transactions.edit');
            Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
                ->middleware('throttle:5,1')
                ->name('transactions.destroy');
        });
    });

    Route::middleware('auth')->group(function () {
        Route::get('/broker/license', [BrokerLicenseController::class, 'show'])->name('broker.license.show');
        Route::post('/broker/license', [BrokerLicenseController::class, 'submit'])->name('broker.license.submit');
    });

    // Management modules should remain admin-only
    Route::middleware('role:admin')->group(function () {
        Route::resource('buyers', BuyerController::class)->except('show');
        Route::resource('sellers', SellerController::class)->except('show');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::post('/transactions/{transaction}/approve-payment', [TransactionController::class, 'approvePayment'])
            ->middleware('throttle:5,1')
            ->name('transactions.approve-payment');
        Route::post('/transactions/{transaction}/reject-payment', [TransactionController::class, 'rejectPayment'])
            ->middleware('throttle:5,1')
            ->name('transactions.reject-payment');
        
        // Seller Request Routes (Admin only)
        Route::get('/seller-requests', [SellerRequestController::class, 'index'])->name('admin.seller-requests.index');
        Route::get('/seller-requests/{sellerRequest}', [SellerRequestController::class, 'show'])->name('admin.seller-requests.show');
        Route::post('/seller-requests/{sellerRequest}/approve', [SellerRequestController::class, 'approve'])->name('admin.seller-requests.approve');
        Route::post('/seller-requests/{sellerRequest}/reject', [SellerRequestController::class, 'reject'])->name('admin.seller-requests.reject');

        // Broker Dealer License Approval (Admin only)
        Route::get('/broker-licenses', [BrokerLicenseApprovalController::class, 'index'])->name('admin.broker-licenses.index');
        Route::get('/broker-licenses/{user}', [BrokerLicenseApprovalController::class, 'show'])->name('admin.broker-licenses.show');
        Route::post('/broker-licenses/{user}/approve', [BrokerLicenseApprovalController::class, 'approve'])->name('admin.broker-licenses.approve');
        Route::post('/broker-licenses/{user}/reject', [BrokerLicenseApprovalController::class, 'reject'])->name('admin.broker-licenses.reject');
    });

    // OTP verification for transactions (used by broker/buyer/seller flows)
    Route::post('/transactions/{transaction}/verify-otp', [TransactionController::class, 'verifyOtp'])
        ->middleware('throttle:5,1')
        ->name('transactions.verify-otp');

    Route::resource('inquiries', InquiryController::class)->except('show');

    // In-app notifications (accessible notification bell)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Chat routes
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/users', [ChatController::class, 'availableUsers'])->name('chat.users');
        Route::get('/unread/count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');
        Route::post('/partners/list', [ChatController::class, 'getChatPartners'])->name('chat.partners');
        Route::post('/send', [ChatController::class, 'send'])->name('chat.send');
        Route::get('/{user}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/{user}', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/{user}/fetch', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
        Route::post('/{user}/read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');
        Route::delete('/{message}', [ChatController::class, 'delete'])->name('chat.delete');
    });

    // Payroll Management Routes (Admin/Payroll Staff only)
    Route::middleware('role:admin')->prefix('payroll')->name('payroll.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [PayrollController::class, 'dashboard'])->name('dashboard');

        // Employee Management
        Route::get('/employees', [PayrollController::class, 'employees'])->name('employees');
        Route::get('/employees/create', [PayrollController::class, 'createEmployee'])->name('employees.create');
        Route::post('/employees', [PayrollController::class, 'storeEmployee'])->name('employees.store');
        Route::get('/employees/{employee}/edit', [PayrollController::class, 'editEmployee'])->name('employees.edit');
        Route::put('/employees/{employee}', [PayrollController::class, 'updateEmployee'])->name('employees.update');

        // Salary Management
        Route::get('/salaries', [PayrollController::class, 'salaries'])->name('salaries');
        Route::get('/salaries/create', [PayrollController::class, 'createSalary'])->name('salaries.create');
        Route::post('/salaries', [PayrollController::class, 'storeSalary'])->name('salaries.store');

        // Payroll Processing
        Route::get('/payroll', [PayrollController::class, 'payroll'])->name('payroll');
        Route::post('/payroll/generate', [PayrollController::class, 'generatePayroll'])->name('payroll.generate');
        Route::put('/payroll/{payroll}/approve', [PayrollController::class, 'approvePayroll'])->name('payroll.approve');
        Route::put('/payroll/{payroll}/process-payment', [PayrollController::class, 'processPayment'])->name('payroll.process-payment');
        Route::get('/payroll/{payroll}', [PayrollController::class, 'showPayroll'])->name('payroll.show');

        // Attendance Management
        Route::get('/attendance', [PayrollController::class, 'attendance'])->name('attendance');
        Route::post('/attendance/mark', [PayrollController::class, 'markAttendance'])->name('attendance.mark');

        // Reports
        Route::get('/reports', [PayrollController::class, 'generateReport'])->name('reports');
        Route::get('/export', [PayrollController::class, 'exportPayroll'])->name('export');
    });
});
