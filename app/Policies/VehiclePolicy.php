<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the vehicle
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        // Anyone can view available vehicles
        if ($vehicle->status === 'available') {
            return true;
        }

        // Owner of vehicle can view
        return $user->id === $vehicle->created_by || $user->hasRole('admin');
    }

    /**
     * Determine if the user can update the vehicle
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        // Admin can always update
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check if user is the vehicle creator
        if ($user->id === $vehicle->created_by) {
            return true;
        }

        // Check if user is the seller (for seller account)
        if ($user->hasRole('seller')) {
            $seller = Seller::where('email', $user->email)->first();
            if ($seller && $vehicle->seller_id === $seller->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user can delete the vehicle
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Admin can always delete
        if ($user->hasRole('admin')) {
            return true;
        }

        // Creator can delete own vehicle
        return $user->id === $vehicle->created_by;
    }

    /**
     * Determine if the user can create a vehicle
     */
    public function create(User $user): bool
    {
        // Only sellers and admins can create vehicles
        return $user->hasRole(['seller', 'admin', 'buyer']);
    }
}
