<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds has_sub_questions flag to indicate if a question can have sub-questions.
     * Only questions with this flag will appear in parent question dropdown.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Flag to indicate this question will have sub-questions
            // Only these questions appear in the parent question dropdown
            $table->boolean('has_sub_questions')->default(false)->after('parent_question_id');
        });

        // Auto-set has_sub_questions=true for questions that already have sub-questions
        $this->updateExistingQuestionsWithSubQuestions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('has_sub_questions');
        });
    }

    /**
     * Set has_sub_questions=true for questions that already have sub-questions.
     */
    protected function updateExistingQuestionsWithSubQuestions(): void
    {
        // Find all questions that have children (sub-questions)
        $parentIds = \DB::table('questions')
            ->whereNotNull('parent_question_id')
            ->distinct()
            ->pluck('parent_question_id');

        if ($parentIds->isNotEmpty()) {
            \DB::table('questions')
                ->whereIn('id', $parentIds)
                ->update(['has_sub_questions' => true]);
        }
    }
};
