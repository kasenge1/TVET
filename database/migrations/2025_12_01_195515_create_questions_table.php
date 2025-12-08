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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->string('question_number'); // "1", "1a", "1b", "2", "2a", etc.
            $table->foreignId('parent_question_id')->nullable()->constrained('questions')->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_image_url')->nullable(); // Single primary image
            $table->json('question_images')->nullable(); // Additional images (array)
            $table->text('answer_text')->nullable();
            $table->string('answer_image_url')->nullable(); // Single primary image
            $table->json('answer_images')->nullable(); // Additional images (array)
            $table->boolean('ai_generated')->default(false);
            $table->enum('answer_source', ['manual', 'ai', 'pasted'])->default('manual');
            $table->integer('order')->default(0); // For sorting
            $table->integer('view_count')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index('unit_id');
            $table->index('parent_question_id');
            $table->index('order');
            $table->index('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
