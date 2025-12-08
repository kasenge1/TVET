<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Unit extends Model
{
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug when creating
        static::creating(function ($unit) {
            if (empty($unit->slug)) {
                $unit->slug = Str::slug($unit->title);
            }
        });

        // Update slug when title changes
        static::updating(function ($unit) {
            if ($unit->isDirty('title') && !$unit->isDirty('slug')) {
                $unit->slug = Str::slug($unit->title);
            }
        });

        // Delete all questions when unit is deleted (to trigger their deleting events for image cleanup)
        static::deleting(function ($unit) {
            foreach ($unit->questions as $question) {
                $question->delete();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'unit_number',
        'title',
        'slug',
        'description',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_number' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the course that owns this unit.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the questions for this unit.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get only main questions (no sub-questions).
     */
    public function mainQuestions()
    {
        return $this->hasMany(Question::class)->whereNull('parent_question_id')->orderBy('order');
    }

    /**
     * Get the total number of questions in this unit.
     */
    public function getQuestionCountAttribute()
    {
        return $this->questions()->count();
    }
}
