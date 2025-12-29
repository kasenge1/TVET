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

            // Delete all levels (cascade will delete units and questions)
            foreach ($course->levels as $level) {
                $level->delete();
            }

            // Delete any units directly attached to course (legacy)
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
     * Get all levels for this course.
     */
    public function levels(): HasMany
    {
        return $this->hasMany(Level::class)->orderBy('order');
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

    /**
     * Get a display string for the course levels.
     * Shows the count of levels (e.g., "3 Levels") or returns null if no levels.
     */
    public function getLevelDisplayAttribute(): ?string
    {
        $count = $this->levels()->count();
        if ($count === 0) {
            return null;
        }

        return $count . ' ' . ($count === 1 ? 'Level' : 'Levels');
    }
}
