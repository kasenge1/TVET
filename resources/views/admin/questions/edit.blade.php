@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Question')
@section('page-actions')
    <a href="{{ route('admin.questions.show', $question) }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Question
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="unit_id" class="form-label fw-medium">Unit <span class="text-danger">*</span></label>
                    <select class="form-select" id="unit_id" name="unit_id" required>
                        @foreach(\App\Models\Course::with('units')->get() as $course)
                            <optgroup label="{{ $course->title }}">
                                @foreach($course->units as $unit)
                                    <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $question->unit_id) == $unit->id ? 'selected' : '' }}>
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
                            <div class="question-type-card {{ old('question_type', $question->question_type ?? 'text') === 'text' ? 'active' : '' }}" onclick="selectQuestionType('text')">
                                <input type="radio" name="question_type" id="type_text" value="text" class="d-none" {{ old('question_type', $question->question_type ?? 'text') === 'text' ? 'checked' : '' }}>
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
                            <div class="question-type-card {{ old('question_type', $question->question_type) === 'video' ? 'active' : '' }}" onclick="selectQuestionType('video')">
                                <input type="radio" name="question_type" id="type_video" value="video" class="d-none" {{ old('question_type', $question->question_type) === 'video' ? 'checked' : '' }}>
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

                <div id="video_section" class="mb-4" style="{{ old('question_type', $question->question_type) === 'video' ? '' : 'display: none;' }}">
                    <label for="video_url" class="form-label fw-medium">YouTube Video URL <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-youtube text-danger"></i></span>
                        <input type="url"
                               class="form-control @error('video_url') is-invalid @enderror"
                               id="video_url"
                               name="video_url"
                               value="{{ old('video_url', $question->video_url) }}"
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <small class="text-muted">Paste the YouTube video URL. The video should contain the question and answer options.</small>
                    @error('video_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div id="video_preview" class="mt-3" style="{{ $question->youtube_embed_url ? '' : 'display: none;' }}">
                        <div class="ratio ratio-16x9">
                            <iframe id="video_iframe" src="{{ $question->youtube_embed_url }}" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <div id="text_section" style="{{ old('question_type', $question->question_type) === 'video' ? 'display: none;' : '' }}">
                <x-quill-editor
                    name="question_text"
                    id="question_editor"
                    label="Question Text"
                    placeholder="Enter the question text here..."
                    height="300px"
                    :value="old('question_text', $question->question_text)"
                    required
                />

                @if($question->question_images)
                    @php
                        // question_images is already cast to array in the model
                        $existingImages = $question->question_images;
                    @endphp
                    @if(count($existingImages) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-medium">Current Question Images</label>
                            <div class="row g-2">
                                @foreach($existingImages as $index => $image)
                                    <div class="col-md-3">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Question Image">
                                            <div class="form-check position-absolute top-0 end-0 m-2 bg-white rounded px-2">
                                                <input class="form-check-input" type="checkbox" name="remove_question_images[]" value="{{ $index }}" id="remove_q_{{ $index }}">
                                                <label class="form-check-label small" for="remove_q_{{ $index }}">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <div class="mb-4">
                    <label for="question_images" class="form-label fw-medium">Add More Question Images (Optional)</label>
                    <input type="file"
                           class="form-control @error('question_images') is-invalid @enderror"
                           id="question_images"
                           name="question_images[]"
                           accept="image/*"
                           multiple>
                    <small class="text-muted">You can upload multiple images. Max: 2MB per image</small>
                    @error('question_images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4 mb-4" id="answer">
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
                        :value="old('answer_text', $question->answer_text)"
                        required
                    />

                    @if($question->answer_images)
                        @php
                            // answer_images is already cast to array in the model
                            $existingAnswerImages = $question->answer_images;
                        @endphp
                        @if(count($existingAnswerImages) > 0)
                            <div class="mb-4">
                                <label class="form-label fw-medium">Current Answer Images</label>
                                <div class="row g-2">
                                    @foreach($existingAnswerImages as $index => $image)
                                        <div class="col-md-3">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Answer Image">
                                                <div class="form-check position-absolute top-0 end-0 m-2 bg-white rounded px-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_answer_images[]" value="{{ $index }}" id="remove_a_{{ $index }}">
                                                    <label class="form-check-label small" for="remove_a_{{ $index }}">
                                                        Remove
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="mb-4">
                        <label for="answer_images" class="form-label fw-medium">Add More Answer Images (Optional)</label>
                        <input type="file"
                               class="form-control @error('answer_images') is-invalid @enderror"
                               id="answer_images"
                               name="answer_images[]"
                               accept="image/*"
                               multiple>
                        <small class="text-muted">You can upload multiple images. Max: 2MB per image</small>
                        @error('answer_images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                </div><!-- End text_section -->

                <input type="hidden" name="ai_generated" value="{{ $question->ai_generated ? '1' : '0' }}" id="ai_generated_hidden">

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Question
                        </button>
                        <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <div class="sidebar-sticky" style="position: sticky; top: 20px;">
            <x-card title="Question Statistics">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Created</span>
                    <span>{{ $question->created_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Last Updated</span>
                    <span>{{ $question->updated_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Sub-Questions</span>
                    <span class="fw-bold">{{ $question->subQuestions->count() }}</span>
                </div>
            </x-card>

            <x-card title="Quick Actions" class="mt-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Question
                    </a>
                    @if(!$question->parent_question_id)
                        <a href="{{ route('admin.questions.create', ['unit' => $question->unit_id, 'parent' => $question->id]) }}" class="btn btn-outline-info">
                            <i class="bi bi-arrow-return-right me-2"></i>Add Sub-Question
                        </a>
                    @endif
                </div>
            </x-card>

            <x-card title="Tips" class="mt-4">
                <div class="mb-3">
                    <p class="small text-muted mb-1"><strong>Question Numbers:</strong></p>
                    <p class="small text-muted mb-0">Use numbers for main questions (1, 2, 3) and letters for sub-questions (1a, 1b)</p>
                </div>
                <div>
                    <p class="small text-muted mb-1"><strong>Math Formulas:</strong></p>
                    <p class="small text-muted mb-0">Click the <code>fx</code> button in the editor toolbar to insert LaTeX equations</p>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

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

            return axios.post('{{ route("admin.questions.generate-answer", $question) }}', {
                question_text: questionText,
                instructions: instructions,
                unit_id: unitSelect.value
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

// Initialize required state based on selected question type
document.addEventListener('DOMContentLoaded', function() {
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
