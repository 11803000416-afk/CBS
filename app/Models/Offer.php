<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'buyer_id',
        'seller_id',
        'offer_amount',
        'message',
        'status',
    ];

    protected $casts = [
        'offer_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
            'accepted' => 'green',
            'rejected' => 'red',
            'expired' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending</span>',
            'accepted' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Accepted</span>',
            'rejected' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Rejected</span>',
            'expired' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Expired</span>',
            default => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Unknown</span>',
        };
    }
}
