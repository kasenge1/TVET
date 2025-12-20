<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Question;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fixes database integrity issues from deleted sub-questions and duplicate slugs.
     */
    public function up(): void
    {
        // 1. Delete orphaned sub-questions (parent_question_id points to non-existent parent)
        $orphaned = DB::table('questions')
            ->whereNotNull('parent_question_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('questions as parent')
                    ->whereRaw('parent.id = questions.parent_question_id');
            })
            ->get();

        if ($orphaned->count() > 0) {
            echo "Found {$orphaned->count()} orphaned sub-questions. Deleting..." . PHP_EOL;

            foreach ($orphaned as $orphan) {
                // Delete images if they exist
                if ($orphan->question_images) {
                    $images = json_decode($orphan->question_images, true);
                    if (is_array($images)) {
                        foreach ($images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
                if ($orphan->answer_images) {
                    $images = json_decode($orphan->answer_images, true);
                    if (is_array($images)) {
                        foreach ($images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
            }

            DB::table('questions')
                ->whereNotNull('parent_question_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('questions as parent')
                        ->whereRaw('parent.id = questions.parent_question_id');
                })
                ->delete();
        }

        // 2. Fix has_sub_questions flag for questions where sub-questions were deleted
        $mismatched = DB::table('questions')
            ->where('has_sub_questions', true)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('questions as sub')
                    ->whereRaw('sub.parent_question_id = questions.id');
            })
            ->get();

        if ($mismatched->count() > 0) {
            echo "Found {$mismatched->count()} questions with has_sub_questions=true but no subs. Fixing..." . PHP_EOL;

            DB::table('questions')
                ->where('has_sub_questions', true)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('questions as sub')
                        ->whereRaw('sub.parent_question_id = questions.id');
                })
                ->update(['has_sub_questions' => false]);
        }

        // 3. Handle duplicate slugs by regenerating them
        $duplicateSlugs = DB::table('questions')
            ->select('slug', DB::raw('COUNT(*) as count'))
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('slug');

        if ($duplicateSlugs->count() > 0) {
            echo "Found {$duplicateSlugs->count()} duplicate slugs. Regenerating..." . PHP_EOL;

            foreach ($duplicateSlugs as $slug) {
                $questions = Question::where('slug', $slug)->orderBy('id')->get();

                // Keep the first one, regenerate others
                $first = true;
                foreach ($questions as $question) {
                    if ($first) {
                        $first = false;
                        continue; // Keep the original slug for the first question
                    }

                    // Generate new unique slug
                    $newSlug = $slug . '-' . $question->id;

                    // Make sure it's unique
                    $counter = 1;
                    while (Question::where('slug', $newSlug)->where('id', '!=', $question->id)->exists()) {
                        $newSlug = $slug . '-' . $question->id . '-' . $counter;
                        $counter++;
                    }

                    Question::where('id', $question->id)->update(['slug' => $newSlug]);
                    echo "  Updated question {$question->id}: {$slug} -> {$newSlug}" . PHP_EOL;
                }
            }
        }

        echo "Database cleanup completed successfully!" . PHP_EOL;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse cleanup operations
    }
};
