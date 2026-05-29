<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorAuthentication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed',
        'two_factor_enabled_at',
        'two_factor_disabled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'two_factor_confirmed' => 'boolean',
        'two_factor_enabled_at' => 'datetime',
        'two_factor_disabled_at' => 'datetime',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'two_factor_authentication';

    /**
     * Get the user that owns this 2FA record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if 2FA is enabled for this user.
     */
    public function isEnabled(): bool
    {
        return $this->two_factor_confirmed && !is_null($this->two_factor_secret);
    }

    /**
     * Get recovery codes as array.
     */
    public function getRecoveryCodes(): array
    {
        return json_decode($this->two_factor_recovery_codes ?? '[]', true);
    }

    /**
     * Set recovery codes from array.
     */
    public function setRecoveryCodes(array $codes): void
    {
        $this->two_factor_recovery_codes = json_encode($codes);
        $this->save();
    }

    /**
     * Use a recovery code.
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->getRecoveryCodes();
        $index = array_search($code, $codes);

        if ($index === false) {
            return false;
        }

        unset($codes[$index]);
        $this->setRecoveryCodes($codes);

        return true;
    }
}
