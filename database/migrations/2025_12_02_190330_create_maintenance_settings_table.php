<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('We\'ll Be Right Back!');
            $table->string('subtitle')->default('System Under Maintenance');
            $table->text('message')->default('We\'re currently performing scheduled maintenance to enhance your learning experience. Our team is working diligently to bring the platform back online as soon as possible.');
            $table->string('expected_duration')->default('1-2 Hours');
            $table->string('support_email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('maintenance_settings')->insert([
            'title' => 'We\'ll Be Right Back!',
            'subtitle' => 'System Under Maintenance',
            'message' => 'We\'re currently performing scheduled maintenance to enhance your learning experience. Our team is working diligently to bring the platform back online as soon as possible.',
            'expected_duration' => '1-2 Hours',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_settings');
    }
};
