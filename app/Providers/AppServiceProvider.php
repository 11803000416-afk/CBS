<?php

namespace App\Providers;

use App\Models\SellerRequest;
use App\Models\User;
use App\Models\Vehicle;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Detect database availability. If DB is unavailable, register an offline
        // auth provider and supply minimal demo data to views so the app remains usable.
        $dbAvailable = true;

        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            $dbAvailable = false;
        }

        if (! $dbAvailable) {
            Auth::provider('offline', function ($app, array $config) {
                return new \App\Auth\OfflineUserProvider();
            });

            Config::set('auth.providers.users.driver', 'offline');
            app('auth')->forgetGuards();

            View::composer('layouts.app', function ($view) {
                $view->with('pendingSellerRequestsCount', 0);
                $view->with('pendingBrokerLicenseRequestsCount', 0);
            });

            View::composer('dashboard.index', function ($view) {
                $approvedVehicles = collect(config('demo_vehicles', []))->take(6);
                $view->with('approvedVehicles', $approvedVehicles);
            });

            View::composer('home', function ($view) {
                $featuredVehicles = collect(config('demo_vehicles', []))->take(6);
                $view->with('featuredVehicles', $featuredVehicles);
            });

            View::composer('vehicles.browse-modern', function ($view) {
                $view->with('vehicles', collect(config('demo_vehicles', [])));
            });

            return;
        }

        // Database is available: ensure demo users exist (only outside production),
        // then register the normal view composers that rely on the database.
        $this->ensureDemoUsersExist();

        View::composer('layouts.app', function ($view) {
            $pendingSellerRequestsCount = 0;
            $pendingBrokerLicenseRequestsCount = 0;

            if (auth()->check() && auth()->user()->hasRole(User::ROLE_ADMIN)) {
                $pendingSellerRequestsCount = Cache::remember(
                    'nav:pending_seller_requests_count:v1',
                    now()->addMinutes(2),
                    fn () => SellerRequest::where('status', 'pending')->count()
                );

                $pendingBrokerLicenseRequestsCount = Cache::remember(
                    'nav:pending_broker_license_requests_count:v1',
                    now()->addMinutes(2),
                    fn () => User::where('role', User::ROLE_BROKER)
                        ->where('dealer_license_status', 'pending')
                        ->count()
                );
            }

            $view->with('pendingSellerRequestsCount', $pendingSellerRequestsCount);
            $view->with('pendingBrokerLicenseRequestsCount', $pendingBrokerLicenseRequestsCount);
        });

        View::composer('dashboard.index', function ($view) {
            $approvedVehicles = Cache::remember(
                'dashboard:index:approved_vehicles:v1',
                now()->addMinutes(2),
                fn () => Vehicle::with(['seller', 'sellerRequest'])
                    ->where(function ($query) {
                        $query->where('status', 'available')
                            ->orWhereHas('sellerRequest', function ($sellerRequestQuery) {
                                $sellerRequestQuery->where('status', 'approved');
                            });
                    })
                    ->latest()
                    ->take(6)
                    ->get()
            );

            $view->with('approvedVehicles', $approvedVehicles);
        });

        View::composer('home', function ($view) {
            $featuredVehicles = Cache::remember(
                'home:featured_vehicles:v1',
                now()->addMinutes(2),
                fn () => Vehicle::with(['seller'])
                    ->where('status', 'available')
                    ->latest()
                    ->take(6)
                    ->get()
            );

            $view->with('featuredVehicles', $featuredVehicles);
        });

        View::composer('vehicles.browse-modern', function ($view) {
            $vehicles = Cache::remember(
                'vehicles:browse-modern:available:v1',
                now()->addMinutes(2),
                fn () => Vehicle::where('status', 'available')
                    ->with(['seller'])
                    ->latest()
                    ->paginate(12)
            );

            $view->with('vehicles', $vehicles);
        });
    }

    /**
     * Restore demo users automatically when the users table is empty.
     */
    private function ensureDemoUsersExist(): void
    {
        try {
            if (User::count() > 0) {
                return;
            }

            (new AdminUserSeeder())->run();
        } catch (Throwable $e) {
            // Keep the app usable even if seeding cannot run in the current context.
        }
    }
}
