<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'type',
        'user_id',   // null = global broadcast; set = personal
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Notifications visible to a specific user:
     *   • global broadcasts (user_id IS NULL)
     *   • personal notifications addressed to them
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')
              ->orWhere('user_id', $userId);
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public static function broadcast(string $title, string $body, string $type = 'general'): self
    {
        return static::create([
            'title'   => $title,
            'body'    => $body,
            'type'    => $type,
            'user_id' => null,   // visible to everyone
        ]);
    }

    public static function notify(int $userId, string $title, string $body, string $type = 'general'): self
    {
        return static::create([
            'title'   => $title,
            'body'    => $body,
            'type'    => $type,
            'user_id' => $userId,
        ]);
    }

    public function markRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }
}
