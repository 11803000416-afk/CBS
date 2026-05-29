<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'transaction_id',
        'reviewer_id',
        'buyer_id',
        'seller_id',
        'rating',
        'title',
        'comment',
        'pros',
        'cons',
        'would_recommend',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'would_recommend' => 'boolean',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function ratingLabel(): string
    {
        return match (true) {
            $this->rating >= 5 => 'Excellent',
            $this->rating >= 4 => 'Very Good',
            $this->rating >= 3 => 'Good',
            $this->rating >= 2 => 'Fair',
            default => 'Poor',
        };
    }
}