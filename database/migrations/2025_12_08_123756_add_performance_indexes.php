<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds performance indexes for commonly queried columns.
     */
    public function up(): void
    {
        // Add index to enrollments for user queries
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('user_id', 'enrollments_user_id_index');
        });

        // Add indexes to subscriptions for status and user queries
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasIndex('subscriptions', 'subscriptions_user_status_index')) {
                $table->index(['user_id', 'status'], 'subscriptions_user_status_index');
            }
            if (!Schema::hasIndex('subscriptions', 'subscriptions_status_index')) {
                $table->index('status', 'subscriptions_status_index');
            }
        });

        // Add indexes to question_views for analytics queries
        Schema::table('question_views', function (Blueprint $table) {
            $table->index('user_id', 'question_views_user_id_index');
            $table->index('question_id', 'question_views_question_id_index');
            $table->index('viewed_at', 'question_views_viewed_at_index');
        });

        // Add index to bookmarks for user queries
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->index('user_id', 'bookmarks_user_id_index');
        });

        // Add index to site_settings for key lookups
        Schema::table('site_settings', function (Blueprint $table) {
            $table->index('key', 'site_settings_key_index');
        });

        // Add index to notifications for user unread queries
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'read_at'], 'notifications_user_read_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_user_id_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_user_status_index');
            $table->dropIndex('subscriptions_status_index');
        });

        Schema::table('question_views', function (Blueprint $table) {
            $table->dropIndex('question_views_user_id_index');
            $table->dropIndex('question_views_question_id_index');
            $table->dropIndex('question_views_viewed_at_index');
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex('bookmarks_user_id_index');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropIndex('site_settings_key_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_index');
        });
    }
};
