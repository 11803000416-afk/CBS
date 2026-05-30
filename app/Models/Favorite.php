<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'vehicle_id', 'saved_at'];

    protected $casts = [
        'saved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public static function isFavorited(int $userId, int $vehicleId): bool
    {
        return self::where('user_id', $userId)
            ->where('vehicle_id', $vehicleId)
            ->exists();
    }

    public static function toggleFavorite(int $userId, int $vehicleId)
    {
        if (self::isFavorited($userId, $vehicleId)) {
            return self::where('user_id', $userId)
                ->where('vehicle_id', $vehicleId)
                ->delete();
        }

        return self::create([
            'user_id' => $userId,
            'vehicle_id' => $vehicleId,
            'saved_at' => now(),
        ]);
    }
}
