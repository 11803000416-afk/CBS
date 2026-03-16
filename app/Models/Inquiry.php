<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'buyer_id',
        'assigned_to',
        'message',
        'meeting_location',
        'preferred_time',
        'special_requirements',
        'response',
        'status',
    ];

    protected $casts = [
        'preferred_time' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function assignedBroker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
