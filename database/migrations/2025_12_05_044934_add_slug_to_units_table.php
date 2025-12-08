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
        Schema::table('units', function (Blueprint $table) {
            $table->string('slug')->after('title');
            $table->unique(['course_id', 'slug']);
        });

        // Generate slugs for existing units
        $units = \App\Models\Unit::all();
        foreach ($units as $unit) {
            $unit->slug = \Illuminate\Support\Str::slug($unit->title);
            $unit->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropUnique(['course_id', 'slug']);
            $table->dropColumn('slug');
        });
    }
};
