<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;
use App\Models\Unit;
use App\Models\User;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('No admin user found. Skipping notes seeding.');
            return;
        }

        // Get units to attach notes to
        $units = Unit::take(3)->get();

        if ($units->isEmpty()) {
            $this->command->warn('No units found. Skipping notes seeding.');
            return;
        }

        $notesData = [
            [
                'title' => 'Key Concepts Overview',
                'content' => '<h3>Important Concepts to Remember</h3>
<p>This unit covers several fundamental concepts that form the foundation of the subject. Make sure you understand each one before moving forward.</p>
<ul>
<li><strong>Concept 1:</strong> The basic principles and their applications</li>
<li><strong>Concept 2:</strong> How these principles interact with each other</li>
<li><strong>Concept 3:</strong> Practical applications in real-world scenarios</li>
</ul>
<p>Take your time to review each concept thoroughly. These will appear in your exams!</p>',
                'order' => 1,
                'is_published' => true,
            ],
            [
                'title' => 'Important Formulas & Equations',
                'content' => '<h3>Essential Formulas</h3>
<p>Memorize these formulas as they are frequently tested:</p>
<blockquote>
<p><strong>Formula 1:</strong> V = I × R (Ohm\'s Law)</p>
<p><strong>Formula 2:</strong> P = V × I (Power equation)</p>
<p><strong>Formula 3:</strong> E = P × t (Energy calculation)</p>
</blockquote>
<h4>Tips for Using Formulas:</h4>
<ol>
<li>Always identify what you\'re solving for first</li>
<li>List all known values</li>
<li>Choose the appropriate formula</li>
<li>Substitute and solve</li>
</ol>
<p>Practice using these formulas with the questions in this unit!</p>',
                'order' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'Safety Guidelines & Best Practices',
                'content' => '<h3>Safety First!</h3>
<p>Always follow these safety guidelines when working with electrical systems:</p>
<h4>Before Starting Work:</h4>
<ul>
<li>✅ Always turn off power at the main switch</li>
<li>✅ Use a voltage tester to confirm power is off</li>
<li>✅ Wear appropriate PPE (Personal Protective Equipment)</li>
<li>✅ Inform others that you are working on the system</li>
</ul>
<h4>During Work:</h4>
<ul>
<li>✅ Use insulated tools only</li>
<li>✅ Never work alone on live circuits</li>
<li>✅ Keep your work area clean and organized</li>
<li>✅ Follow the lockout/tagout procedure</li>
</ul>
<h4>After Work:</h4>
<ul>
<li>✅ Double-check all connections</li>
<li>✅ Remove all tools and materials</li>
<li>✅ Test the system before energizing</li>
<li>✅ Document your work</li>
</ul>
<p><strong>Remember:</strong> Safety is not optional - it\'s a requirement!</p>',
                'order' => 3,
                'is_published' => true,
            ],
            [
                'title' => 'Exam Tips & Common Mistakes',
                'content' => '<h3>How to Ace Your Exam</h3>
<p>Based on previous exam papers, here are the most commonly tested areas:</p>
<h4>High-Frequency Topics:</h4>
<ol>
<li><strong>Topic A:</strong> Definitions and explanations (usually 20% of marks)</li>
<li><strong>Topic B:</strong> Calculations and problem-solving (usually 30% of marks)</li>
<li><strong>Topic C:</strong> Diagrams and labeling (usually 15% of marks)</li>
<li><strong>Topic D:</strong> Applications and case studies (usually 35% of marks)</li>
</ol>
<h4>Common Mistakes to Avoid:</h4>
<ul>
<li>❌ Not reading the question carefully</li>
<li>❌ Forgetting to include units in calculations</li>
<li<li>❌ Poor time management</li>
<li>❌ Not showing your working steps</li>
</ul>
<h4>Time Management Tips:</h4>
<p>Allocate your time based on marks:</p>
<ul>
<li>1 mark = approximately 1.5 minutes</li>
<li>Always attempt all questions</li>
<li>Leave time for review at the end</li>
</ul>
<p>Good luck with your studies! 📚</p>',
                'order' => 4,
                'is_published' => true,
            ],
        ];

        foreach ($units as $unit) {
            foreach ($notesData as $noteData) {
                Note::create(array_merge($noteData, [
                    'unit_id' => $unit->id,
                    'created_by' => $admin->id,
                ]));
            }
            $this->command->info("Created 4 notes for Unit {$unit->unit_number}: {$unit->title}");
        }

        $this->command->info('Notes seeding completed successfully!');
    }
}
