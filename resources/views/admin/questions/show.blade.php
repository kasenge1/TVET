@extends('layouts.admin')

@section('page-header', true)
@section('page-title')
    Question #{{ $question->period_question_number ?? $question->question_number }}
    @if($question->examPeriod)
        <small class="text-muted fs-6">({{ $question->examPeriod->name }})</small>
    @endif
@endsection
@section('page-actions')
    <a href="{{ route('admin.questions.edit', $question) }}" class="btn-modern btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Question
    </a>
@endsection

@section('main')
<div class="row g-4">
    <div class="col-xl-8">
        <x-card title="Question Details">
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="badge-modern badge bg-primary fs-6">Q{{ $question->period_question_number ?? $question->question_number }}</span>
                    @if($question->examPeriod)
                        <span class="badge-modern badge bg-secondary">
                            <i class="bi bi-calendar-event me-1"></i>{{ $question->examPeriod->name }}
                        </span>
                    @endif
                    @if($question->isVideoQuestion())
                        <span class="badge-modern badge bg-danger">
                            <i class="bi bi-play-circle"></i> Video
                        </span>
                    @endif
                    @if($question->ai_generated)
                        <span class="badge-modern badge bg-info">
                            <i class="bi bi-robot"></i> AI Generated
                        </span>
                    @endif
                    @if($question->parent_question_id)
                        <span class="badge-modern badge bg-secondary">Sub-question</span>
                    @endif
                    @if($question->has_sub_questions)
                        <span class="badge-modern badge bg-warning text-dark">
                            <i class="bi bi-diagram-3 me-1"></i>Has {{ $question->subQuestions->count() }} sub-question(s)
                        </span>
                    @endif
                </div>

                @if($question->isVideoQuestion())
                    {{-- Video Question Display --}}
                    @if($question->youtube_embed_url)
                        <div class="ratio ratio-16x9 mb-3">
                            <iframe src="{{ $question->youtube_embed_url }}"
                                    title="Video Question"
                                    allowfullscreen
                                    class="rounded"></iframe>
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            This video contains both the question and answer options.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Invalid or missing video URL
                        </div>
                    @endif
                @else
                    {{-- Text Question Display --}}
                    <div class="fs-5 fw-medium mb-3 quill-content">{!! $question->question_text !!}</div>

                    @if($question->question_images && count($question->question_images) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Question Images:</h6>
                            <div class="row g-2">
                                @foreach($question->question_images as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             class="img-thumbnail hover-lift"
                                             alt="Question Image"
                                             style="cursor: pointer;"
                                             onclick="window.open(this.src, '_blank')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            @if($question->isTextQuestion())
                @if($question->has_sub_questions && $question->subQuestions->count() > 0)
                    {{-- Question has sub-questions - answers are in sub-questions --}}
                    <div class="border-top pt-4">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> This question has sub-questions. The answers are provided in each sub-question below.
                        </div>
                    </div>
                @elseif($question->answer_text)
                    {{-- Regular question with answer --}}
                    <div class="border-top pt-4">
                        <h5 class="mb-3">
                            <i class="bi bi-check-circle text-success"></i> Answer
                        </h5>
                        <div class="bg-light p-4 rounded">
                            <div class="mb-3 quill-content">{!! $question->answer_text !!}</div>

                            @if($question->answer_images && count($question->answer_images) > 0)
                                <h6 class="text-muted mb-2">Answer Images:</h6>
                                <div class="row g-2">
                                    @foreach($question->answer_images as $image)
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 class="img-thumbnail hover-lift"
                                                 alt="Answer Image"
                                                 style="cursor: pointer;"
                                                 onclick="window.open(this.src, '_blank')">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif(!$question->has_sub_questions)
                    {{-- No answer and no sub-questions --}}
                    <div class="border-top pt-4">
                        <div class="text-center py-4 bg-light rounded">
                            <i class="bi bi-x-circle text-warning display-4 d-block mb-2"></i>
                            <p class="text-muted mb-0">No answer provided yet</p>
                        </div>
                    </div>
                @endif
            @endif
        </x-card>

        @if($question->subQuestions->count() > 0)
            <x-card class="mt-4">
                <x-slot name="title">
                    <i class="bi bi-list-ol me-2"></i>Sub-Questions ({{ $question->subQuestions->count() }})
                </x-slot>
                <div class="sub-questions-list">
                    @foreach($question->subQuestions as $index => $subQuestion)
                        @php
                            // Get the sub-question letter (a, b, c...)
                            $subLetter = chr(97 + $index); // 97 = 'a'
                            $parentPeriodNum = $question->period_question_number ?? $question->question_number;
                        @endphp
                        <div class="sub-question-card mb-4 {{ !$loop->last ? 'pb-4 border-bottom' : '' }}">
                            {{-- Sub-question Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                        {{ $subLetter }}
                                    </div>
                                    <div>
                                        <span class="badge bg-light text-dark border">Q{{ $parentPeriodNum }}{{ $subLetter }}</span>
                                        @if($subQuestion->ai_generated)
                                            <span class="badge bg-info ms-1"><i class="bi bi-robot"></i> AI</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.questions.show', $subQuestion) }}" class="btn btn-outline-primary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.questions.edit', $subQuestion) }}" class="btn btn-outline-secondary" title="Edit Sub-question">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            onclick="deleteSubQuestion({{ $subQuestion->id }}, '{{ $subLetter }}')"
                                            title="Delete Sub-question">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Sub-question Text --}}
                            <div class="ms-5 ps-2">
                                <div class="mb-3">
                                    <small class="text-uppercase text-muted fw-bold d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        Question
                                    </small>
                                    <div class="quill-content">{!! $subQuestion->question_text !!}</div>

                                    @if($subQuestion->question_images && count($subQuestion->question_images) > 0)
                                        <div class="row g-2 mt-2">
                                            @foreach($subQuestion->question_images as $image)
                                                <div class="col-md-3">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                         class="img-thumbnail"
                                                         alt="Question Image"
                                                         style="cursor: pointer; max-height: 100px; object-fit: cover;"
                                                         onclick="window.open(this.src, '_blank')">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Sub-question Answer --}}
                                <div class="bg-success bg-opacity-10 border-start border-success border-3 p-3 rounded-end">
                                    <small class="text-uppercase text-success fw-bold d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Answer
                                    </small>
                                    @if($subQuestion->answer_text)
                                        <div class="quill-content">{!! $subQuestion->answer_text !!}</div>

                                        @if($subQuestion->answer_images && count($subQuestion->answer_images) > 0)
                                            <div class="row g-2 mt-2">
                                                @foreach($subQuestion->answer_images as $image)
                                                    <div class="col-md-3">
                                                        <img src="{{ asset('storage/' . $image) }}"
                                                             class="img-thumbnail"
                                                             alt="Answer Image"
                                                             style="cursor: pointer; max-height: 100px; object-fit: cover;"
                                                             onclick="window.open(this.src, '_blank')">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-warning">
                                            <i class="bi bi-exclamation-circle me-1"></i>No answer provided yet
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif
    </div>

    <div class="col-xl-4">
        <x-card title="Question Information">
            <div class="mb-3">
                <div class="text-muted small mb-1">Course</div>
                <a href="{{ route('admin.courses.show', $question->unit->course) }}" class="fw-medium">
                    {{ $question->unit->course->title }}
                </a>
            </div>
            <div class="mb-3">
                <div class="text-muted small mb-1">Unit</div>
                <a href="{{ route('admin.units.show', $question->unit) }}" class="fw-medium">
                    Unit {{ $question->unit->unit_number }}: {{ $question->unit->title }}
                </a>
            </div>
            <div class="mb-3">
                <div class="text-muted small mb-1">Global Number</div>
                <div class="fw-medium">
                    <span class="badge bg-secondary">#{{ $question->question_number }}</span>
                </div>
            </div>
            @if($question->period_question_number)
            <div class="mb-3">
                <div class="text-muted small mb-1">Period Number</div>
                <div class="fw-medium">
                    <span class="badge bg-primary">#{{ $question->period_question_number }}</span>
                    @if($question->examPeriod)
                        <small class="text-muted ms-1">in {{ $question->examPeriod->name }}</small>
                    @endif
                </div>
            </div>
            @endif
            <div class="mb-3">
                <div class="text-muted small mb-1">Question Type</div>
                <div class="fw-medium">
                    @if($question->isVideoQuestion())
                        <span class="badge bg-danger"><i class="bi bi-play-circle me-1"></i>Video</span>
                    @else
                        <span class="badge bg-primary"><i class="bi bi-file-text me-1"></i>Text</span>
                    @endif
                </div>
            </div>
            @if($question->parent_question_id)
                <div class="mb-3">
                    <div class="text-muted small mb-1">Parent Question</div>
                    <a href="{{ route('admin.questions.show', $question->parentQuestion) }}" class="fw-medium">
                        Q{{ $question->parentQuestion->question_number }}
                    </a>
                </div>
            @endif
            <div class="mb-3">
                <div class="text-muted small mb-1">Sub-Questions</div>
                <div class="fw-medium">{{ $question->subQuestions->count() }}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small mb-1">Created</div>
                <div class="fw-medium">{{ $question->created_at->format('M d, Y') }}</div>
            </div>
        </x-card>

        <x-card title="Quick Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Question
                </a>
                @if(!$question->answer_text && !$question->has_sub_questions)
                    <a href="{{ route('admin.questions.edit', $question) }}#answer" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-2"></i>Add Answer
                    </a>
                @endif
                @if($question->has_sub_questions && !$question->parent_question_id)
                    <a href="{{ route('admin.questions.create', ['unit' => $question->unit_id, 'parent' => $question->id]) }}" class="btn btn-outline-info">
                        <i class="bi bi-plus-circle me-2"></i>Add Sub-Question
                    </a>
                @endif
                <a href="{{ route('admin.units.show', $question->unit) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Unit
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Render KaTeX formulas in quill content areas
    document.querySelectorAll('.quill-content').forEach(function(element) {
        renderMathInElement(element, {
            delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false},
                {left: '\\(', right: '\\)', display: false},
                {left: '\\[', right: '\\]', display: true}
            ],
            throwOnError: false,
            trust: true
        });
    });

    // Also render any .ql-formula elements
    document.querySelectorAll('.ql-formula').forEach(function(element) {
        try {
            const formula = element.getAttribute('data-value') || element.textContent;
            if (formula) {
                katex.render(formula, element, { throwOnError: false });
            }
        } catch (e) {
            console.warn('KaTeX render error:', e);
        }
    });
});

// Delete sub-question function
function deleteSubQuestion(subQuestionId, subQuestionLetter) {
    if (!confirm(`Are you sure you want to delete sub-question "${subQuestionLetter}"?\n\nThis action cannot be undone.`)) {
        return;
    }

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/questions/${subQuestionId}`;
    form.style.display = 'none';

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    // Add DELETE method
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
