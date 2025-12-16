<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Question extends Model
{
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug when creating
        static::creating(function ($question) {
            if (empty($question->slug)) {
                $question->slug = static::generateSlug($question);
            }
        });

        // Update slug when question_text changes
        static::updating(function ($question) {
            if ($question->isDirty('question_text') && !$question->isDirty('slug')) {
                $question->slug = static::generateSlug($question);
            }
        });

        // Delete associated images when question is deleted
        static::deleting(function ($question) {
            // Delete multiple question images
            if ($question->question_images) {
                foreach ($question->question_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            // Delete multiple answer images
            if ($question->answer_images) {
                foreach ($question->answer_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        });
    }

    /**
     * Generate a unique slug for the question.
     * Format: {exam_period_slug}-q{period_number} (e.g., "july-2024-q1")
     * Uses period_question_number for exam-period-specific numbering.
     */
    protected static function generateSlug($question): string
    {
        $prefix = '';

        // Include exam period in slug if available
        if ($question->exam_period_id) {
            $examPeriod = \App\Models\ExamPeriod::find($question->exam_period_id);
            if ($examPeriod) {
                $prefix = $examPeriod->slug . '-';
            }
        }

        // Use period_question_number for slug (exam-period-specific numbering)
        if (!empty($question->period_question_number)) {
            return $prefix . 'q' . $question->period_question_number;
        }

        // Fallback: find next available period number in this unit + exam period
        $query = static::where('unit_id', $question->unit_id)
            ->whereNull('parent_question_id')
            ->where('id', '!=', $question->id ?? 0);

        if ($question->exam_period_id) {
            $query->where('exam_period_id', $question->exam_period_id);
        }

        $count = $query->count();

        return $prefix . 'q' . ($count + 1);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_id',
        'exam_period_id',
        'exam_month',
        'exam_year',
        'question_type',
        'video_url',
        'question_number',
        'period_question_number',
        'slug',
        'parent_question_id',
        'question_text',
        'question_images',
        'answer_text',
        'answer_images',
        'ai_generated',
        'answer_source',
        'order',
        'view_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'question_images' => 'array',
        'answer_images' => 'array',
        'ai_generated' => 'boolean',
        'order' => 'integer',
        'view_count' => 'integer',
        'exam_month' => 'integer',
        'exam_year' => 'integer',
        'period_question_number' => 'integer',
    ];

    /**
     * Get the exam period as a formatted string (e.g., "July 2024").
     * Uses the ExamPeriod relationship if available, falls back to legacy fields.
     */
    public function getExamPeriodLabelAttribute(): ?string
    {
        // First try to use the ExamPeriod relationship
        if ($this->exam_period_id && $this->relationLoaded('examPeriod') && $this->examPeriod) {
            return $this->examPeriod->name;
        }

        // Fallback to legacy exam_month/exam_year fields
        if (!$this->exam_month || !$this->exam_year) {
            return null;
        }

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return ($months[$this->exam_month] ?? '') . ' ' . $this->exam_year;
    }

    /**
     * Get the exam period key for grouping (e.g., "2024-07").
     * Uses the ExamPeriod relationship if available, falls back to legacy fields.
     */
    public function getExamPeriodKeyAttribute(): ?string
    {
        // First try to use the ExamPeriod relationship
        if ($this->exam_period_id && $this->relationLoaded('examPeriod') && $this->examPeriod) {
            return $this->examPeriod->period_key;
        }

        // Fallback to legacy exam_month/exam_year fields
        if (!$this->exam_month || !$this->exam_year) {
            return null;
        }

        return $this->exam_year . '-' . str_pad($this->exam_month, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Scope a query to filter by exam period ID.
     */
    public function scopeForExamPeriod($query, $examPeriodId)
    {
        return $query->where('exam_period_id', $examPeriodId);
    }

    /**
     * Scope a query to filter by exam period (legacy).
     */
    public function scopeExamPeriod($query, $month, $year)
    {
        return $query->where('exam_month', $month)->where('exam_year', $year);
    }

    /**
     * Scope a query to filter by exam year (legacy).
     */
    public function scopeExamYear($query, $year)
    {
        return $query->where('exam_year', $year);
    }

    /**
     * Get the unit that owns this question.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the exam period for this question.
     */
    public function examPeriod(): BelongsTo
    {
        return $this->belongsTo(ExamPeriod::class);
    }

    /**
     * Get the parent question (for sub-questions).
     */
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'parent_question_id');
    }

    /**
     * Get the sub-questions for this question.
     */
    public function subQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'parent_question_id')->orderBy('order');
    }

    /**
     * Get the bookmarks for this question.
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Check if this is a main question (not a sub-question).
     */
    public function isMainQuestion(): bool
    {
        return is_null($this->parent_question_id);
    }

    /**
     * Check if this is a sub-question.
     */
    public function isSubQuestion(): bool
    {
        return !is_null($this->parent_question_id);
    }

    /**
     * Increment the view count for this question (legacy method - counts every view).
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Record a view for a specific user (only counts once per user).
     */
    public function recordUserView(int $userId): bool
    {
        // Check if user has already viewed this question
        $alreadyViewed = QuestionView::where('user_id', $userId)
            ->where('question_id', $this->id)
            ->exists();

        if (!$alreadyViewed) {
            // Record the view
            QuestionView::create([
                'user_id' => $userId,
                'question_id' => $this->id,
            ]);

            // Increment the view count
            $this->increment('view_count');
            return true;
        }

        return false;
    }

    /**
     * Get the unique views relationship.
     */
    public function views(): HasMany
    {
        return $this->hasMany(QuestionView::class);
    }

    /**
     * Get unique view count.
     */
    public function getUniqueViewCountAttribute(): int
    {
        return $this->views()->count();
    }

    /**
     * Scope a query to only include main questions.
     */
    public function scopeMainQuestions($query)
    {
        return $query->whereNull('parent_question_id');
    }

    /**
     * Scope a query to only include sub-questions.
     */
    public function scopeSubQuestions($query)
    {
        return $query->whereNotNull('parent_question_id');
    }

    /**
     * Scope a query to only include AI-generated questions.
     */
    public function scopeAiGenerated($query)
    {
        return $query->where('ai_generated', true);
    }

    /**
     * Check if this is a video question.
     */
    public function isVideoQuestion(): bool
    {
        return $this->question_type === 'video';
    }

    /**
     * Check if this is a text question.
     */
    public function isTextQuestion(): bool
    {
        return $this->question_type === 'text' || $this->question_type === null;
    }

    /**
     * Get the YouTube video ID from the URL.
     */
    public function getYoutubeVideoIdAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->video_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Get the YouTube embed URL with privacy-enhanced settings.
     * - Uses youtube-nocookie.com for enhanced privacy
     * - Disables related videos (rel=0)
     * - Hides YouTube branding (modestbranding=1)
     * - Disables keyboard controls to prevent navigation (disablekb=1)
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $videoId = $this->youtube_video_id;
        if (!$videoId) {
            return null;
        }

        // Use youtube-nocookie.com for privacy-enhanced mode
        // rel=0 prevents showing related videos at the end
        // modestbranding=1 reduces YouTube branding
        return "https://www.youtube-nocookie.com/embed/{$videoId}?rel=0&modestbranding=1&showinfo=0";
    }

    /**
     * Scope a query to only include video questions.
     */
    public function scopeVideoQuestions($query)
    {
        return $query->where('question_type', 'video');
    }

    /**
     * Scope a query to only include text questions.
     */
    public function scopeTextQuestions($query)
    {
        return $query->where('question_type', 'text');
    }
}
