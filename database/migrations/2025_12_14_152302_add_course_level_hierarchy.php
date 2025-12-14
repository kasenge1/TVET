<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration restructures the hierarchy:
     * Course -> Level -> Unit -> Questions
     *
     * Levels now belong to a specific course (e.g., "Food and Beverage" has "Level 3", "Level 4")
     * Units now belong to a level (and indirectly to a course)
     */
    public function up(): void
    {
        // Step 1: Add course_id to levels table
        Schema::table('levels', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('level_number')->nullable()->after('name'); // e.g., "3", "4", "5"
        });

        // Step 2: Add level_id to units table
        Schema::table('units', function (Blueprint $table) {
            $table->foreignId('level_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
        });

        // Step 3: Create default levels for each existing course
        $courses = DB::table('courses')->get();
        foreach ($courses as $course) {
            // Create Level 3, 4, 5 for each course
            foreach ([3, 4, 5] as $levelNum) {
                $levelId = DB::table('levels')->insertGetId([
                    'course_id' => $course->id,
                    'name' => "Level {$levelNum}",
                    'level_number' => (string) $levelNum,
                    'slug' => $course->slug . '-level-' . $levelNum,
                    'description' => "Level {$levelNum} for {$course->title}",
                    'order' => $levelNum,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Assign all existing units of this course to Level 3 by default
                if ($levelNum === 3) {
                    DB::table('units')
                        ->where('course_id', $course->id)
                        ->whereNull('level_id')
                        ->update(['level_id' => $levelId]);
                }
            }
        }

        // Step 4: Remove old generic levels (Certificate, Diploma, Higher Diploma) that don't have course_id
        DB::table('levels')->whereNull('course_id')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove level_id from units
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });

        // Remove course_id and level_number from levels
        Schema::table('levels', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'level_number']);
        });
    }
};
