<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Booking' => 'App\Policies\BookingPolicy',
        'App\Models\Offer' => 'App\Policies\OfferPolicy',
        'App\Models\Vehicle' => 'App\Policies\VehiclePolicy',
        'App\Models\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\Buyer' => 'App\Policies\BuyerPolicy',
        'App\Models\Seller' => 'App\Policies\SellerPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
