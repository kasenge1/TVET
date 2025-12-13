<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\Unit;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RegenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugs:regenerate {--questions : Regenerate question slugs} {--units : Regenerate unit slugs} {--all : Regenerate all slugs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate slugs for questions and/or units';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $regenerateQuestions = $this->option('questions') || $this->option('all');
        $regenerateUnits = $this->option('units') || $this->option('all');

        if (!$regenerateQuestions && !$regenerateUnits) {
            $this->error('Please specify --questions, --units, or --all');
            return 1;
        }

        if ($regenerateUnits) {
            $this->regenerateUnitSlugs();
        }

        if ($regenerateQuestions) {
            $this->regenerateQuestionSlugs();
        }

        $this->info('Slug regeneration complete!');
        return 0;
    }

    /**
     * Regenerate unit slugs.
     */
    protected function regenerateUnitSlugs(): void
    {
        $this->info('Regenerating unit slugs...');
        $units = Unit::all();
        $bar = $this->output->createProgressBar($units->count());

        foreach ($units as $unit) {
            $baseSlug = Str::slug($unit->title);
            $slug = $baseSlug;
            $counter = 1;

            while (Unit::where('slug', $slug)->where('id', '!=', $unit->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $unit->slug = $slug;
            $unit->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Regenerated {$units->count()} unit slugs.");
    }

    /**
     * Regenerate question slugs.
     */
    protected function regenerateQuestionSlugs(): void
    {
        $this->info('Regenerating question slugs...');
        $questions = Question::orderBy('unit_id')->orderBy('order')->get();
        $bar = $this->output->createProgressBar($questions->count());

        // Track counters per unit for questions without question_number
        $unitCounters = [];

        foreach ($questions as $question) {
            // Use question_number if available
            if (!empty($question->question_number)) {
                $slug = 'q' . $question->question_number;
            } else {
                // Fallback: use incremental counter per unit
                $unitId = $question->unit_id;
                if (!isset($unitCounters[$unitId])) {
                    $unitCounters[$unitId] = 1;
                }
                $slug = 'q' . $unitCounters[$unitId];
                $unitCounters[$unitId]++;
            }

            $question->slug = $slug;
            $question->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Regenerated {$questions->count()} question slugs.");
    }
}
