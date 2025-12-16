<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This moves exam period from units to questions.
     * Questions will now have exam_month and exam_year to group them
     * by when the exam was taken (e.g., July 2024, December 2024).
     */
    public function up(): void
    {
        // Add exam period to questions table
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedTinyInteger('exam_month')->nullable()->after('unit_id');
            $table->unsignedSmallInteger('exam_year')->nullable()->after('exam_month');

            // Index for efficient grouping/filtering by exam period
            $table->index(['exam_month', 'exam_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['exam_month', 'exam_year']);
            $table->dropColumn(['exam_month', 'exam_year']);
        });
    }
};
