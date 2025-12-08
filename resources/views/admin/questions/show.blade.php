@extends('layouts.admin')

@section('page-header', true)
@section('page-title')
    Question {{ $question->question_number }}
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
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge-modern badge bg-primary fs-6">Q{{ $question->question_number }}</span>
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

            @if($question->isTextQuestion() && $question->answer_text)
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
            @elseif($question->isTextQuestion())
                <div class="border-top pt-4">
                    <div class="text-center py-4 bg-light rounded">
                        <i class="bi bi-x-circle text-warning display-4 d-block mb-2"></i>
                        <p class="text-muted mb-0">No answer provided yet</p>
                    </div>
                </div>
            @endif
        </x-card>

        @if($question->subQuestions->count() > 0)
            <x-card title="Sub-Questions" class="mt-4">
                <div class="list-group list-group-flush">
                    @foreach($question->subQuestions as $subQuestion)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge-modern badge bg-secondary me-2">{{ $subQuestion->question_number }}</span>
                                        @if($subQuestion->ai_generated)
                                            <span class="badge-modern badge bg-info">AI</span>
                                        @endif
                                    </div>
                                    <div class="mb-2 quill-content">{!! $subQuestion->question_text !!}</div>
                                    @if($subQuestion->answer_text)
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> Answer provided
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            <i class="bi bi-x-circle"></i> No answer yet
                                        </small>
                                    @endif
                                </div>
                                <div class="btn-group-modern btn-group btn-group-sm">
                                    <a href="{{ route('admin.questions.show', $subQuestion) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.questions.edit', $subQuestion) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
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
                <div class="text-muted small mb-1">Question Number</div>
                <div class="fw-medium">{{ $question->question_number }}</div>
            </div>
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
                @if(!$question->answer_text)
                    <a href="{{ route('admin.questions.edit', $question) }}#answer" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-2"></i>Add Answer
                    </a>
                @endif
                <a href="{{ route('admin.questions.create', ['unit' => $question->unit_id, 'parent' => $question->id]) }}" class="btn btn-outline-info">
                    <i class="bi bi-arrow-return-right me-2"></i>Add Sub-Question
                </a>
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
</script>
@endpush
