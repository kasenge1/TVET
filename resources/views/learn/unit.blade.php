@extends('layouts.frontend')

@section('title', $unit->title . ' - ' . $course->title . ' - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $unit->title }}</li>
        </ol>
    </nav>

    <!-- Unit Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="flex-grow-1">
                    <h1 class="h4 fw-bold mb-2">{{ $unit->title }}</h1>
                    <p class="mb-2 opacity-90">
                        <i class="bi bi-question-circle me-1"></i>{{ $questions->total() }} Questions
                        @if($selectedPeriod ?? false)
                            <span class="badge bg-white bg-opacity-25 ms-2">
                                <i class="bi bi-funnel me-1"></i>Filtered
                            </span>
                        @endif
                    </p>
                    <!-- Progress Bar -->
                    <div class="d-flex align-items-center mt-3">
                        <div class="progress flex-grow-1 me-3" style="height: 8px; background: rgba(255,255,255,0.3); max-width: 200px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $unitProgress['percentage'] }}%"></div>
                        </div>
                        <span class="small fw-medium">{{ $unitProgress['percentage'] }}%</span>
                    </div>
                    <small class="opacity-75">{{ $unitProgress['viewed'] }} of {{ $unitProgress['total'] }} questions reviewed</small>
                </div>
                <div class="mt-3 mt-md-0 ms-md-3 d-flex flex-column gap-2">
                    @if($lastViewed)
                        @php
                            $allViewed = $unitProgress['viewed'] >= $unitProgress['total'];
                        @endphp
                        <a href="{{ route('learn.question', [$unit->slug, $lastViewed->slug]) }}" class="btn {{ $allViewed ? 'btn-success' : 'btn-warning' }}">
                            @if($allViewed)
                                <i class="bi bi-arrow-repeat me-1"></i>Review Again
                            @else
                                <i class="bi bi-play-fill me-1"></i>Continue
                            @endif
                        </a>
                    @elseif($questions->count() > 0)
                        <a href="{{ route('learn.question', [$unit->slug, $questions->first()->slug]) }}" class="btn btn-warning">
                            <i class="bi bi-play-fill me-1"></i>Start Learning
                        </a>
                    @endif
                    <a href="{{ route('learn.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i>Back to Units
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Ad Banner -->
    <x-google-ad slot="header" class="mb-4" />

    @if(isset($examPeriods) && $examPeriods->count() > 0)
        @if(!($selectedPeriod ?? false))
            <!-- Exam Period Selection - Show when no period is selected -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2 text-primary"></i>Select Exam Period
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">This unit contains questions from multiple exam periods. Please select an exam period to view questions:</p>
                    <div class="row g-3">
                        @foreach($examPeriods as $period)
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('learn.unit', ['unit' => $unit->slug, 'period' => $period['key']]) }}"
                                   class="card border h-100 text-decoration-none exam-period-card">
                                    <div class="card-body text-center py-4">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                                        </div>
                                        <h6 class="mb-1 text-dark">{{ $period['label'] }}</h6>
                                        <small class="text-muted">Click to view questions</small>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Selected Period Header with back option -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary fs-6 me-2">
                                <i class="bi bi-calendar-event me-1"></i>
                                @foreach($examPeriods as $period)
                                    @if($period['key'] === $selectedPeriod)
                                        {{ $period['label'] }}
                                    @endif
                                @endforeach
                            </span>
                            <span class="text-muted">{{ $questions->total() }} questions</span>
                        </div>
                        <a href="{{ route('learn.unit', $unit->slug) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Change Period
                        </a>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            @if($questions->count() > 0)
                <div class="mb-4">
                    @foreach($questions as $index => $question)
                        @php
                            $isViewed = in_array($question->id, $viewedIds);
                        @endphp
                        <div class="card border-0 shadow-sm mb-3 question-card {{ $isViewed ? 'border-start border-success border-3' : '' }}">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="{{ $isViewed ? 'bg-success text-white' : 'bg-light text-primary' }} rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                            @if($isViewed)
                                                <i class="bi bi-check-lg"></i>
                                            @else
                                                {{ $questions->firstItem() + $index }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="question-preview" style="font-size: 0.9rem;">
                                                {!! Str::limit(strip_tags($question->question_text), 200) !!}
                                            </div>
                                            <div class="d-flex flex-shrink-0 ms-2 gap-1">
                                                @if($isViewed)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </span>
                                                @endif
                                                @if(in_array($question->id, $savedIds))
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-bookmark-fill"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            @if($question->question_images && count($question->question_images) > 0)
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    <i class="bi bi-image me-1"></i>Has Images
                                                </span>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-eye me-1"></i>{{ $question->view_count ?? 0 }} views
                                            </small>
                                            <a href="{{ route('learn.question', [$unit->slug, $question->slug]) }}" class="btn btn-primary btn-sm">
                                                View Answer <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(($index + 1) % 5 == 0 && $index + 1 < $questions->count())
                            <!-- Ad after every 5 questions -->
                            <x-google-ad slot="content" class="mb-3" />
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($questions->hasPages())
                    <div class="d-flex justify-content-center mb-4">
                        {{ $questions->appends(['period' => $selectedPeriod])->links() }}
                    </div>
                @endif
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-question-circle display-4 text-muted mb-3"></i>
                        <h4 class="text-muted">No Questions Yet</h4>
                        <p class="text-muted mb-0">Questions will appear here once they are added to this exam period.</p>
                    </div>
                </div>
            @endif
        @endif
    @else
        <!-- No exam periods - show all questions directly -->
        @if($questions->count() > 0)
            <div class="mb-4">
                @foreach($questions as $index => $question)
                    @php
                        $isViewed = in_array($question->id, $viewedIds);
                    @endphp
                    <div class="card border-0 shadow-sm mb-3 question-card {{ $isViewed ? 'border-start border-success border-3' : '' }}">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="{{ $isViewed ? 'bg-success text-white' : 'bg-light text-primary' }} rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                        @if($isViewed)
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            {{ $questions->firstItem() + $index }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <div class="question-preview" style="font-size: 0.9rem;">
                                            {!! Str::limit(strip_tags($question->question_text), 200) !!}
                                        </div>
                                        <div class="d-flex flex-shrink-0 ms-2 gap-1">
                                            @if($isViewed)
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </span>
                                            @endif
                                            @if(in_array($question->id, $savedIds))
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        @if($question->question_images && count($question->question_images) > 0)
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="bi bi-image me-1"></i>Has Images
                                            </span>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-eye me-1"></i>{{ $question->view_count ?? 0 }} views
                                        </small>
                                        <a href="{{ route('learn.question', [$unit->slug, $question->slug]) }}" class="btn btn-primary btn-sm">
                                            View Answer <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(($index + 1) % 5 == 0 && $index + 1 < $questions->count())
                        <!-- Ad after every 5 questions -->
                        <x-google-ad slot="content" class="mb-3" />
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            @if($questions->hasPages())
                <div class="d-flex justify-content-center mb-4">
                    {{ $questions->links() }}
                </div>
            @endif
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-question-circle display-4 text-muted mb-3"></i>
                    <h4 class="text-muted">No Questions Yet</h4>
                    <p class="text-muted mb-0">Questions will appear here once they are added to this unit.</p>
                </div>
            </div>
        @endif
    @endif

    <!-- Ad Banner -->
    <x-google-ad slot="content" class="mb-4" />
</div>
@endsection

@push('styles')
<style>
    .question-card {
        transition: all 0.2s ease;
    }
    .question-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .exam-period-card {
        transition: all 0.2s ease;
    }
    .exam-period-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        border-color: var(--bs-primary) !important;
    }
</style>
@endpush
