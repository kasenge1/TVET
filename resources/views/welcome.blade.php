@extends('layouts.frontend')

@section('title', 'TVET Revision - Kenya KNEC Past Papers & Exam Preparation')
@section('description', 'Prepare for KNEC TVET exams with past papers, expert answers, and study materials. Access thousands of questions for Diploma and Certificate courses.')
@section('keywords', 'TVET, KNEC, Kenya, past papers, exam preparation, diploma, certificate, technical education, revision, study materials')

@section('og_title', 'TVET Revision - Kenya KNEC Past Papers & Exam Preparation')
@section('og_description', 'Prepare for KNEC TVET exams with past papers, expert answers, and study materials. Access thousands of questions for Diploma and Certificate courses.')

@section('twitter_title', 'TVET Revision - Kenya KNEC Past Papers & Exam Preparation')
@section('twitter_description', 'Prepare for KNEC TVET exams with past papers, expert answers, and study materials. Access thousands of questions for Diploma and Certificate courses.')

@section('canonical', route('home'))

@php
$websiteSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'TVET Revision',
    'url' => config('app.url'),
    'description' => 'Prepare for KNEC TVET exams with past papers, expert answers, and study materials.',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => route('courses.index') . '?search={search_term_string}',
        'query-input' => 'required name=search_term_string'
    ]
];

$heroSettings = \App\Models\SiteSetting::getHeroSettings();
@endphp

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="fe-hero text-white">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-6">
                <div class="fe-hero-content">
                    <span class="fe-hero-badge">
                        <i class="bi bi-mortarboard-fill"></i> Kenya's #1 TVET Revision Platform
                    </span>
                    <h1 class="fe-hero-title">{{ $heroSettings['heading'] }}</h1>
                    <p class="fe-hero-subtitle mb-4">{{ $heroSettings['subheading'] }}</p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('courses.index') }}" class="fe-btn fe-btn-white fe-btn-lg">
                            <i class="bi bi-book me-2"></i>{{ $heroSettings['primary_button_text'] }}
                        </a>
                        @guest
                        <a href="{{ route('register') }}" class="fe-btn fe-btn-outline-white fe-btn-lg">
                            <i class="bi bi-person-plus me-2"></i>{{ $heroSettings['secondary_button_text'] }}
                        </a>
                        @endguest
                    </div>
                    <div class="fe-hero-stats">
                        <div>
                            <div class="fe-hero-stat-number">{{ \App\Models\Course::where('is_published', true)->count() }}+</div>
                            <div class="fe-hero-stat-label">Courses</div>
                        </div>
                        <div class="fe-hero-stat-divider"></div>
                        <div>
                            <div class="fe-hero-stat-number">{{ \App\Models\Question::count() }}+</div>
                            <div class="fe-hero-stat-label">Questions</div>
                        </div>
                        <div class="fe-hero-stat-divider"></div>
                        <div>
                            <div class="fe-hero-stat-number">{{ \App\Models\User::where('role', 'student')->count() }}+</div>
                            <div class="fe-hero-stat-label">Students</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="fe-hero-visual">
                    <div class="fe-hero-card-stack">
                        <div class="fe-hero-card">
                            <div class="fe-hero-card-icon"><i class="bi bi-journal-check"></i></div>
                            <div>
                                <div class="fw-semibold text-white">Past Papers</div>
                                <small class="text-white-50">KNEC exam questions</small>
                            </div>
                        </div>
                        <div class="fe-hero-card">
                            <div class="fe-hero-card-icon"><i class="bi bi-lightbulb"></i></div>
                            <div>
                                <div class="fw-semibold text-white">Expert Answers</div>
                                <small class="text-white-50">Detailed explanations</small>
                            </div>
                        </div>
                        <div class="fe-hero-card">
                            <div class="fe-hero-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <div class="fw-semibold text-white">Track Progress</div>
                                <small class="text-white-50">Monitor your growth</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="fe-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="fe-section-label">Features</span>
            <h2 class="fe-section-title">Why Choose TVET Revision?</h2>
            <p class="fe-section-subtitle mx-auto">Everything you need to succeed in your TVET examinations, all in one place.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="fe-feature-card">
                    <div class="fe-feature-icon fe-feature-icon-blue"><i class="bi bi-collection"></i></div>
                    <h5 class="fe-feature-title">Extensive Question Bank</h5>
                    <p class="fe-feature-text">Access thousands of past exam questions organized by course and unit.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="fe-feature-card">
                    <div class="fe-feature-icon fe-feature-icon-green"><i class="bi bi-check-circle"></i></div>
                    <h5 class="fe-feature-title">Expert Answers</h5>
                    <p class="fe-feature-text">Get detailed, accurate answers to help you understand concepts better.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="fe-feature-card">
                    <div class="fe-feature-icon fe-feature-icon-amber"><i class="bi bi-phone"></i></div>
                    <h5 class="fe-feature-title">Mobile Friendly</h5>
                    <p class="fe-feature-text">Study anywhere, anytime with our mobile-optimized platform.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="fe-feature-card">
                    <div class="fe-feature-icon fe-feature-icon-purple"><i class="bi bi-bookmark-star"></i></div>
                    <h5 class="fe-feature-title">Smart Bookmarks</h5>
                    <p class="fe-feature-text">Save important questions and create your personalized study lists.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses Section -->
@php
    $popularCourses = \App\Models\Course::where('is_published', true)
        ->withCount(['units', 'enrollments'])
        ->with('levels')
        ->orderByDesc('enrollments_count')
        ->take(3)
        ->get();
@endphp

@if($popularCourses->count() > 0)
<section class="fe-section" style="background: var(--fe-bg);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="fe-section-label">Popular</span>
                <h2 class="fe-section-title mb-1">Most Enrolled Courses</h2>
                <p class="text-muted mb-0">Join thousands of students in our top courses</p>
            </div>
            <a href="{{ route('courses.index') }}" class="fe-btn fe-btn-primary d-none d-md-inline-flex">
                View All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($popularCourses as $course)
            <div class="col-md-6 col-lg-4">
                <div class="fe-card">
                    <div class="fe-card-img">
                        @if($course->thumbnail_url)
                        <img src="{{ asset('storage/' . $course->thumbnail_url) }}" alt="{{ $course->title }}">
                        @else
                        <div class="fe-card-img-placeholder"><i class="bi bi-book"></i></div>
                        @endif
                        @if($course->level_display)
                        <span class="fe-card-badge">{{ $course->level_display }}</span>
                        @endif
                    </div>
                    <div class="fe-card-body">
                        <div class="fe-card-meta"><i class="bi bi-collection me-1"></i>{{ $course->units_count }} Units</div>
                        <h5 class="fe-card-title">{{ Str::limit($course->title, 50) }}</h5>
                        <p class="fe-card-desc">{{ Str::limit(strip_tags($course->description), 80) }}</p>
                    </div>
                    <div class="fe-card-footer">
                        <a href="{{ route('courses.show', $course) }}" class="fe-btn fe-btn-primary w-100">
                            View Course <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('courses.index') }}" class="fe-btn fe-btn-outline-white" style="border-color: var(--fe-border); color: var(--fe-primary);">
                View All Courses <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- How It Works Section -->
<section class="fe-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="fe-section-label">Get Started</span>
            <h2 class="fe-section-title">How It Works</h2>
            <p class="fe-section-subtitle mx-auto">Get started in just a few simple steps</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="fe-step-card">
                    <div class="fe-step-number">1</div>
                    <h5 class="fe-step-title">Create Account</h5>
                    <p class="fe-step-text">Sign up for free and set up your student profile in seconds.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fe-step-card">
                    <div class="fe-step-number">2</div>
                    <h5 class="fe-step-title">Choose Your Course</h5>
                    <p class="fe-step-text">Browse and enroll in your TVET course to access all questions.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fe-step-card">
                    <div class="fe-step-number">3</div>
                    <h5 class="fe-step-title">Start Learning</h5>
                    <p class="fe-step-text">Access questions, study answers, and track your progress.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="fe-cta text-center">
    <div class="container">
        <h2 class="fe-cta-title">{{ $heroSettings['cta_heading'] }}</h2>
        <p class="fe-cta-subtitle">{{ $heroSettings['cta_subheading'] }}</p>
        @guest
        <a href="{{ route('register') }}" class="fe-btn fe-btn-white fe-btn-lg">
            <i class="bi bi-rocket-takeoff me-2"></i>Get Started Free
        </a>
        @else
        <a href="{{ route('learn.index') }}" class="fe-btn fe-btn-white fe-btn-lg">
            <i class="bi bi-book me-2"></i>Continue Learning
        </a>
        @endguest
    </div>
</section>
@endsection
