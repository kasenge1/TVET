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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->enum('role', ['admin', 'student'])->default('student')->after('password');
            $table->enum('subscription_tier', ['free', 'premium'])->default('free')->after('role');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_tier');
            $table->string('profile_photo_url')->nullable()->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id',
                'role',
                'subscription_tier',
                'subscription_expires_at',
                'profile_photo_url'
            ]);
        });
    }
};
