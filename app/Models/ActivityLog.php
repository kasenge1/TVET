<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
        'resource_id',
        'metadata',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the resource that was affected.
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Log an activity.
     */
    public static function log(
        string $action,
        ?Model $resource = null,
        ?array $metadata = null,
        ?Request $request = null
    ): self {
        $request = $request ?? request();

        return static::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => $resource ? get_class($resource) : null,
            'resource_id' => $resource ? $resource->getKey() : null,
            'metadata' => $metadata,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Scope to filter by action.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by resource type.
     */
    public function scopeForResourceType($query, string $type)
    {
        return $query->where('resource_type', $type);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Get human-readable action name.
     */
    public function getActionLabelAttribute(): string
    {
        $labels = [
            'login' => 'Logged in',
            'logout' => 'Logged out',
            'login_failed' => 'Failed login attempt',
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'viewed' => 'Viewed',
            'searched' => 'Searched',
            'enrolled' => 'Enrolled',
            'unenrolled' => 'Unenrolled',
            'bookmarked' => 'Bookmarked',
            'unbookmarked' => 'Removed bookmark',
            'subscribed' => 'Subscribed',
            'subscription_expired' => 'Subscription expired',
            'payment_completed' => 'Payment completed',
            'payment_failed' => 'Payment failed',
            'profile_updated' => 'Updated profile',
            'password_changed' => 'Changed password',
        ];

        return $labels[$this->action] ?? ucwords(str_replace('_', ' ', $this->action));
    }

    /**
     * Get action icon for display.
     */
    public function getActionIconAttribute(): string
    {
        $icons = [
            'login' => 'box-arrow-in-right',
            'logout' => 'box-arrow-right',
            'login_failed' => 'exclamation-triangle',
            'created' => 'plus-circle',
            'updated' => 'pencil',
            'deleted' => 'trash',
            'viewed' => 'eye',
            'searched' => 'search',
            'enrolled' => 'mortarboard',
            'unenrolled' => 'x-circle',
            'bookmarked' => 'bookmark-plus',
            'unbookmarked' => 'bookmark-dash',
            'subscribed' => 'credit-card',
            'subscription_expired' => 'clock-history',
            'payment_completed' => 'check-circle',
            'payment_failed' => 'x-circle',
            'profile_updated' => 'person',
            'password_changed' => 'key',
        ];

        return $icons[$this->action] ?? 'activity';
    }

    /**
     * Get action color for display.
     */
    public function getActionColorAttribute(): string
    {
        $colors = [
            'login' => 'success',
            'logout' => 'secondary',
            'login_failed' => 'danger',
            'created' => 'primary',
            'updated' => 'warning',
            'deleted' => 'danger',
            'viewed' => 'info',
            'searched' => 'info',
            'enrolled' => 'success',
            'unenrolled' => 'warning',
            'bookmarked' => 'primary',
            'unbookmarked' => 'secondary',
            'subscribed' => 'warning',
            'subscription_expired' => 'warning',
            'payment_completed' => 'success',
            'payment_failed' => 'danger',
            'profile_updated' => 'info',
            'password_changed' => 'warning',
            'registered' => 'success',
        ];

        return $colors[$this->action] ?? 'secondary';
    }

    /**
     * Get a human-readable description of the activity.
     */
    public function getDescriptionAttribute(): ?string
    {
        if (isset($this->metadata['description'])) {
            return $this->metadata['description'];
        }

        $resourceName = '';
        if ($this->resource_type) {
            $resourceName = class_basename($this->resource_type);
            if ($this->metadata && isset($this->metadata['name'])) {
                $resourceName .= ': ' . $this->metadata['name'];
            } elseif ($this->metadata && isset($this->metadata['title'])) {
                $resourceName .= ': ' . $this->metadata['title'];
            }
        }

        return $resourceName ?: null;
    }
}
