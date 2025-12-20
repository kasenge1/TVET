@extends('layouts.frontend')

@section('title', 'Question ' . ($currentIndex + 1) . ' - ' . $unit->title . ' - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">{{ $course->title }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('learn.unit', $unit->slug) }}" class="text-decoration-none">{{ $unit->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Question {{ $currentIndex + 1 }}</li>
        </ol>
    </nav>

    <!-- Navigation Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <!-- Progress Bar -->
            <div class="progress mb-3" style="height: 6px;">
                <div class="progress-bar bg-primary" role="progressbar"
                     style="width: {{ (($currentIndex + 1) / $allQuestions->count()) * 100 }}%"
                     aria-valuenow="{{ $currentIndex + 1 }}"
                     aria-valuemin="0"
                     aria-valuemax="{{ $allQuestions->count() }}">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if($prev)
                    <a href="{{ route('learn.question', [$unit->slug, $prev->slug]) }}" class="btn btn-outline-primary btn-sm" id="prevBtn" title="Previous (Left Arrow)">
                        <i class="bi bi-chevron-left me-1"></i><span class="d-none d-sm-inline">Previous</span>
                    </a>
                @else
                    <span class="btn btn-outline-secondary btn-sm disabled">
                        <i class="bi bi-chevron-left me-1"></i><span class="d-none d-sm-inline">Previous</span>
                    </span>
                @endif

                <!-- Question Picker Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                        <strong>{{ $currentIndex + 1 }}</strong> / {{ $allQuestions->count() }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-center question-picker-menu" style="max-height: 300px; overflow-y: auto; width: 200px;">
                        <div class="px-3 py-2 border-bottom">
                            <small class="text-muted fw-bold">Jump to Question</small>
                        </div>
                        <div class="question-picker-grid p-2">
                            @foreach($allQuestions as $index => $q)
                                <a href="{{ route('learn.question', [$unit->slug, $q->slug]) }}"
                                   class="question-picker-item {{ $index === $currentIndex ? 'active' : '' }}"
                                   title="Question {{ $index + 1 }}">
                                    {{ $index + 1 }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($next)
                    <a href="{{ route('learn.question', [$unit->slug, $next->slug]) }}" class="btn btn-outline-primary btn-sm" id="nextBtn" title="Next (Right Arrow)">
                        <span class="d-none d-sm-inline">Next</span><i class="bi bi-chevron-right ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('learn.unit', $unit->slug) }}" class="btn btn-success btn-sm" title="Back to Unit">
                        <i class="bi bi-check-lg me-1"></i><span class="d-none d-sm-inline">Done</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Question Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary me-2">{{ $unit->title }}</span>
                            @if($question->period_question_number)
                                <span class="badge bg-secondary">Q{{ $question->period_question_number }}</span>
                            @elseif($question->question_number)
                                <span class="badge bg-secondary">Q{{ $question->question_number }}</span>
                            @endif
                            @if($question->has_sub_questions && $question->subQuestions->count() > 0)
                                <span class="badge bg-info">{{ $question->subQuestions->count() }} parts</span>
                            @endif
                        </div>
                        <button class="btn btn-sm bookmark-btn {{ $isSaved ? 'btn-warning' : 'btn-outline-warning' }}"
                                data-question-id="{{ $question->id }}"
                                id="saveBtn">
                            <i class="bi bi-bookmark-fill me-1"></i>
                            <span id="saveBtnText">{{ $isSaved ? 'Saved' : 'Save' }}</span>
                        </button>
                    </div>

                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Question</h6>

                    @if($question->isVideoQuestion() && $question->youtube_embed_url)
                        <!-- Video Question -->
                        <div class="video-question mb-4">
                            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                                <iframe src="{{ $question->youtube_embed_url }}"
                                        title="Video Question"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <small>This video contains both the question and answer. Watch the full video to see the solution.</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Text Question -->
                        <div class="question-content mb-4" style="font-size: 1rem; line-height: 1.7;">
                            {!! $question->question_text !!}
                        </div>
                    @endif

                    @if($question->question_images && count($question->question_images) > 0)
                        <div class="question-images mb-4">
                            <div class="row g-2">
                                @foreach($question->question_images as $image)
                                    <div class="col-md-6">
                                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="Question Image"
                                                 class="img-fluid rounded border"
                                                 style="max-height: 300px; cursor: zoom-in;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ad Banner -->
            <x-google-ad slot="content" class="mb-4" />

            <!-- Sub-Questions Section (if this is a parent question with sub-questions) -->
            @if($question->subQuestions && $question->subQuestions->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-primary fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-list-ol me-1"></i>Sub-Questions & Answers
                        </h6>

                        @foreach($question->subQuestions as $index => $subQuestion)
                            @php
                                $subLetter = chr(97 + $index); // a, b, c...
                                $parentNum = $question->period_question_number ?? ($currentIndex + 1);
                            @endphp
                            <div class="sub-question-item mb-4 {{ !$loop->last ? 'pb-4 border-bottom' : '' }}">
                                <!-- Sub-question -->
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                            {{ $subLetter }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="question-content" style="font-size: 1rem; line-height: 1.7;">
                                            {!! $subQuestion->question_text !!}
                                        </div>

                                        @if($subQuestion->question_images && count($subQuestion->question_images) > 0)
                                            <div class="question-images mt-3">
                                                <div class="row g-2">
                                                    @foreach($subQuestion->question_images as $image)
                                                        <div class="col-md-6">
                                                            <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $image) }}"
                                                                     alt="Question Image"
                                                                     class="img-fluid rounded border"
                                                                     style="max-height: 200px; cursor: zoom-in;">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Sub-question Answer -->
                                <div class="ms-5 ps-2 border-start border-success border-3 bg-light rounded-end p-3">
                                    <small class="text-uppercase text-success fw-bold d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Answer ({{ $subLetter }})
                                    </small>
                                    @if($subQuestion->answer_text)
                                        <div class="answer-content" style="font-size: 0.95rem; line-height: 1.6;">
                                            {!! $subQuestion->answer_text !!}
                                        </div>
                                    @else
                                        <div class="text-muted small">
                                            <i class="bi bi-hourglass-split me-1"></i>Answer coming soon...
                                        </div>
                                    @endif

                                    @if($subQuestion->answer_images && count($subQuestion->answer_images) > 0)
                                        <div class="answer-images mt-3">
                                            <div class="row g-2">
                                                @foreach($subQuestion->answer_images as $image)
                                                    <div class="col-md-6">
                                                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                 alt="Answer Image"
                                                                 class="img-fluid rounded border"
                                                                 style="max-height: 200px; cursor: zoom-in;">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($subQuestion->ai_generated)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-robot me-1"></i>AI generated
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Answer Card (for questions without sub-questions) -->
                @if($question->isVideoQuestion())
                    <!-- Video questions: Answer is included in the video -->
                    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #28a745 !important;">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-success fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="bi bi-check-circle-fill me-1"></i>Answer
                            </h6>
                            <div class="text-center py-3">
                                <div class="mb-3">
                                    <i class="bi bi-play-circle text-success" style="font-size: 3rem;"></i>
                                </div>
                                <p class="mb-2 fw-medium">The answer is included in the video above</p>
                                <p class="text-muted small mb-0">Watch the complete video to see the full explanation and solution.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Text questions: Show answer text -->
                    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #28a745 !important;">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-success fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="bi bi-check-circle-fill me-1"></i>Answer
                            </h6>

                            @if($question->answer_text)
                                <div class="answer-content" style="font-size: 1rem; line-height: 1.7;">
                                    {!! $question->answer_text !!}
                                </div>
                            @else
                                <div class="text-muted text-center py-4">
                                    <i class="bi bi-hourglass-split display-4 mb-3"></i>
                                    <p class="mb-0">Answer coming soon...</p>
                                </div>
                            @endif

                            @if($question->answer_images && count($question->answer_images) > 0)
                                <div class="answer-images mt-4">
                                    <div class="row g-2">
                                        @foreach($question->answer_images as $image)
                                            <div class="col-md-6">
                                                <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                         alt="Answer Image"
                                                         class="img-fluid rounded border"
                                                         style="max-height: 300px; cursor: zoom-in;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($question->ai_generated)
                                <div class="mt-4 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-robot me-1"></i>This answer was generated with AI assistance
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            <!-- Ad Banner -->
            <x-google-ad slot="content" class="mb-4" />

            <!-- Bottom Navigation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        @if($prev)
                            <a href="{{ route('learn.question', [$unit->slug, $prev->slug]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-chevron-left me-1"></i>Previous Question
                            </a>
                        @else
                            <span></span>
                        @endif

                        <a href="{{ route('learn.unit', $unit->slug) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i>All Questions
                        </a>

                        @if($next)
                            <a href="{{ route('learn.question', [$unit->slug, $next->slug]) }}" class="btn btn-primary">
                                Next Question<i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('learn.index') }}" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i>Unit Complete
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">Question Info</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Unit</span>
                        <span class="fw-medium">{{ $unit->title }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Type</span>
                        @if($question->isVideoQuestion())
                            <span class="badge bg-danger"><i class="bi bi-youtube me-1"></i>Video</span>
                        @else
                            <span class="badge bg-primary"><i class="bi bi-file-text me-1"></i>Text</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Views</span>
                        <span class="fw-medium">{{ $question->view_count ?? 0 }}</span>
                    </div>
                    @if($question->ai_generated && !$question->isVideoQuestion())
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Answer Source</span>
                            <span class="badge bg-info">AI Generated</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">Quick Actions</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('learn.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-house me-2"></i>My Course
                        </a>
                        <a href="{{ route('learn.unit', $unit->slug) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-list-ul me-2"></i>Unit Questions
                        </a>
                        <a href="{{ route('learn.saved') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-bookmark-fill me-2"></i>Saved Questions
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ad -->
            <x-google-ad slot="sidebar" />
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveBtn');
    const saveBtnText = document.getElementById('saveBtnText');

    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const questionId = this.dataset.questionId;

            fetch(`/learn/bookmark/${questionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.saved) {
                    saveBtn.classList.remove('btn-outline-warning');
                    saveBtn.classList.add('btn-warning');
                    saveBtnText.textContent = 'Saved';
                } else {
                    saveBtn.classList.remove('btn-warning');
                    saveBtn.classList.add('btn-outline-warning');
                    saveBtnText.textContent = 'Save';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Don't trigger if user is typing in an input
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        if (e.key === 'ArrowLeft') {
            const prevBtn = document.getElementById('prevBtn');
            if (prevBtn) window.location.href = prevBtn.href;
        } else if (e.key === 'ArrowRight') {
            const nextBtn = document.getElementById('nextBtn');
            if (nextBtn) window.location.href = nextBtn.href;
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .question-content img,
    .answer-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.375rem;
        margin: 0.5rem 0;
    }

    .question-content table,
    .answer-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }

    .question-content table th,
    .question-content table td,
    .answer-content table th,
    .answer-content table td {
        border: 1px solid #dee2e6;
        padding: 0.5rem;
    }

    .question-content ul,
    .question-content ol,
    .answer-content ul,
    .answer-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .question-content li,
    .answer-content li {
        margin-bottom: 0.5rem;
    }

    /* Question Picker Grid */
    .question-picker-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 4px;
    }

    .question-picker-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #495057;
        background: #f8f9fa;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .question-picker-item:hover {
        background: #e9ecef;
        color: #0d6efd;
    }

    .question-picker-item.active {
        background: #0d6efd;
        color: #fff;
    }

    .dropdown-menu-center {
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
</style>
@endpush
