<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Unit;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $query = Question::with('unit.course');

        // Filter by unit
        if ($request->has('unit')) {
            $query->where('unit_id', $request->unit);
        }

        // Filter by parent question (main questions only)
        if ($request->has('main_only')) {
            $query->whereNull('parent_question_id');
        }

        $questions = $query->latest()->paginate(15);
        $units = Unit::with('course')->orderBy('unit_number')->get();

        return view('admin.questions.index', compact('questions', 'units'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Request $request)
    {
        $units = Unit::with('course')->orderBy('unit_number')->get();
        $unitId = $request->query('unit');
        $selectedUnit = $unitId ? Unit::find($unitId) : null;
        
        // Get parent questions for sub-questions
        $parentQuestions = Question::whereNull('parent_question_id')
            ->when($unitId, fn($q) => $q->where('unit_id', $unitId))
            ->orderBy('order')
            ->get();

        return view('admin.questions.create', compact('units', 'selectedUnit', 'parentQuestions'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        // Auto-generate question number if not provided or generate fresh
        $questionNumber = $this->generateQuestionNumber(
            $request->unit_id,
            $request->parent_question_id
        );
        $request->merge(['question_number' => $questionNumber]);

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'exam_period_id' => 'required|exists:exam_periods,id',
            'question_type' => 'required|in:text,video',
            'video_url' => 'required_if:question_type,video|nullable|url',
            'question_number' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    // Check uniqueness based on whether it's a sub-question or main question
                    if ($request->parent_question_id) {
                        // Sub-question: unique within the parent question
                        $exists = Question::where('parent_question_id', $request->parent_question_id)
                            ->where('question_number', $value)
                            ->exists();
                        if ($exists) {
                            $fail('Sub-question "' . $value . '" already exists for this parent question.');
                        }
                    } else {
                        // Main question: unique within the unit
                        $exists = Question::where('unit_id', $request->unit_id)
                            ->whereNull('parent_question_id')
                            ->where('question_number', $value)
                            ->exists();
                        if ($exists) {
                            $fail('Question number "' . $value . '" already exists in this unit.');
                        }
                    }
                },
            ],
            'parent_question_id' => 'nullable|exists:questions,id',
            'question_text' => 'required_if:question_type,text|nullable|string',
            'question_images.*' => 'nullable|image|max:2048',
            'answer_text' => 'required_if:question_type,text|nullable|string',
            'answer_images.*' => 'nullable|image|max:2048',
        ]);

        // For video questions, set a default question_text
        if ($request->question_type === 'video') {
            $validated['question_text'] = $validated['question_text'] ?? 'Video Question';
        }

        // Sanitize HTML content to prevent XSS attacks
        if (!empty($validated['question_text'])) {
            $validated['question_text'] = Purify::clean($validated['question_text']);
        }
        if (!empty($validated['answer_text'])) {
            $validated['answer_text'] = Purify::clean($validated['answer_text']);
        }

        // Handle multiple question images upload
        if ($request->hasFile('question_images')) {
            $questionImages = [];
            foreach ($request->file('question_images') as $image) {
                $path = $image->store('questions/images', 'public');
                $questionImages[] = $path;
            }
            $validated['question_images'] = $questionImages;
        }

        // Handle multiple answer images upload
        if ($request->hasFile('answer_images')) {
            $answerImages = [];
            foreach ($request->file('answer_images') as $image) {
                $path = $image->store('questions/answers', 'public');
                $answerImages[] = $path;
            }
            $validated['answer_images'] = $answerImages;
        }

        // Set default values
        $validated['ai_generated'] = $request->boolean('ai_generated', false);
        $validated['answer_source'] = $validated['ai_generated'] ? 'ai' : 'manual';

        // Auto-set order based on existing questions count
        $existingCount = Question::where('unit_id', $request->unit_id)
            ->when($request->parent_question_id, function($q) use ($request) {
                return $q->where('parent_question_id', $request->parent_question_id);
            }, function($q) {
                return $q->whereNull('parent_question_id');
            })
            ->count();
        $validated['order'] = $existingCount + 1;

        Question::create($validated);

        // Check if "Save & Create Another" was clicked
        if ($request->action === 'save_and_new') {
            return redirect()->route('admin.questions.create', ['unit' => $request->unit_id])
                ->with('success', 'Question created! You can add another one.');
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully!');
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        $question->load(['unit.course', 'parentQuestion', 'subQuestions']);
        
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        $units = Unit::with('course')->orderBy('unit_number')->get();
        $parentQuestions = Question::whereNull('parent_question_id')
            ->where('unit_id', $question->unit_id)
            ->where('id', '!=', $question->id)
            ->orderBy('order')
            ->get();

        return view('admin.questions.edit', compact('question', 'units', 'parentQuestions'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'exam_period_id' => 'required|exists:exam_periods,id',
            'question_type' => 'required|in:text,video',
            'video_url' => 'required_if:question_type,video|nullable|url',
            'parent_question_id' => 'nullable|exists:questions,id',
            'question_text' => 'required_if:question_type,text|nullable|string',
            'question_images.*' => 'nullable|image|max:2048',
            'answer_text' => 'required_if:question_type,text|nullable|string',
            'answer_images.*' => 'nullable|image|max:2048',
        ]);

        // For video questions, set a default question_text
        if ($request->question_type === 'video') {
            $validated['question_text'] = $validated['question_text'] ?? 'Video Question';
        }

        // Sanitize HTML content to prevent XSS attacks
        if (!empty($validated['question_text'])) {
            $validated['question_text'] = Purify::clean($validated['question_text']);
        }
        if (!empty($validated['answer_text'])) {
            $validated['answer_text'] = Purify::clean($validated['answer_text']);
        }

        // Handle multiple question images upload
        if ($request->hasFile('question_images')) {
            // Delete old images
            if ($question->question_images) {
                foreach ($question->question_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $questionImages = [];
            foreach ($request->file('question_images') as $image) {
                $path = $image->store('questions/images', 'public');
                $questionImages[] = $path;
            }
            $validated['question_images'] = $questionImages;
        }

        // Handle multiple answer images upload
        if ($request->hasFile('answer_images')) {
            // Delete old images
            if ($question->answer_images) {
                foreach ($question->answer_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $answerImages = [];
            foreach ($request->file('answer_images') as $image) {
                $path = $image->store('questions/answers', 'public');
                $answerImages[] = $path;
            }
            $validated['answer_images'] = $answerImages;
        }

        // Set AI generated flag if provided
        if ($request->has('ai_generated')) {
            $validated['ai_generated'] = $request->boolean('ai_generated');
            $validated['answer_source'] = $validated['ai_generated'] ? 'ai' : 'manual';
        }

        $question->update($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(Question $question)
    {
        // Delete question images
        if ($question->question_images) {
            foreach ($question->question_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete answer images
        if ($question->answer_images) {
            foreach ($question->answer_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Generate AI answer for a question.
     */
    public function generateAnswer(Question $question)
    {
        $aiService = new AiService();

        if (!$aiService->isConfigured()) {
            return back()->with('error', 'AI is not configured. Please add your API key in Settings.');
        }

        // Build context for better answer generation
        $context = [];
        if ($question->unit) {
            $context['unit'] = $question->unit->name;
            if ($question->unit->course) {
                $context['course'] = $question->unit->course->title;
            }
        }

        $result = $aiService->generateAnswer($question->question_text, $context);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Update the question with the AI-generated answer
        $question->update([
            'answer_text' => $result['answer'],
            'answer_source' => 'ai',
            'ai_generated' => true,
        ]);

        return back()->with('success', 'AI answer generated successfully!');
    }

    /**
     * Generate AI answer preview for new questions (before saving).
     */
    public function generateAnswerPreview(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
        ]);

        $aiService = new AiService();

        if (!$aiService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'AI is not configured. Please add your API key in Settings.'
            ], 400);
        }

        // Build context for better answer generation
        $context = [];
        if ($request->unit) {
            $context['unit'] = $request->unit;
        }
        if ($request->course) {
            $context['course'] = $request->course;
        }
        if ($request->instructions) {
            $context['instructions'] = $request->instructions;
        }

        $result = $aiService->generateAnswer($request->question_text, $context);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'answer' => $result['answer']
        ]);
    }

    /**
     * Show the import form.
     */
    public function showImport()
    {
        $units = Unit::with('course')->orderBy('unit_number')->get();
        return view('admin.questions.import', compact('units'));
    }

    /**
     * Download import template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="questions_import_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'question_number',
                'question_type',
                'video_url',
                'question_text',
                'answer_text',
                'order',
            ]);

            // Sample data rows - Text question
            fputcsv($file, [
                '1',
                'text',
                '',
                'What is the capital of Kenya?',
                'The capital of Kenya is Nairobi.',
                '1',
            ]);
            // Video question example
            fputcsv($file, [
                '2',
                'video',
                'https://www.youtube.com/watch?v=example123',
                'Video Question',
                '',
                '2',
            ]);
            fputcsv($file, [
                '3a',
                'text',
                '',
                'List three factors affecting demand.',
                '1. Price of the product\n2. Consumer income\n3. Consumer preferences',
                '3',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process the import.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'skip_header' => 'nullable|boolean',
            'update_existing' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $unitId = $request->unit_id;
        $skipHeader = $request->boolean('skip_header', true);
        $updateExisting = $request->boolean('update_existing', false);

        try {
            $result = $this->importFromCsv($file, $unitId, $skipHeader, $updateExisting);

            if ($result['errors']) {
                return back()
                    ->with('warning', "Imported {$result['imported']} questions with {$result['error_count']} errors.")
                    ->with('import_errors', $result['errors']);
            }

            return redirect()->route('admin.questions.index', ['unit' => $unitId])
                ->with('success', "Successfully imported {$result['imported']} questions!");

        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Import questions from CSV file.
     */
    protected function importFromCsv($file, int $unitId, bool $skipHeader, bool $updateExisting): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        $imported = 0;
        $errors = [];
        $rowNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            // Skip header row
            if ($skipHeader && $rowNumber === 1) {
                continue;
            }

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                $result = $this->processImportRow($row, $unitId, $rowNumber, $updateExisting);
                if ($result === true) {
                    $imported++;
                } else {
                    $errors[] = $result;
                }
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return [
            'imported' => $imported,
            'error_count' => count($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Process a single import row.
     */
    protected function processImportRow(array $row, int $unitId, int $rowNumber, bool $updateExisting): bool|string
    {
        // Expected columns: question_number, question_type, video_url, question_text, answer_text, order
        $questionNumber = trim($row[0] ?? '');
        $questionType = trim($row[1] ?? 'text');
        $videoUrl = trim($row[2] ?? '');
        $questionText = trim($row[3] ?? '');
        $answerText = trim($row[4] ?? '');
        $order = (int) ($row[5] ?? $rowNumber);

        // Validate question type
        if (!in_array($questionType, ['text', 'video'])) {
            $questionType = 'text';
        }

        // Validate required fields
        if (empty($questionNumber)) {
            return "Row {$rowNumber}: Question number is required";
        }

        // For text questions, question_text is required
        if ($questionType === 'text' && empty($questionText)) {
            return "Row {$rowNumber}: Question text is required for text questions";
        }

        // For video questions, video_url is required
        if ($questionType === 'video' && empty($videoUrl)) {
            return "Row {$rowNumber}: Video URL is required for video questions";
        }

        // Set default question_text for video questions
        if ($questionType === 'video' && empty($questionText)) {
            $questionText = 'Video Question';
        }

        // Check for existing question
        $existingQuestion = Question::where('unit_id', $unitId)
            ->where('question_number', $questionNumber)
            ->first();

        if ($existingQuestion) {
            if ($updateExisting) {
                $existingQuestion->update([
                    'question_type' => $questionType,
                    'video_url' => $questionType === 'video' ? $videoUrl : null,
                    'question_text' => $questionText,
                    'answer_text' => $answerText ?: null,
                    'order' => $order,
                    'answer_source' => 'manual',
                ]);
                return true;
            } else {
                return "Row {$rowNumber}: Question '{$questionNumber}' already exists";
            }
        }

        // Create new question
        Question::create([
            'unit_id' => $unitId,
            'question_type' => $questionType,
            'video_url' => $questionType === 'video' ? $videoUrl : null,
            'question_number' => $questionNumber,
            'question_text' => $questionText,
            'answer_text' => $answerText ?: null,
            'order' => $order,
            'answer_source' => 'manual',
            'ai_generated' => false,
        ]);

        return true;
    }

    /**
     * Export questions to CSV.
     */
    public function export(Request $request)
    {
        $unitId = $request->get('unit');
        $filename = 'questions_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($unitId) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Unit',
                'Question Number',
                'Question Type',
                'Video URL',
                'Question Text',
                'Answer Text',
                'Order',
                'Answer Source',
                'View Count',
                'Created At',
            ]);

            $query = Question::with('unit.course');
            if ($unitId) {
                $query->where('unit_id', $unitId);
            }

            $query->orderBy('unit_id')->orderBy('order')->chunk(500, function($questions) use ($file) {
                foreach ($questions as $question) {
                    fputcsv($file, [
                        $question->id,
                        $question->unit ? $question->unit->name : 'N/A',
                        $question->question_number,
                        $question->question_type ?? 'text',
                        $question->video_url ?? '',
                        $question->question_text,
                        $question->answer_text,
                        $question->order,
                        $question->answer_source ?? 'manual',
                        $question->view_count,
                        $question->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate the next question number automatically.
     */
    protected function generateQuestionNumber($unitId, $parentQuestionId = null): string
    {
        if ($parentQuestionId) {
            // Sub-question: get parent's number and append next letter
            $parentQuestion = Question::find($parentQuestionId);
            if (!$parentQuestion) {
                return '1a'; // Fallback
            }

            // Count existing sub-questions for this parent
            $subCount = Question::where('parent_question_id', $parentQuestionId)->count();
            // Convert count to letter: 0=a, 1=b, 2=c, etc.
            $letter = chr(97 + $subCount); // 97 is ASCII for 'a'

            return $parentQuestion->question_number . $letter;
        } else {
            // Main question: get next number in unit
            $maxNumber = Question::where('unit_id', $unitId)
                ->whereNull('parent_question_id')
                ->selectRaw('MAX(CAST(question_number AS UNSIGNED)) as max_num')
                ->value('max_num');

            return (string) (($maxNumber ?? 0) + 1);
        }
    }

    /**
     * Handle bulk actions on questions.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:questions,id',
        ]);

        $count = count($validated['ids']);

        switch ($validated['action']) {
            case 'delete':
                // Get all questions to delete their images
                $questions = Question::whereIn('id', $validated['ids'])->get();

                foreach ($questions as $question) {
                    // Delete question images
                    if ($question->question_images) {
                        foreach ($question->question_images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }

                    // Delete answer images
                    if ($question->answer_images) {
                        foreach ($question->answer_images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }

                // Delete questions (this will cascade to sub-questions due to foreign key)
                Question::whereIn('id', $validated['ids'])->delete();
                $message = "{$count} question(s) deleted successfully!";
                break;

            default:
                return back()->with('error', 'Invalid action.');
        }

        return redirect()->route('admin.questions.index')->with('success', $message);
    }
}
