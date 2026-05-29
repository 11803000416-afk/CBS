<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'buyer_id',
        'seller_id',
        'broker_id',
        'sale_price',
        'broker_commission',
        'currency',
        'status',
        'payment_requested_at',
        'reviewed_at',
        'reviewed_by',
        'completed_at',
        'notes',
        'review_notes',
        'agreement_file',
        'agreement_uploaded_at',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'broker_commission' => 'decimal:2',
        'payment_requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'completed_at' => 'datetime',
        'agreement_uploaded_at' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'broker_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function otps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionOtp::class);
    }

    public function review(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VehicleReview::class);
    }
}
