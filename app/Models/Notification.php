<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'icon_color',
        'action_url',
        'data',
        'read_at',
        'email_sent',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'data' => 'array',
        'read_at' => 'datetime',
        'email_sent' => 'boolean',
    ];

    // Notification types
    const TYPE_NEW_QUESTION = 'new_question';
    const TYPE_NEW_USER = 'new_user';
    const TYPE_NEW_SUBSCRIPTION = 'new_subscription';
    const TYPE_SUBSCRIPTION_EXPIRING = 'subscription_expiring';
    const TYPE_SUBSCRIPTION_EXPIRED = 'subscription_expired';
    const TYPE_WELCOME = 'welcome';
    const TYPE_SYSTEM = 'system';
    const TYPE_NEW_COURSE = 'new_course';
    const TYPE_NEW_UNIT = 'new_unit';

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Check if the notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for specific notification type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for recent notifications.
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get icon with fallback.
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ?? $this->getDefaultIcon();
    }

    /**
     * Get default icon based on type.
     */
    protected function getDefaultIcon(): string
    {
        return match($this->type) {
            self::TYPE_NEW_QUESTION => 'question-circle-fill',
            self::TYPE_NEW_USER => 'person-plus-fill',
            self::TYPE_NEW_SUBSCRIPTION => 'credit-card-fill',
            self::TYPE_SUBSCRIPTION_EXPIRING => 'exclamation-triangle-fill',
            self::TYPE_SUBSCRIPTION_EXPIRED => 'x-circle-fill',
            self::TYPE_WELCOME => 'hand-thumbs-up-fill',
            self::TYPE_NEW_COURSE => 'book-fill',
            self::TYPE_NEW_UNIT => 'collection-fill',
            default => 'bell-fill',
        };
    }
}
