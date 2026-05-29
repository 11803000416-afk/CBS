<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_SELLER = 'seller';
    public const ROLE_BROKER = 'broker';
    public const ROLE_AGENT = self::ROLE_BROKER;
    public const ROLE_BUYER = 'buyer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'is_active',
        'dealer_license_number',
        'dealer_license_document_path',
        'dealer_license_status',
        'dealer_license_admin_notes',
        'dealer_license_submitted_at',
        'dealer_license_reviewed_at',
        'dealer_license_reviewed_by',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'dealer_license_submitted_at' => 'datetime',
        'dealer_license_reviewed_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function buyerProfile(): HasOne
    {
        return $this->hasOne(Buyer::class);
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function listedVehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'created_by');
    }

    public function assignedInquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function bookingsAsBuyer(): HasMany
    {
        return $this->hasMany(Booking::class, 'buyer_id');
    }

    public function bookingsAsSeller(): HasMany
    {
        return $this->hasMany(Booking::class, 'seller_id');
    }

    public function chatRooms(): HasMany
    {
        return $this->hasMany(ChatRoom::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function offersAsBuyer(): HasMany
    {
        return $this->hasMany(Offer::class, 'buyer_id');
    }

    public function offersAsSeller(): HasMany
    {
        return $this->hasMany(Offer::class, 'seller_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'broker_id');
    }

    public function vehicleReviews(): HasMany
    {
        return $this->hasMany(VehicleReview::class, 'reviewer_id');
    }

    public function dealerLicenseReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealer_license_reviewed_by');
    }

    public function twoFactorAuthentication(): HasOne
    {
        return $this->hasOne(TwoFactorAuthentication::class);
    }

    public function isBrokerLicenseApproved(): bool
    {
        if (! $this->hasRole(self::ROLE_BROKER)) {
            return true;
        }

        return $this->dealer_license_status === 'approved';
    }

    public function shouldNotifyAdminForBrokerApproval(): bool
    {
        return $this->hasRole(self::ROLE_BROKER)
            && ! empty($this->dealer_license_number)
            && $this->dealer_license_status === 'not_submitted';
    }

    public function hasRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        $userRole = $this->normalizeRole($this->role);
        if ($userRole === null) {
            return false;
        }

        $normalizedRoles = array_values(array_unique(array_filter(array_map(
            fn ($role) => $this->normalizeRole($role),
            $roles
        ))));

        return in_array($userRole, $normalizedRoles, true);
    }

    public function getNormalizedRoleAttribute(): ?string
    {
        return $this->normalizeRole($this->role);
    }

    private function normalizeRole(?string $role): ?string
    {
        if ($role === null || $role === '') {
            return null;
        }

        return match (strtolower($role)) {
            'agent' => self::ROLE_BROKER,
            self::ROLE_ADMIN, self::ROLE_BROKER, self::ROLE_SELLER, self::ROLE_BUYER => strtolower($role),
            default => null,
        };
    }

    /**
     * Check if 2FA is enabled for this user.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->twoFactorAuthentication?->isEnabled() ?? false;
    }

    /**
     * Get or create 2FA record for this user.
     */
    public function getOrCreateTwoFactorAuthentication(): TwoFactorAuthentication
    {
        return $this->twoFactorAuthentication ?? $this->twoFactorAuthentication()->create();
    }

    /**
     * Enable 2FA for this user.
     */
    public function enableTwoFactor(): void
    {
        $twoFactor = $this->getOrCreateTwoFactorAuthentication();
        $twoFactor->two_factor_confirmed = true;
        $twoFactor->two_factor_enabled_at = now();
        $twoFactor->save();
    }

    /**
     * Disable 2FA for this user.
     */
    public function disableTwoFactor(): void
    {
        if ($this->twoFactorAuthentication) {
            $this->twoFactorAuthentication->update([
                'two_factor_confirmed' => false,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_disabled_at' => now(),
            ]);
        }
    }
}
