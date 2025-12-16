<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds period_question_number for exam-period-specific numbering (1, 2, 3 within each period).
     * The existing question_number becomes the global sequential number (permanent).
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Period-specific question number (e.g., 1, 2, 3 within each exam period)
            // This number is recalculated when questions are transferred between periods
            $table->unsignedInteger('period_question_number')->nullable()->after('question_number');

            // Add index for efficient querying by exam period + period number
            $table->index(['exam_period_id', 'period_question_number']);
        });

        // Populate period_question_number for existing questions
        // Group by unit_id and exam_period_id, then number sequentially
        $this->populateExistingPeriodNumbers();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['exam_period_id', 'period_question_number']);
            $table->dropColumn('period_question_number');
        });
    }

    /**
     * Populate period_question_number for existing questions.
     */
    protected function populateExistingPeriodNumbers(): void
    {
        // Get all unique combinations of unit_id and exam_period_id
        $combinations = \DB::table('questions')
            ->select('unit_id', 'exam_period_id')
            ->whereNull('parent_question_id')
            ->whereNotNull('exam_period_id')
            ->groupBy('unit_id', 'exam_period_id')
            ->get();

        foreach ($combinations as $combo) {
            // Get questions for this combination ordered by their order field
            $questions = \DB::table('questions')
                ->where('unit_id', $combo->unit_id)
                ->where('exam_period_id', $combo->exam_period_id)
                ->whereNull('parent_question_id')
                ->orderBy('order')
                ->get();

            $periodNum = 1;
            foreach ($questions as $question) {
                \DB::table('questions')
                    ->where('id', $question->id)
                    ->update(['period_question_number' => $periodNum]);
                $periodNum++;
            }
        }
    }
};
