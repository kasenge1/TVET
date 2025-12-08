<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Course extends Model
{
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug when creating
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        // Update slug when title changes
        static::updating(function ($course) {
            if ($course->isDirty('title') && !$course->isDirty('slug')) {
                $course->slug = Str::slug($course->title);
            }
        });

        // Delete associated files and related records when course is deleted
        static::deleting(function ($course) {
            // Delete thumbnail
            if ($course->thumbnail_url) {
                Storage::disk('public')->delete($course->thumbnail_url);
            }

            // Delete all units (this will trigger their deleting events which will delete questions)
            foreach ($course->units as $unit) {
                $unit->delete();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'code',
        'description',
        'thumbnail_url',
        'level',
        'level_id',
        'is_published',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the admin who created this course.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the level relationship for this course.
     * Note: Use levelRelation() to avoid conflict with the 'level' attribute (enum string)
     */
    public function levelRelation(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * Get the display name for the level (handles both string enum and relationship)
     */
    public function getLevelDisplayAttribute(): string
    {
        // First try to get from the Level relationship
        if ($this->level_id && $this->levelRelation) {
            return $this->levelRelation->name;
        }

        // Fallback to the level enum string with formatted display
        if ($this->attributes['level'] ?? null) {
            return ucwords(str_replace('_', ' ', $this->attributes['level']));
        }

        return '';
    }

    /**
     * Get the units for this course.
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class)->orderBy('order');
    }

    /**
     * Get the enrollments for this course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get students enrolled in this course.
     */
    public function students()
    {
        return $this->hasManyThrough(User::class, Enrollment::class, 'course_id', 'id', 'id', 'user_id');
    }

    /**
     * Scope a query to only include published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get the total number of questions in this course.
     */
    public function getTotalQuestionsAttribute()
    {
        return $this->units()->withCount('questions')->get()->sum('questions_count');
    }

    /**
     * Get the total number of students enrolled.
     */
    public function getStudentCountAttribute()
    {
        return $this->enrollments()->count();
    }
}
