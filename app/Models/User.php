<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'subscription_tier',
        'subscription_expires_at',
        'profile_photo_url',
        'is_blocked',
        'blocked_reason',
        'blocked_at',
        'blocked_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_expires_at' => 'datetime',
            'is_blocked' => 'boolean',
            'blocked_at' => 'datetime',
        ];
    }

    /**
     * Get the enrollment for this user.
     */
    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    /**
     * Get the course the user is enrolled in.
     */
    public function course()
    {
        return $this->hasOneThrough(Course::class, Enrollment::class, 'user_id', 'id', 'id', 'course_id');
    }

    /**
     * Get the bookmarks for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Bookmark>
     */
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Get the subscriptions for this user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the activity logs for this user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the active subscription for this user.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->where('expires_at', '>', now());
    }

    /**
     * Check if user has admin access (super-admin, admin, content-manager, or question-editor).
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super-admin', 'admin', 'content-manager', 'question-editor'])
            || $this->role === 'admin'; // Backward compatibility
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student') || $this->role === 'student';
    }

    /**
     * Check if user can manage questions (has question-related permissions).
     */
    public function canManageQuestions(): bool
    {
        return $this->can('create questions') || $this->can('edit questions');
    }

    /**
     * Check if user has premium subscription.
     */
    public function isPremium(): bool
    {
        // Check user's own subscription fields first
        if ($this->subscription_tier === 'premium'
            && $this->subscription_expires_at
            && $this->subscription_expires_at > now()) {
            return true;
        }

        // Also check for active subscription in subscriptions table
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Scope a query to only include admins (using Spatie roles).
     */
    public function scopeAdmin($query)
    {
        return $query->role(['super-admin', 'admin', 'content-manager', 'question-editor']);
    }

    /**
     * Scope a query to only include students.
     */
    public function scopeStudent($query)
    {
        return $query->role('student');
    }

    /**
     * Scope a query for users with staff roles (non-students).
     */
    public function scopeStaff($query)
    {
        return $query->role(['super-admin', 'admin', 'content-manager', 'question-editor']);
    }

    /**
     * Scope a query to only include premium users.
     */
    public function scopePremium($query)
    {
        return $query->where(function ($q) {
            // Check user's own subscription fields
            $q->where(function ($inner) {
                $inner->where('subscription_tier', 'premium')
                      ->where('subscription_expires_at', '>', now());
            })
            // Or check for active subscription in subscriptions table
            ->orWhereHas('subscriptions', function ($sub) {
                $sub->where('status', 'active')
                    ->where('expires_at', '>', now());
            });
        });
    }

    /**
     * Scope a query to only include blocked users.
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    /**
     * Scope a query to only include active (non-blocked) users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }

    /**
     * Get the admin who blocked this user.
     */
    public function blockedByAdmin()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    /**
     * Check if user is blocked.
     */
    public function isBlocked(): bool
    {
        return $this->is_blocked === true;
    }

    /**
     * Block this user.
     */
    public function block(?string $reason = null, ?int $blockedBy = null): bool
    {
        return $this->update([
            'is_blocked' => true,
            'blocked_reason' => $reason,
            'blocked_at' => now(),
            'blocked_by' => $blockedBy,
        ]);
    }

    /**
     * Unblock this user.
     */
    public function unblock(): bool
    {
        return $this->update([
            'is_blocked' => false,
            'blocked_reason' => null,
            'blocked_at' => null,
            'blocked_by' => null,
        ]);
    }
}
