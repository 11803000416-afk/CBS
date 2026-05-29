<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Relaxed rate limits for development; production should use stricter limits
        $isLocal = app()->environment('local');
        
        RateLimiter::for('api', function (Request $request) {
            $limit = app()->environment('local') ? 1000 : 60;
            return Limit::perMinute($limit)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            $email = strtolower((string) $request->input('email'));
            $limit = app()->environment('local') ? 100 : 5;
            return Limit::perMinute($limit)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('register', function (Request $request) {
            $limit = app()->environment('local') ? 100 : 3;
            return Limit::perMinute($limit)->by($request->ip());
        });

        // Custom route model binding for chat with fallback
        Route::model('user', User::class);
        Route::bind('user', function ($value) {
            $user = User::find($value);
            if (!$user) {
                abort(redirect('/chat')->with('error', 'User not found'));
            }
            return $user;
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
