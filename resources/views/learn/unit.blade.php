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
                        @if($unit->exam_period)
                            <span class="badge bg-white bg-opacity-25 me-2">
                                <i class="bi bi-calendar-event me-1"></i>{{ $unit->exam_period }}
                            </span>
                        @endif
                        <i class="bi bi-question-circle me-1"></i>{{ $questions->total() }} Questions
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
                                        {{ $question->question_number ?: ($questions->firstItem() + $index) }}
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

                                @if($question->question_images && count($question->question_images) > 0)
                                    <div class="mb-2">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="bi bi-image me-1"></i>Has Images
                                        </span>
                                    </div>
                                @endif

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
</style>
@endpush
