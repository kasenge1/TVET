@extends('layouts.frontend')

@section('title', $course->title . ' - TVET Revision')
@section('description', Str::limit(strip_tags($course->description), 160))
@section('keywords', 'TVET, KNEC, ' . $course->title . ', past papers, revision, ' . ($course->level_display ?? ''))

@section('og_title', $course->title . ' - TVET Revision')
@section('og_description', Str::limit(strip_tags($course->description), 160))
@section('og_image', $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : asset('images/og-default.png'))

@section('twitter_title', $course->title . ' - TVET Revision')
@section('twitter_description', Str::limit(strip_tags($course->description), 160))
@section('twitter_image', $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : asset('images/og-default.png'))

@section('canonical', route('courses.show', $course->slug))

@php
$courseSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Course',
    'name' => $course->title,
    'description' => Str::limit(strip_tags($course->description), 200),
    'provider' => [
        '@type' => 'Organization',
        'name' => 'TVET Revision',
        'sameAs' => config('app.url')
    ],
    'hasCourseInstance' => [
        '@type' => 'CourseInstance',
        'courseMode' => 'online'
    ]
];
if ($course->code) {
    $courseSchema['courseCode'] = $course->code;
}
if ($course->level_display) {
    $courseSchema['educationalLevel'] = $course->level_display;
}
@endphp

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($courseSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb fe-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                <li class="breadcrumb-item active">{{ $course->title }}</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                @if($course->level_display)
                <span class="fe-hero-badge mb-3" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">{{ $course->level_display }}</span>
                @endif
                <h1 class="fe-hero-title" style="font-size: 2.5rem;">{{ $course->title }}</h1>
                @if($course->code)
                <p class="mb-3" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Course Code: {{ $course->code }}</p>
                @endif
                <div class="d-flex flex-wrap gap-4 mb-4">
                    <div class="fe-course-stat"><i class="bi bi-collection"></i> {{ $course->units->count() }} Units</div>
                    <div class="fe-course-stat"><i class="bi bi-question-circle"></i> {{ $totalQuestions }} Questions</div>
                </div>
                @auth
                    <a href="{{ route('student.course') }}" class="fe-btn fe-btn-white fe-btn-lg">
                        <i class="bi bi-play-circle me-2"></i>Start Learning
                    </a>
                @else
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="fe-btn fe-btn-white fe-btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Register to Access
                        </a>
                        <a href="{{ route('login') }}" class="fe-btn fe-btn-outline-white fe-btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </a>
                    </div>
                @endauth
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                @if($course->thumbnail_url)
                <img src="{{ asset('storage/' . $course->thumbnail_url) }}" class="img-fluid rounded-4 shadow" alt="{{ $course->title }}" style="max-height: 250px; object-fit: cover;">
                @else
                <div class="d-flex align-items-center justify-content-center rounded-4" style="width: 200px; height: 200px; background: rgba(255,255,255,0.1); backdrop-filter: blur(8px); margin: 0 auto;">
                    <i class="bi bi-book display-1 text-white opacity-75"></i>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Course Content Section -->
<section class="fe-section">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Description -->
                @if($course->description)
                <div class="fe-sidebar-card mb-4">
                    <h4 class="fw-bold mb-3" style="font-size: 1.2rem;">About This Course</h4>
                    <div style="color: var(--fe-text-secondary); line-height: 1.8;">{!! $course->description !!}</div>
                </div>
                @endif

                <!-- Levels and Units List -->
                <div class="fe-sidebar-card">
                    <h4 class="fw-bold mb-4" style="font-size: 1.2rem;">Course Content</h4>

                    @if($course->levels->count() > 0)
                    <div class="accordion fe-accordion" id="levelsAccordion">
                        @foreach($course->levels as $levelIndex => $level)
                        <div class="accordion-item mb-3" style="border-radius: var(--fe-radius) !important; overflow: hidden;">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $levelIndex === 0 ? '' : 'collapsed' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#level{{ $level->id }}" aria-expanded="{{ $levelIndex === 0 ? 'true' : 'false' }}">
                                    <div class="d-flex align-items-center w-100 justify-content-between me-3">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center me-3 fw-bold text-white" style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: var(--fe-primary); font-size: 0.8rem;">
                                                {{ $level->level_number ?? ($levelIndex + 1) }}
                                            </div>
                                            <span>{{ $level->name }}</span>
                                        </div>
                                        <span class="badge rounded-pill me-2" style="background: var(--fe-primary-light); color: var(--fe-primary); font-size: 0.7rem;">
                                            {{ $level->units->count() }} {{ Str::plural('Unit', $level->units->count()) }}
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="level{{ $level->id }}" class="accordion-collapse collapse {{ $levelIndex === 0 ? 'show' : '' }}" data-bs-parent="#levelsAccordion">
                                <div class="accordion-body pt-0">
                                    @if($level->units->count() > 0)
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($level->units as $unitIndex => $unit)
                                        <div class="d-flex align-items-center justify-content-between p-3" style="background: var(--fe-bg); border-radius: var(--fe-radius-sm); border: 1px solid var(--fe-border);">
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 28px; height: 28px; min-width: 28px; border-radius: 6px; background: var(--fe-border); font-size: 0.75rem; color: var(--fe-text-secondary);">
                                                    {{ $unit->unit_number ?? ($unitIndex + 1) }}
                                                </div>
                                                <div>
                                                    <span class="fw-semibold" style="font-size: 0.9rem; color: var(--fe-text);">{{ $unit->title }}</span>
                                                    @if($unit->exam_month && $unit->exam_year)
                                                    <small class="d-block" style="color: var(--fe-text-muted); font-size: 0.75rem;">{{ \App\Models\Unit::MONTHS[$unit->exam_month] ?? '' }} {{ $unit->exam_year }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill" style="background: #ecfdf5; color: #059669; font-size: 0.7rem;">
                                                <i class="bi bi-question-circle me-1"></i>{{ $unit->questions_count }} Questions
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-3">
                                        <p class="mb-0" style="color: var(--fe-text-muted); font-size: 0.85rem;">No units available in this level yet.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="fe-empty-state">
                        <i class="bi bi-inbox d-block"></i>
                        <p>No content available yet.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Stats Card -->
                <div class="fe-sidebar-card mb-4">
                    <h5 class="fe-sidebar-card-title">Course Overview</h5>
                    <div class="fe-stat-row">
                        <span class="fe-stat-row-label"><i class="bi bi-collection"></i> Units</span>
                        <span class="fe-stat-row-value">{{ $course->units->count() }}</span>
                    </div>
                    <div class="fe-stat-row">
                        <span class="fe-stat-row-label"><i class="bi bi-question-circle"></i> Questions</span>
                        <span class="fe-stat-row-value">{{ $totalQuestions }}</span>
                    </div>
                    @if($course->level_display)
                    <div class="fe-stat-row">
                        <span class="fe-stat-row-label"><i class="bi bi-mortarboard"></i> Level</span>
                        <span class="fe-stat-row-value">{{ $course->level_display }}</span>
                    </div>
                    @endif
                    <div class="fe-stat-row" style="border-bottom: none;">
                        <span class="fe-stat-row-label"><i class="bi bi-calendar"></i> Updated</span>
                        <span class="fe-stat-row-value">{{ $course->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="fe-cta-card">
                    <div class="fe-cta-card-icon"><i class="bi bi-rocket-takeoff"></i></div>
                    <h5 class="fw-bold mb-2">Ready to Start?</h5>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.8); margin-bottom: 1rem;">Register now to access all questions and start preparing for your exams.</p>
                    @auth
                        <a href="{{ route('student.course') }}" class="fe-btn fe-btn-white w-100">
                            <i class="bi bi-play-circle me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="fe-btn fe-btn-white w-100">
                            <i class="bi bi-person-plus me-2"></i>Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Other Courses Section -->
@php
    $relatedCourses = \App\Models\Course::where('is_published', true)
        ->where('id', '!=', $course->id)
        ->withCount('units')
        ->with('levels')
        ->take(3)
        ->get();
@endphp

@if($relatedCourses->count() > 0)
<section class="fe-section-sm" style="background: var(--fe-bg);">
    <div class="container">
        <h4 class="fw-bold mb-4" style="font-size: 1.25rem;">Related Courses</h4>
        <div class="row g-4">
            @foreach($relatedCourses as $relatedCourse)
            <div class="col-md-4">
                <div class="fe-card">
                    <div class="fe-card-img">
                        @if($relatedCourse->thumbnail_url)
                        <img src="{{ asset('storage/' . $relatedCourse->thumbnail_url) }}" alt="{{ $relatedCourse->title }}">
                        @else
                        <div class="fe-card-img-placeholder"><i class="bi bi-book"></i></div>
                        @endif
                        @if($relatedCourse->level_display)
                        <span class="fe-card-badge">{{ $relatedCourse->level_display }}</span>
                        @endif
                    </div>
                    <div class="fe-card-body">
                        <h6 class="fe-card-title">{{ $relatedCourse->title }}</h6>
                        <p class="fe-card-desc">{{ $relatedCourse->units_count }} Units</p>
                    </div>
                    <div class="fe-card-footer">
                        <a href="{{ route('courses.show', $relatedCourse) }}" class="fe-btn fe-btn-primary w-100" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
                            View Course
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
