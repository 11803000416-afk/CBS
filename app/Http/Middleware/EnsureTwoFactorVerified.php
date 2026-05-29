<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is an admin and has 2FA enabled, require verification
        if ($user && $user->hasRole('admin') && $user->hasTwoFactorEnabled()) {
            if (!session()->get('two_factor_verified')) {
                return redirect('/auth/two-factor')->withErrors(['code' => 'Please verify with 2FA to continue.']);
            }
        }

        return $next($request);
    }
}
