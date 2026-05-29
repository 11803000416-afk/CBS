<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'is_read',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the recipient of the message.
     */
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope to get messages between two users.
     */
    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('from_user_id', $userId1)->where('to_user_id', $userId2)
              ->orWhere('from_user_id', $userId2)->where('to_user_id', $userId1);
        });
    }

    /**
     * Scope to get unread messages for a user.
     */
    public function scopeUnread($query, $userId)
    {
        return $query->where('to_user_id', $userId)->where('is_read', false);
    }

    /**
     * Get unread count for a user.
     */
    public static function unreadCount($userId)
    {
        return static::unread($userId)->count();
    }

    /**
     * Get unique chat partners for a user.
     */
    public static function getChatPartners($userId)
    {
        return static::where(function ($q) use ($userId) {
            $q->where('from_user_id', $userId)
              ->orWhere('to_user_id', $userId);
        })
        ->with(['fromUser', 'toUser'])
        ->latest('created_at')
        ->get()
        ->map(function ($message) use ($userId) {
            return $message->from_user_id === $userId ? $message->toUser : $message->fromUser;
        })
        ->unique('id')
        ->values();
    }
}
