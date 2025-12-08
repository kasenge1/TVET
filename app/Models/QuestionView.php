<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionView extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'question_id',
    ];

    /**
     * Get the user that viewed the question.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question that was viewed.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Record a view for a user and question.
     * Updates timestamp if already viewed (for tracking last viewed).
     */
    public static function recordView(int $userId, int $questionId): bool
    {
        try {
            $view = self::where('user_id', $userId)
                ->where('question_id', $questionId)
                ->first();

            if ($view) {
                // Update timestamp to track last viewed
                $view->touch();
                return false; // Not a new view
            }

            self::create([
                'user_id' => $userId,
                'question_id' => $questionId,
            ]);
            return true; // New view
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if a user has viewed a question.
     */
    public static function hasViewed(int $userId, int $questionId): bool
    {
        return self::where('user_id', $userId)
            ->where('question_id', $questionId)
            ->exists();
    }

    /**
     * Get the last viewed question for a user in a specific unit.
     */
    public static function getLastViewedInUnit(int $userId, int $unitId): ?Question
    {
        $view = self::where('user_id', $userId)
            ->whereHas('question', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId)
                      ->whereNull('parent_question_id'); // Main questions only
            })
            ->latest('updated_at')
            ->with('question')
            ->first();

        return $view?->question;
    }

    /**
     * Get the count of viewed questions for a user in a specific unit.
     */
    public static function getViewedCountInUnit(int $userId, int $unitId): int
    {
        return self::where('user_id', $userId)
            ->whereHas('question', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId)
                      ->whereNull('parent_question_id'); // Main questions only
            })
            ->count();
    }

    /**
     * Get the last viewed question for a user in their enrolled course.
     */
    public static function getLastViewedInCourse(int $userId, int $courseId): ?array
    {
        $view = self::where('user_id', $userId)
            ->whereHas('question.unit', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->whereHas('question', function ($query) {
                $query->whereNull('parent_question_id'); // Main questions only
            })
            ->latest('updated_at')
            ->with(['question.unit'])
            ->first();

        if (!$view) {
            return null;
        }

        return [
            'question' => $view->question,
            'unit' => $view->question->unit,
            'viewed_at' => $view->updated_at,
        ];
    }

    /**
     * Get progress stats for a user in their enrolled course.
     */
    public static function getCourseProgress(int $userId, int $courseId): array
    {
        $totalQuestions = Question::whereHas('unit', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->whereNull('parent_question_id')
            ->count();

        $viewedQuestions = self::where('user_id', $userId)
            ->whereHas('question.unit', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->whereHas('question', function ($query) {
                $query->whereNull('parent_question_id');
            })
            ->count();

        return [
            'total' => $totalQuestions,
            'viewed' => $viewedQuestions,
            'percentage' => $totalQuestions > 0 ? round(($viewedQuestions / $totalQuestions) * 100) : 0,
        ];
    }

    /**
     * Get the next unread question in a unit for a user.
     * If user has viewed some questions, returns the next one after the last viewed.
     * If user hasn't viewed any, returns the first question.
     */
    public static function getNextUnreadInUnit(int $userId, int $unitId): ?Question
    {
        // Get IDs of all viewed questions in this unit
        $viewedIds = self::where('user_id', $userId)
            ->whereHas('question', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId)
                      ->whereNull('parent_question_id');
            })
            ->pluck('question_id')
            ->toArray();

        // Get all questions in this unit ordered by order
        $allQuestions = Question::where('unit_id', $unitId)
            ->whereNull('parent_question_id')
            ->orderBy('order')
            ->get();

        if ($allQuestions->isEmpty()) {
            return null;
        }

        // If nothing viewed, return first question
        if (empty($viewedIds)) {
            return $allQuestions->first();
        }

        // Find the first unviewed question
        $nextUnread = $allQuestions->first(function ($question) use ($viewedIds) {
            return !in_array($question->id, $viewedIds);
        });

        // If all are viewed, get the last viewed one for "Continue"
        if (!$nextUnread) {
            return self::getLastViewedInUnit($userId, $unitId);
        }

        return $nextUnread;
    }

    /**
     * Get the next unread question in a course for a user.
     * Returns the next unread question across all units.
     */
    public static function getNextUnreadInCourse(int $userId, int $courseId): ?array
    {
        // Get all viewed question IDs in this course
        $viewedIds = self::where('user_id', $userId)
            ->whereHas('question.unit', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->whereHas('question', function ($query) {
                $query->whereNull('parent_question_id');
            })
            ->pluck('question_id')
            ->toArray();

        // Get all units in order
        $units = \App\Models\Unit::where('course_id', $courseId)
            ->orderBy('unit_number')
            ->get();

        foreach ($units as $unit) {
            // Get questions in this unit
            $questions = Question::where('unit_id', $unit->id)
                ->whereNull('parent_question_id')
                ->orderBy('order')
                ->get();

            // Find first unread in this unit
            $nextUnread = $questions->first(function ($question) use ($viewedIds) {
                return !in_array($question->id, $viewedIds);
            });

            if ($nextUnread) {
                return [
                    'question' => $nextUnread,
                    'unit' => $unit,
                ];
            }
        }

        // All questions viewed - return last viewed
        return self::getLastViewedInCourse($userId, $courseId);
    }
}
