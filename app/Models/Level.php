<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'level_number',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the course that owns this level
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get all units for this level
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class)->orderBy('order');
    }

    /**
     * Scope to get only active levels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get total questions count across all units in this level
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->units()->withCount('questions')->get()->sum('questions_count');
    }
}
