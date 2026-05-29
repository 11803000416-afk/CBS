<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'buyer_id',
        'seller_id',
        'status',
        'booking_date',
        'booking_time',
        'duration_hours',
        'total_amount',
        'buyer_message',
        'seller_message',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'blue',
            default => 'gray',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'confirmed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            'completed' => 'bg-blue-100 text-blue-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
