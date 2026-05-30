<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model_name',
        'model_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function logActivity(
        ?int $userId,
        string $action,
        ?string $modelName = null,
        ?int $modelId = null,
        ?string $description = null,
    ): self {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'model_name' => $modelName,
            'model_id' => $modelId,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function getActivitySummary($days = 30)
    {
        return self::where('created_at', '>=', now()->subDays($days))
            ->groupBy('action')
            ->selectRaw('action, COUNT(*) as count')
            ->get();
    }
}
