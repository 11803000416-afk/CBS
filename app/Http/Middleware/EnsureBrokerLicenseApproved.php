<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBrokerLicenseApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasRole(User::ROLE_BROKER) && ! $user->isBrokerLicenseApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Broker license approval is required before dealing in vehicles.',
                ], 403);
            }

            return redirect()
                ->route('broker.license.show')
                ->with('error', 'Your dealer license must be approved by admin before you can deal in vehicles and transactions.');
        }

        return $next($request);
    }
}
