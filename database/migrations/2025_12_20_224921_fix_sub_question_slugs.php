<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Question;
use App\Models\ExamPeriod;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix slugs for all existing sub-questions
        $subQuestions = Question::whereNotNull('parent_question_id')->get();

        foreach ($subQuestions as $sub) {
            // Get parent
            $parent = $sub->parentQuestion;
            if (!$parent) continue;

            // Get exam period prefix
            $prefix = '';
            if ($sub->exam_period_id) {
                $examPeriod = ExamPeriod::find($sub->exam_period_id);
                if ($examPeriod && !empty($examPeriod->slug)) {
                    $prefix = $examPeriod->slug . '-';
                }
            }

            // Generate new slug
            $baseSlug = $prefix . 'q' . $parent->period_question_number;

            // Find position among siblings (ordered by id)
            $position = Question::where('parent_question_id', $parent->id)
                ->where('id', '<=', $sub->id)
                ->orderBy('id')
                ->pluck('id')
                ->search($sub->id) + 1;

            $letter = chr(96 + $position); // 97 = 'a', 98 = 'b', etc.
            $newSlug = $baseSlug . $letter;

            // Update slug directly without triggering model events
            Question::where('id', $sub->id)->update(['slug' => $newSlug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed - slugs are generated automatically
    }
};
