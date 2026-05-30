<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Vehicle;

class FavoriteService
{
    public function toggleFavorite(int $userId, int $vehicleId)
    {
        return Favorite::toggleFavorite($userId, $vehicleId);
    }

    public function getFavorites(int $userId)
    {
        return Favorite::where('user_id', $userId)
            ->with('vehicle')
            ->orderBy('saved_at', 'desc')
            ->paginate(12);
    }

    public function isFavorited(int $userId, int $vehicleId): bool
    {
        return Favorite::isFavorited($userId, $vehicleId);
    }

    public function countFavorites(int $userId): int
    {
        return Favorite::where('user_id', $userId)->count();
    }

    public function getFavoritedVehicles(int $userId)
    {
        return Vehicle::whereIn('id', function ($query) use ($userId) {
            $query->select('vehicle_id')
                ->from('favorites')
                ->where('user_id', $userId);
        })->get();
    }
}
