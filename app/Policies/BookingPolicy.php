<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->buyer_id || 
               $user->id === $booking->seller_id || 
               $user->hasRole('admin');
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->seller_id || 
               $user->hasRole('admin');
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->buyer_id || 
               $user->id === $booking->seller_id || 
               $user->hasRole('admin');
    }
}
