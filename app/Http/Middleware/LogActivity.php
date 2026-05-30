<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log important user actions
        if (auth()->check()) {
            $route = $request->route()?->getName();
            
            $actionsToLog = [
                'login.store' => 'User login',
                'register.store' => 'User registration',
                'vehicles.store' => 'Vehicle created',
                'vehicles.update' => 'Vehicle updated',
                'vehicles.destroy' => 'Vehicle deleted',
                'transactions.store' => 'Transaction created',
                'transactions.verify-otp' => 'Transaction verified',
                'bookings.store' => 'Booking created',
                'inquiries.store' => 'Inquiry sent',
            ];

            if (isset($actionsToLog[$route])) {
                ActivityLog::logActivity(
                    auth()->id(),
                    $route,
                    null,
                    null,
                    $actionsToLog[$route]
                );
            }
        }

        return $response;
    }
}
