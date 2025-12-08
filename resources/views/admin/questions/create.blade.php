@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create New Question')
@section('page-actions')
    <a href="{{ route('admin.questions.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Questions
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf

                <div class="mb-4">
                    <label for="unit_id" class="form-label fw-medium">Unit <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg" id="unit_id" name="unit_id" required>
                        <option value="">Select a unit</option>
                        @foreach(\App\Models\Course::with('units')->get() as $course)
                            <optgroup label="{{ $course->title }}">
                                @foreach($course->units as $unit)
                                    <option value="{{ $unit->id }}"
                                            {{ old('unit_id', request('unit')) == $unit->id ? 'selected' : '' }}
                                            data-course="{{ $course->title }}"
                                            data-unit="{{ $unit->unit_number }}">
                                        Unit {{ $unit->unit_number }}: {{ $unit->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Question Type Selection - Card Style -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Question Type <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="question-type-card {{ old('question_type', 'text') === 'text' ? 'active' : '' }}" onclick="selectQuestionType('text')">
                                <input type="radio" name="question_type" id="type_text" value="text" class="d-none" {{ old('question_type', 'text') === 'text' ? 'checked' : '' }}>
                                <div class="card h-100 border-2 cursor-pointer">
                                    <div class="card-body text-center py-4">
                                        <div class="type-icon mb-3">
                                            <i class="bi bi-file-text-fill display-4 text-primary"></i>
                                        </div>
                                        <h5 class="card-title mb-2">Text Question</h5>
                                        <p class="card-text text-muted small mb-0">
                                            Write question and answer with rich text editor, images, and math formulas
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="question-type-card {{ old('question_type') === 'video' ? 'active' : '' }}" onclick="selectQuestionType('video')">
                                <input type="radio" name="question_type" id="type_video" value="video" class="d-none" {{ old('question_type') === 'video' ? 'checked' : '' }}>
                                <div class="card h-100 border-2 cursor-pointer">
                                    <div class="card-body text-center py-4">
                                        <div class="type-icon mb-3">
                                            <i class="bi bi-youtube display-4 text-danger"></i>
                                        </div>
                                        <h5 class="card-title mb-2">Video Question</h5>
                                        <p class="card-text text-muted small mb-0">
                                            Embed a YouTube video that contains both question and answer options
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Question Selection -->
                <div class="mb-4">
                    <label for="parent_question_id" class="form-label fw-medium">Question Category</label>
                    <select class="form-select form-select-lg" id="parent_question_id" name="parent_question_id">
                        <option value="">Main Question (New numbered question)</option>
                    </select>
                    <small class="text-muted">Select a parent to create a sub-question (a, b, c...)</small>
                </div>

                <!-- Auto-generated Question Number Display -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Question Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-hash"></i>
                        </span>
                        <input type="text"
                               class="form-control form-control-lg bg-light fw-bold @error('question_number') is-invalid @enderror"
                               id="question_number_display"
                               value=""
                               readonly
                               placeholder="Auto-generated">
                        <input type="hidden" name="question_number" id="question_number" value="{{ old('question_number') }}">
                    </div>
                    <small class="text-muted" id="question_number_help">
                        <i class="bi bi-info-circle me-1"></i>Question number is automatically assigned based on the unit
                    </small>
                    @error('question_number')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div id="video_section" class="mb-4" style="{{ old('question_type') === 'video' ? '' : 'display: none;' }}">
                    <label for="video_url" class="form-label fw-medium">YouTube Video URL <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-youtube text-danger"></i></span>
                        <input type="url"
                               class="form-control @error('video_url') is-invalid @enderror"
                               id="video_url"
                               name="video_url"
                               value="{{ old('video_url') }}"
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <small class="text-muted">Paste the YouTube video URL. The video should contain the question and answer options.</small>
                    @error('video_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div id="video_preview" class="mt-3" style="display: none;">
                        <div class="ratio ratio-16x9">
                            <iframe id="video_iframe" src="" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <div id="text_section" style="{{ old('question_type') === 'video' ? 'display: none;' : '' }}">
                <x-quill-editor
                    name="question_text"
                    id="question_editor"
                    label="Question Text"
                    placeholder="Enter the question text here..."
                    height="300px"
                    required
                />


                <div class="mb-4">
                    <label for="question_images" class="form-label fw-medium">Question Images (Optional)</label>
                    <input type="file"
                           class="form-control @error('question_images') is-invalid @enderror"
                           id="question_images"
                           name="question_images[]"
                           accept="image/*"
                           multiple
                           onchange="previewImages(this, 'question_preview')">
                    <small class="text-muted">You can upload multiple images. Max: 2MB per image</small>
                    @error('question_images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="question_preview" class="row g-2 mt-2"></div>
                </div>

                <div class="border-top pt-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Answer <span class="text-danger">*</span></h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="generateAnswer()">
                            <i class="bi bi-stars me-1"></i>Generate with AI
                        </button>
                    </div>

                    <x-quill-editor
                        name="answer_text"
                        id="answer_editor"
                        label="Answer Text"
                        placeholder="Enter the answer text here..."
                        height="300px"
                        required
                    />


                    <div class="mb-4">
                        <label for="answer_images" class="form-label fw-medium">Answer Images (Optional)</label>
                        <input type="file"
                               class="form-control @error('answer_images') is-invalid @enderror"
                               id="answer_images"
                               name="answer_images[]"
                               accept="image/*"
                               multiple
                               onchange="previewImages(this, 'answer_preview')">
                        <small class="text-muted">You can upload multiple images. Max: 2MB per image</small>
                        @error('answer_images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="answer_preview" class="row g-2 mt-2"></div>
                    </div>
                </div>
                </div><!-- End text_section -->

                <input type="hidden" name="ai_generated" value="0" id="ai_generated_hidden">

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Question
                        </button>
                        <button type="submit" name="action" value="save_and_new" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i>Save & Create Another
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Tips for Adding Questions" class="sticky-top" style="top: 20px;">
            <div class="mb-4">
                <h6 class="text-primary mb-2"><i class="bi bi-1-circle me-2"></i>Question Numbers</h6>
                <p class="small text-muted mb-1"><strong>Main Questions:</strong> Enter numbers like 1, 2, 3</p>
                <p class="small text-muted mb-0"><strong>Sub-Questions:</strong> Select a parent, then enter letter (a, b, c)</p>
            </div>
            <div class="mb-4">
                <h6 class="text-primary mb-2"><i class="bi bi-pencil-square me-2"></i>Rich Text Editor</h6>
                <p class="small text-muted mb-1">Use the toolbar for formatting, images, videos, and code blocks</p>
                <p class="small text-muted mb-0"><strong>Math Formulas:</strong> Click the <code>fx</code> button to insert LaTeX equations</p>
            </div>
            <div class="mb-4">
                <h6 class="text-primary mb-2"><i class="bi bi-stars me-2"></i>AI Generation</h6>
                <p class="small text-muted mb-0">Click "Generate with AI" to automatically create comprehensive answers based on the question</p>
            </div>
            <div>
                <h6 class="text-primary mb-2"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                <p class="small text-muted mb-0">Use "Save & Create Another" when adding multiple questions in bulk to save time</p>
            </div>
        </x-card>
    </div>
</div>
@endsection

@php
    $parentQuestionsJson = \App\Models\Question::whereNull('parent_question_id')
        ->select(['id', 'unit_id', 'question_number', 'question_text'])
        ->get();

    // Get question counts for auto-numbering
    $questionCountsJson = \App\Models\Question::whereNull('parent_question_id')
        ->selectRaw('unit_id, MAX(CAST(question_number AS UNSIGNED)) as max_number')
        ->groupBy('unit_id')
        ->pluck('max_number', 'unit_id');

    // Get sub-question counts per parent
    $subQuestionCountsJson = \App\Models\Question::whereNotNull('parent_question_id')
        ->selectRaw('parent_question_id, COUNT(*) as sub_count')
        ->groupBy('parent_question_id')
        ->pluck('sub_count', 'parent_question_id');
@endphp

@push('styles')
<style>
    .question-type-card .card {
        transition: all 0.2s ease;
        border-color: #dee2e6 !important;
    }
    .question-type-card:hover .card {
        border-color: #0d6efd !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .question-type-card.active .card {
        border-color: #0d6efd !important;
        background-color: #f8f9ff;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }
    .question-type-card.active .type-icon i {
        transform: scale(1.1);
    }
    .type-icon i {
        transition: transform 0.2s ease;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
// Store parent questions data
const parentQuestionsData = @json($parentQuestionsJson);
const questionCounts = @json($questionCountsJson);
const subQuestionCounts = @json($subQuestionCountsJson);

// Question Type Selection
function selectQuestionType(type) {
    // Update radio buttons
    document.getElementById('type_text').checked = (type === 'text');
    document.getElementById('type_video').checked = (type === 'video');

    // Update card styling
    document.querySelectorAll('.question-type-card').forEach(card => {
        card.classList.remove('active');
    });
    event.currentTarget.classList.add('active');

    // Toggle sections
    const textSection = document.getElementById('text_section');
    const videoSection = document.getElementById('video_section');

    // Toggle required attributes on textareas
    const questionTextarea = document.getElementById('textarea_question_text');
    const answerTextarea = document.getElementById('textarea_answer_text');
    const videoUrlInput = document.getElementById('video_url');

    if (type === 'video') {
        textSection.style.display = 'none';
        videoSection.style.display = 'block';
        // Remove required from text fields when video is selected
        if (questionTextarea) questionTextarea.removeAttribute('required');
        if (answerTextarea) answerTextarea.removeAttribute('required');
        // Make video URL required
        if (videoUrlInput) videoUrlInput.setAttribute('required', 'required');
    } else {
        textSection.style.display = 'block';
        videoSection.style.display = 'none';
        // Add required back to text fields
        if (questionTextarea) questionTextarea.setAttribute('required', 'required');
        if (answerTextarea) answerTextarea.setAttribute('required', 'required');
        // Remove required from video URL
        if (videoUrlInput) videoUrlInput.removeAttribute('required');
    }
}

// Image Preview
function previewImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-3';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'img-thumbnail';
            img.style.maxHeight = '120px';
            img.style.objectFit = 'cover';

            col.appendChild(img);
            preview.appendChild(col);
        });
    }
}

// Generate next question number
function getNextQuestionNumber(unitId) {
    const maxNumber = questionCounts[unitId] || 0;
    return parseInt(maxNumber) + 1;
}

// Generate next sub-question letter
function getNextSubQuestionLetter(parentId) {
    const count = subQuestionCounts[parentId] || 0;
    // Convert count to letter: 0=a, 1=b, 2=c, etc.
    return String.fromCharCode(97 + count);
}

// Update parent questions dropdown when unit changes
document.getElementById('unit_id').addEventListener('change', function() {
    const unitId = this.value;
    const parentSelect = document.getElementById('parent_question_id');

    // Clear existing options except the first one
    parentSelect.innerHTML = '<option value="">Main Question (New numbered question)</option>';

    if (unitId) {
        // Filter parent questions for this unit
        const unitQuestions = parentQuestionsData.filter(q => q.unit_id == unitId);

        unitQuestions.forEach(q => {
            const option = document.createElement('option');
            option.value = q.id;
            option.dataset.questionNumber = q.question_number;
            // Strip HTML tags for display
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = q.question_text;
            const plainText = tempDiv.textContent || tempDiv.innerText;
            option.textContent = `Q${q.question_number}: ${plainText.substring(0, 50)}${plainText.length > 50 ? '...' : ''}`;
            parentSelect.appendChild(option);
        });
    }

    // Update auto-generated question number
    updateQuestionNumber();
});

// Update question number when parent changes
document.getElementById('parent_question_id').addEventListener('change', updateQuestionNumber);

function updateQuestionNumber() {
    const unitSelect = document.getElementById('unit_id');
    const parentSelect = document.getElementById('parent_question_id');
    const displayInput = document.getElementById('question_number_display');
    const hiddenInput = document.getElementById('question_number');
    const help = document.getElementById('question_number_help');

    const unitId = unitSelect.value;

    if (!unitId) {
        displayInput.value = '';
        hiddenInput.value = '';
        help.innerHTML = '<i class="bi bi-info-circle me-1"></i>Select a unit first';
        return;
    }

    if (parentSelect.value) {
        // Sub-question - get next letter
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        const parentNumber = selectedOption.dataset.questionNumber;
        const nextLetter = getNextSubQuestionLetter(parentSelect.value);
        const fullNumber = parentNumber + nextLetter;

        displayInput.value = fullNumber;
        hiddenInput.value = fullNumber;
        help.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i>Sub-question of Q${parentNumber}`;
    } else {
        // Main question - get next number
        const nextNumber = getNextQuestionNumber(unitId);

        displayInput.value = nextNumber;
        hiddenInput.value = nextNumber;
        help.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i>Next available question number in this unit`;
    }
}

// AI Answer Generation
function generateAnswer() {
    const questionEditor = window.editor_question_editor;
    const answerEditor = window.editor_answer_editor;
    const questionText = questionEditor.root.innerHTML;
    const unitSelect = document.getElementById('unit_id');
    const selectedOption = unitSelect.options[unitSelect.selectedIndex];

    if (!questionEditor.getText().trim()) {
        toastError('Please enter the question text first');
        return;
    }

    if (!unitSelect.value) {
        toastError('Please select a unit first');
        return;
    }

    Swal.fire({
        title: '<i class="bi bi-stars text-primary"></i> Generate Answer with AI',
        html: `
            <div class="text-start">
                <p class="text-muted small mb-3">Our AI will analyze the question and generate a comprehensive answer for you.</p>
                <label class="form-label small fw-medium mb-2">Additional Instructions (Optional)</label>
                <textarea id="ai-instructions" class="form-control" rows="3"
                    placeholder="E.g., Include step-by-step solution, Focus on practical examples, Add diagrams description..."></textarea>
                <div class="mt-3 p-2 bg-light rounded small">
                    <i class="bi bi-lightbulb text-warning me-1"></i>
                    <strong>Tip:</strong> Be specific about the format or depth you need.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-magic me-1"></i> Generate Answer',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6c757d',
        showLoaderOnConfirm: true,
        width: '500px',
        customClass: {
            popup: 'rounded-3',
            title: 'fs-5 fw-bold',
            confirmButton: 'btn btn-primary px-4',
            cancelButton: 'btn btn-secondary px-4'
        },
        preConfirm: () => {
            const instructions = document.getElementById('ai-instructions').value;

            return axios.post('{{ route("admin.questions.generate-answer-preview") }}', {
                question_text: questionText,
                instructions: instructions,
                unit_id: unitSelect.value,
                course: selectedOption.dataset.course,
                unit: selectedOption.dataset.unit
            })
            .then(response => response.data)
            .catch(error => {
                Swal.showValidationMessage(
                    `<i class="bi bi-exclamation-circle me-1"></i> ${error.response?.data?.message || error.message}`
                );
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            answerEditor.root.innerHTML = result.value.answer;
            document.getElementById('ai_generated_hidden').value = '1';
            toastSuccess('Answer generated successfully!');
        }
    });
}

// Initialize on page load (if unit is pre-selected)
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('unit_id');
    if (unitSelect.value) {
        unitSelect.dispatchEvent(new Event('change'));
    }

    // Also check for pre-selected parent from URL
    const urlParams = new URLSearchParams(window.location.search);
    const parentId = urlParams.get('parent');
    if (parentId) {
        setTimeout(() => {
            const parentSelect = document.getElementById('parent_question_id');
            parentSelect.value = parentId;
            updateQuestionNumber();
        }, 100);
    }
});

// YouTube Video Preview
document.getElementById('video_url').addEventListener('input', function() {
    const url = this.value;
    const preview = document.getElementById('video_preview');
    const iframe = document.getElementById('video_iframe');

    const videoId = extractYouTubeId(url);

    if (videoId) {
        // Use youtube-nocookie.com with privacy settings
        // rel=0 prevents related videos, modestbranding=1 reduces branding
        iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?rel=0&modestbranding=1&showinfo=0`;
        preview.style.display = 'block';
    } else {
        iframe.src = '';
        preview.style.display = 'none';
    }
});

function extractYouTubeId(url) {
    if (!url) return null;

    const patterns = [
        /youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/,
        /youtube\.com\/embed\/([a-zA-Z0-9_-]+)/,
        /youtu\.be\/([a-zA-Z0-9_-]+)/,
        /youtube\.com\/v\/([a-zA-Z0-9_-]+)/,
    ];

    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match) return match[1];
    }

    return null;
}

// Initialize video preview if URL exists
document.addEventListener('DOMContentLoaded', function() {
    const videoUrl = document.getElementById('video_url');
    if (videoUrl.value) {
        videoUrl.dispatchEvent(new Event('input'));
    }

    // Initialize required state based on selected question type
    const videoTypeSelected = document.getElementById('type_video').checked;
    if (videoTypeSelected) {
        const questionTextarea = document.getElementById('textarea_question_text');
        const answerTextarea = document.getElementById('textarea_answer_text');
        const videoUrlInput = document.getElementById('video_url');
        if (questionTextarea) questionTextarea.removeAttribute('required');
        if (answerTextarea) answerTextarea.removeAttribute('required');
        if (videoUrlInput) videoUrlInput.setAttribute('required', 'required');
    }
});
</script>
@endpush
