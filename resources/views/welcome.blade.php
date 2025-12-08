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
@endphp

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container position-relative py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-3">Kenya KNEC TVET Exam Preparation Made Simple</h1>
                <p class="lead mb-4 opacity-90">Master your KNEC exams with past papers, detailed answers, and progress tracking. Study smarter, not harder.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('courses.index') }}" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-book me-2"></i>Browse Courses
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Start Free
                    </a>
                    @endguest
                </div>
                <div class="d-flex gap-4 mt-4 pt-2">
                    <div class="text-center">
                        <div class="fs-3 fw-bold">{{ \App\Models\Course::where('is_published', true)->count() }}+</div>
                        <small class="opacity-75">Courses</small>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold">{{ \App\Models\Question::count() }}+</div>
                        <small class="opacity-75">Questions</small>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold">{{ \App\Models\User::where('role', 'student')->count() }}+</div>
                        <small class="opacity-75">Students</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <div class="bg-white bg-opacity-10 rounded-4 p-4" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-mortarboard-fill display-1 text-white opacity-75"></i>
                        <div class="row g-3 mt-3">
                            <div class="col-6">
                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-journal-check fs-2 mb-2 d-block"></i>
                                    <small>Past Papers</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-lightbulb fs-2 mb-2 d-block"></i>
                                    <small>Expert Answers</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-graph-up-arrow fs-2 mb-2 d-block"></i>
                                    <small>Track Progress</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-phone fs-2 mb-2 d-block"></i>
                                    <small>Mobile Ready</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Why Choose TVET Revision?</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Everything you need to succeed in your TVET examinations, all in one place.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper mb-3">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="bi bi-collection"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">Extensive Question Bank</h5>
                        <p class="text-muted small mb-0">Access thousands of past exam questions organized by course and unit.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper mb-3">
                            <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">Expert Answers</h5>
                        <p class="text-muted small mb-0">Get detailed, accurate answers to help you understand concepts better.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper mb-3">
                            <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                                <i class="bi bi-phone"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">Mobile Friendly</h5>
                        <p class="text-muted small mb-0">Study anywhere, anytime with our mobile-optimized platform.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper mb-3">
                            <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                                <i class="bi bi-bookmark-star"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">Smart Bookmarks</h5>
                        <p class="text-muted small mb-0">Save important questions and create your personalized study lists.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses Section -->
@php
    $popularCourses = \App\Models\Course::where('is_published', true)
        ->withCount(['units', 'enrollments'])
        ->with('levelRelation')
        ->orderByDesc('enrollments_count')
        ->take(3)
        ->get();
@endphp

@if($popularCourses->count() > 0)
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Most Enrolled Courses</h2>
                <p class="text-muted mb-0">Join thousands of students in our top courses</p>
            </div>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary d-none d-md-inline-flex">
                View All Courses <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($popularCourses as $course)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    @if($course->thumbnail_url)
                    <img src="{{ asset('storage/' . $course->thumbnail_url) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 160px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 160px;">
                        <i class="bi bi-book text-primary display-4"></i>
                    </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            @if($course->level_display)
                            <span class="badge bg-primary bg-opacity-10 text-primary">{{ $course->level_display }}</span>
                            @else
                            <span></span>
                            @endif
                            <span class="text-muted small"><i class="bi bi-collection me-1"></i>{{ $course->units_count }} Units</span>
                        </div>
                        <h5 class="card-title fw-bold mb-2 course-title">{{ Str::limit($course->title, 50) }}</h5>
                        <p class="card-text text-muted small mb-0 course-description flex-grow-1">{{ Str::limit(strip_tags($course->description), 60) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100">
                            <i class="bi bi-eye me-2"></i>View Course
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
                View All Courses <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- How It Works Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">How It Works</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Get started in just a few simple steps</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="step-number-wrapper mb-3">
                            <div class="step-number">1</div>
                        </div>
                        <h5 class="fw-bold mb-2">Create Account</h5>
                        <p class="text-muted small mb-0">Sign up for free and set up your student profile in seconds.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="step-number-wrapper mb-3">
                            <div class="step-number">2</div>
                        </div>
                        <h5 class="fw-bold mb-2">Choose Your Course</h5>
                        <p class="text-muted small mb-0">Browse and enroll in your TVET course to access all questions.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="step-number-wrapper mb-3">
                            <div class="step-number">3</div>
                        </div>
                        <h5 class="fw-bold mb-2">Start Learning</h5>
                        <p class="text-muted small mb-0">Access questions, study answers, and track your progress.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 text-center position-relative">
        <h2 class="fw-bold mb-3">Ready to Ace Your Exams?</h2>
        <p class="lead mb-4 opacity-90 mx-auto" style="max-width: 600px;">Join thousands of students preparing smarter with TVET Revision.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
            <i class="bi bi-rocket-takeoff me-2"></i>Get Started Free
        </a>
        @else
        <a href="{{ route('learn.index') }}" class="btn btn-light btn-lg px-5">
            <i class="bi bi-book me-2"></i>Continue Learning
        </a>
        @endguest
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Feature Cards */
    .feature-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1);
    }

    .feature-icon-wrapper {
        position: relative;
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: all 0.3s ease;
    }

    /* Step Cards */
    .step-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
    }

    .step-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
    }

    .step-card:hover .step-number {
        transform: scale(1.1);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .step-number-wrapper {
        position: relative;
    }

    .step-number {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    /* Hover lift effect for course cards */
    .hover-lift {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
    }

    /* Course cards consistent sizing */
    .course-title {
        min-height: 48px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-description {
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Mobile responsive styles for homepage */
    @media (max-width: 767.98px) {
        .feature-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
            border-radius: 12px;
        }

        .step-number {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }

        .course-title {
            min-height: 36px;
            font-size: 0.9rem;
        }

        .course-description {
            min-height: 28px;
            font-size: 0.75rem;
        }

        /* Hero feature boxes */
        .hero-gradient .bg-white.bg-opacity-10.rounded-3.p-3 {
            padding: 0.625rem !important;
        }

        .hero-gradient .bg-white.bg-opacity-10.rounded-3.p-3 i {
            font-size: 1.25rem !important;
        }

        .hero-gradient .bg-white.bg-opacity-10.rounded-3.p-3 small {
            font-size: 0.65rem;
        }

        /* Hero stats */
        .d-flex.gap-4.mt-4.pt-2 {
            gap: 1rem !important;
        }

        .d-flex.gap-4.mt-4.pt-2 .text-center .fs-3 {
            font-size: 1.1rem !important;
        }

        .d-flex.gap-4.mt-4.pt-2 .text-center small {
            font-size: 0.65rem;
        }
    }

    @media (max-width: 575.98px) {
        .feature-icon {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }

        .step-number {
            width: 42px;
            height: 42px;
            font-size: 1rem;
        }

        .feature-card .card-body,
        .step-card .card-body {
            padding: 0.875rem !important;
        }

        .feature-card h5,
        .step-card h5 {
            font-size: 0.85rem;
        }

        .feature-card p,
        .step-card p {
            font-size: 0.7rem;
        }

        /* Hero decorative box hidden on very small screens */
        .hero-gradient .col-lg-6.text-center .position-relative {
            display: none;
        }
    }
</style>
@endpush
