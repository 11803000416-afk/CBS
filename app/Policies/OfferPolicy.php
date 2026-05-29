<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Offer $offer): bool
    {
        return $user->id === $offer->buyer_id || 
               $user->id === $offer->seller_id || 
               $user->hasRole('admin');
    }

    public function update(User $user, Offer $offer): bool
    {
        return $user->id === $offer->seller_id || 
               $user->hasRole('admin');
    }

    public function delete(User $user, Offer $offer): bool
    {
        return $user->id === $offer->buyer_id || 
               $user->id === $offer->seller_id || 
               $user->hasRole('admin');
    }
}
