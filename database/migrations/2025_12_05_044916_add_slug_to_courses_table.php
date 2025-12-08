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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
        });

        // Generate slugs for existing courses
        $courses = \App\Models\Course::all();
        foreach ($courses as $course) {
            $course->slug = \Illuminate\Support\Str::slug($course->title . '-' . ($course->level_display ?? 'course'));
            $course->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
