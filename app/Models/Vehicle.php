<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'created_by',
        'brand',
        'model',
        'year',
        'mileage',
        'price',
        'currency',
        'description',
        'images',
        'videos',
        'status',
        'transmission',
        'fuel_type',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'price' => 'decimal:2',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function sellerRequest(): HasOne
    {
        return $this->hasOne(SellerRequest::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(VehicleReview::class);
    }

    public function publishedReviews(): HasMany
    {
        return $this->hasMany(VehicleReview::class)->where('status', 'published');
    }

    // 🔥 PREMIUM FEATURES RELATIONSHIPS
    public function valuation(): HasOne
    {
        return $this->hasOne(VehicleValuation::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedBy(int $userId): bool
    {
        return Favorite::where('user_id', $userId)
            ->where('vehicle_id', $this->id)
            ->exists();
    }

    public function getFavoritesCount(): int
    {
        return $this->favorites()->count();
    }

    public function averageRating(): float
    {
        $average = $this->publishedReviews()->avg('rating');

        return (float) ($average ?? 0);
    }

    public function reviewCount(): int
    {
        return $this->publishedReviews()->count();
    }

    // Scope methods for filtering
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', '!=', 'pending');
    }

    // Record vehicle view
    public function recordView(): void
    {
        $this->increment('views_count');
        Analytics::recordMetric('vehicle_views', $this->brand . ' ' . $this->model);
    }
}

