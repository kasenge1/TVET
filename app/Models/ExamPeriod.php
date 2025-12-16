<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExamPeriod extends Model
{
    /**
     * Months array for display.
     */
    public const MONTHS = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'month',
        'year',
        'slug',
        'description',
        'is_active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($examPeriod) {
            // Auto-generate name if not provided (must be done FIRST)
            if (empty($examPeriod->name) && $examPeriod->month && $examPeriod->year) {
                $examPeriod->name = self::MONTHS[$examPeriod->month] . ' ' . $examPeriod->year;
            }

            // Generate slug from name (after name is set)
            if (empty($examPeriod->slug) && !empty($examPeriod->name)) {
                $examPeriod->slug = Str::slug($examPeriod->name);
            }

            // Auto-set order if not provided
            if (empty($examPeriod->order)) {
                $examPeriod->order = static::max('order') + 1;
            }
        });

        static::updating(function ($examPeriod) {
            // Re-generate name if month/year changed and no custom name
            if (($examPeriod->isDirty('month') || $examPeriod->isDirty('year'))) {
                $examPeriod->name = self::MONTHS[$examPeriod->month] . ' ' . $examPeriod->year;
            }

            // Update slug when name changes
            if ($examPeriod->isDirty('name')) {
                $examPeriod->slug = Str::slug($examPeriod->name);
            }
        });
    }

    /**
     * Get the questions for this exam period.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Scope a query to only include active exam periods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by year and month (newest first).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('year', 'desc')->orderBy('month', 'desc');
    }

    /**
     * Scope a query to order by custom order field.
     */
    public function scopeByOrder($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get the formatted period (e.g., "July 2025").
     */
    public function getFormattedPeriodAttribute(): string
    {
        return (self::MONTHS[$this->month] ?? '') . ' ' . $this->year;
    }

    /**
     * Get the month name.
     */
    public function getMonthNameAttribute(): string
    {
        return self::MONTHS[$this->month] ?? '';
    }

    /**
     * Get the period key for grouping (e.g., "2025-07").
     */
    public function getPeriodKeyAttribute(): string
    {
        return $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT);
    }
}
