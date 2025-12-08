@extends('layouts.frontend')

@section('title', $course->title . ' - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Course Header with Progress -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">{{ $course->title }}</h1>
                    <p class="mb-2 opacity-90">
                        @if($course->level_display)
                            <span class="badge bg-white bg-opacity-25 me-2">{{ $course->level_display }}</span>
                        @endif
                        <span>{{ $course->units->count() }} Units</span>
                        <span class="mx-2">&bull;</span>
                        <span>{{ $totalQuestions }} Questions</span>
                    </p>
                    <!-- Progress Bar -->
                    <div class="d-flex align-items-center mt-3">
                        <div class="progress flex-grow-1 me-3" style="height: 8px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                        <span class="small fw-medium">{{ $progress['percentage'] }}%</span>
                    </div>
                    <small class="opacity-75">{{ $progress['viewed'] }} of {{ $progress['total'] }} questions reviewed</small>
                </div>
                <div class="mt-3 mt-md-0 ms-md-3 d-flex flex-column gap-2">
                    @if($lastViewed)
                        @php
                            $allViewed = $progress['viewed'] >= $progress['total'];
                        @endphp
                        <a href="{{ route('learn.question', [$lastViewed['unit']->slug, $lastViewed['question']->slug]) }}" class="btn {{ $allViewed ? 'btn-success' : 'btn-warning' }}">
                            @if($allViewed)
                                <i class="bi bi-arrow-repeat me-1"></i>Review Again
                            @else
                                <i class="bi bi-play-fill me-1"></i>Continue
                            @endif
                        </a>
                    @elseif($course->units->count() > 0 && $totalQuestions > 0)
                        @php
                            $firstUnit = $course->units->first();
                            $firstQuestion = $firstUnit->questions()->mainQuestions()->orderBy('order')->first();
                        @endphp
                        @if($firstQuestion)
                            <a href="{{ route('learn.question', [$firstUnit->slug, $firstQuestion->slug]) }}" class="btn btn-warning">
                                <i class="bi bi-play-fill me-1"></i>Start Learning
                            </a>
                        @endif
                    @endif
                    <a href="{{ route('learn.saved') }}" class="btn btn-light">
                        <i class="bi bi-bookmark-fill me-1"></i>Saved ({{ $savedCount }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Ad Banner -->
    <x-google-ad slot="header" class="mb-4" />

    <!-- Install App Card (for students) -->
    @php
        $pwaEnabled = \App\Models\SiteSetting::pwaEnabled();
        $pwaRequiresSubscription = \App\Models\SiteSetting::pwaRequiresSubscription();
        $subscriptionsEnabled = \App\Models\SiteSetting::subscriptionsEnabled();
        $userIsPremium = auth()->check() && auth()->user()->isPremium();
        $canInstallPwa = !$pwaRequiresSubscription || $userIsPremium;
        // Show install card if: user can install OR subscriptions are enabled (to encourage subscription)
        $showInstallCard = $canInstallPwa || $subscriptionsEnabled;
    @endphp
    @if($pwaEnabled && $showInstallCard)
    <div id="installAppCard" class="card border-0 shadow-sm mb-4" style="display: none; background: linear-gradient(135deg, #198754 0%, #157347 100%);">
        <div class="card-body p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-phone text-white fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 text-white">
                    @if($canInstallPwa)
                        <h6 class="mb-1 fw-bold">Study Offline Anytime!</h6>
                        <p class="mb-0 small opacity-90">Install the app for quick access & offline study</p>
                    @else
                        <h6 class="mb-1 fw-bold">Want Offline Access?</h6>
                        <p class="mb-0 small opacity-90">Go Premium to download the app & study anywhere</p>
                    @endif
                </div>
                <div class="flex-shrink-0 ms-2">
                    @if($canInstallPwa)
                        <button type="button" class="btn btn-light btn-sm" onclick="installApp()">
                            <i class="bi bi-download me-1"></i>Install
                        </button>
                    @else
                        <a href="{{ route('learn.subscription') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-star-fill me-1"></i>Premium
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        // Show install card only when app is installable
        window.addEventListener('beforeinstallprompt', function() {
            const card = document.getElementById('installAppCard');
            if (card) {
                card.style.display = 'block';
            }
        });
    </script>
    @endif

    <!-- Units Section -->
    <div class="mb-4">
        <h2 class="h5 fw-bold mb-3">
            @if($progress['percentage'] > 0)
                Continue Revising
            @else
                Select a Unit to Start Revising
            @endif
        </h2>

        <div class="row g-3">
            @forelse($course->units as $index => $unit)
                @php
                    $unitProg = $unitProgress[$unit->id] ?? ['viewed' => 0, 'total' => 0, 'percentage' => 0];
                    $isCompleted = $unitProg['percentage'] == 100;
                    $isStarted = $unitProg['percentage'] > 0;
                @endphp
                <div class="col-12">
                    <a href="{{ route('learn.unit', $unit->slug) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 unit-card {{ $isCompleted ? 'border-success' : '' }}">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="{{ $isCompleted ? 'bg-success' : ($isStarted ? 'bg-primary' : 'bg-secondary') }} text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" style="width: 45px; height: 45px;">
                                        @if($isCompleted)
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="h6 fw-bold mb-1 text-dark">{{ $unit->title }}</h3>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 4px; max-width: 120px;">
                                                <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-primary' }}" style="width: {{ $unitProg['percentage'] }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $unitProg['viewed'] }}/{{ $unitProg['total'] }}</span>
                                        </div>
                                    </div>
                                    <div class="ms-2 d-flex align-items-center">
                                        @if($isCompleted)
                                            <span class="badge bg-success-subtle text-success me-2">Complete</span>
                                        @elseif($isStarted)
                                            <span class="badge bg-primary-subtle text-primary me-2">{{ $unitProg['percentage'] }}%</span>
                                        @endif
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-folder-x display-4 text-muted mb-3"></i>
                            <h4 class="text-muted">No Units Available</h4>
                            <p class="text-muted mb-0">Units will appear here once they are added to this course.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Ad Banner -->
    <x-google-ad slot="content" class="mb-4" />
</div>
@endsection

@push('styles')
<style>
    .unit-card {
        transition: all 0.2s ease;
    }
    .unit-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
</style>
@endpush
