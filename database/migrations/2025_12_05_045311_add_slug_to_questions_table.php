<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('slug')->after('question_number');
            $table->unique(['unit_id', 'slug']);
        });

        // Generate slugs for existing questions
        $questions = \App\Models\Question::all();
        foreach ($questions as $question) {
            $baseSlug = \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit(strip_tags($question->question_text), 50, ''));
            $slug = $question->question_number
                ? $question->question_number . '-' . $baseSlug
                : $baseSlug;
            $question->slug = $slug ?: 'question-' . $question->id;
            $question->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropUnique(['unit_id', 'slug']);
            $table->dropColumn('slug');
        });
    }
};
