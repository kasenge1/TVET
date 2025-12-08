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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('action'); // 'viewed_question', 'searched', 'enrolled_course', etc.
            $table->string('resource_type')->nullable(); // 'question', 'course', 'unit'
            $table->unsignedBigInteger('resource_id')->nullable(); // ID of the resource
            $table->json('metadata')->nullable(); // Additional context (search query, etc.)
            $table->timestamp('created_at')->useCurrent();

            // Indexes for performance
            $table->index('user_id');
            $table->index('action');
            $table->index(['resource_type', 'resource_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
