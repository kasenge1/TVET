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
        Schema::create('exam_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "July 2025", "November 2025 Series 1"
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year'); // e.g., 2025
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // Index for efficient querying
            $table->index(['year', 'month']);
            $table->index('is_active');
        });

        // Add exam_period_id to questions table
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('exam_period_id')->nullable()->after('unit_id')
                  ->constrained('exam_periods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['exam_period_id']);
            $table->dropColumn('exam_period_id');
        });

        Schema::dropIfExists('exam_periods');
    }
};
