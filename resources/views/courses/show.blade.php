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
<section class="hero-gradient text-white py-5">
    <div class="container position-relative">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" class="text-white opacity-75">Courses</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $course->title }}</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                @if($course->level_display)
                <span class="badge bg-white bg-opacity-25 mb-3">{{ $course->level_display }}</span>
                @endif
                <h1 class="display-5 fw-bold mb-3">{{ $course->title }}</h1>
                @if($course->code)
                <p class="mb-3 opacity-75">Course Code: {{ $course->code }}</p>
                @endif
                <div class="d-flex flex-wrap gap-4 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-collection fs-5 me-2"></i>
                        <span>{{ $course->units->count() }} Units</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-question-circle fs-5 me-2"></i>
                        <span>{{ $totalQuestions }} Questions</span>
                    </div>
                </div>
                @auth
                    <a href="{{ route('student.course') }}" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-play-circle me-2"></i>Start Learning
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-2">
                        <i class="bi bi-person-plus me-2"></i>Register to Access
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </a>
                @endauth
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                @if($course->thumbnail_url)
                <img src="{{ asset('storage/' . $course->thumbnail_url) }}" class="img-fluid rounded-4 shadow" alt="{{ $course->title }}" style="max-height: 250px; object-fit: cover;">
                @else
                <div class="bg-white bg-opacity-10 rounded-4 p-5" style="backdrop-filter: blur(10px);">
                    <i class="bi bi-book display-1 opacity-75"></i>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Course Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Description -->
                @if($course->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">About This Course</h4>
                        <div class="text-muted mb-0">{!! $course->description !!}</div>
                    </div>
                </div>
                @endif

                <!-- Units List -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Course Units</h4>

                        @if($course->units->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($course->units as $index => $unit)
                            <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between" style="background-color: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 36px; height: 36px; min-width: 36px; font-size: 0.9rem;">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="fw-medium text-dark">{{ $unit->title }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-question-circle me-1"></i>{{ $unit->questions_count }} Questions
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                            <p class="text-muted mt-2 mb-0">No units available yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Stats Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Course Overview</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-collection me-2"></i>Units</span>
                                <span class="fw-bold">{{ $course->units->count() }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-question-circle me-2"></i>Questions</span>
                                <span class="fw-bold">{{ $totalQuestions }}</span>
                            </li>
                            @if($course->level_display)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-mortarboard me-2"></i>Level</span>
                                <span class="fw-bold">{{ $course->level_display }}</span>
                            </li>
                            @endif
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted"><i class="bi bi-calendar me-2"></i>Updated</span>
                                <span class="fw-bold">{{ $course->updated_at->format('M d, Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-rocket-takeoff display-4 mb-3"></i>
                        <h5 class="fw-bold mb-2">Ready to Start?</h5>
                        <p class="opacity-90 small mb-3">Register now to access all questions and start preparing for your exams.</p>
                        @auth
                            <a href="{{ route('student.course') }}" class="btn btn-light w-100">
                                <i class="bi bi-play-circle me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light w-100">
                                <i class="bi bi-person-plus me-2"></i>Get Started Free
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Other Courses Section -->
@php
    $relatedCourses = \App\Models\Course::where('is_published', true)
        ->where('id', '!=', $course->id)
        ->when($course->level_id, function($query) use ($course) {
            return $query->where('level_id', $course->level_id);
        })
        ->withCount('units')
        ->with('levelRelation')
        ->take(3)
        ->get();
@endphp

@if($relatedCourses->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h4 class="fw-bold mb-4">Related Courses</h4>
        <div class="row g-4">
            @foreach($relatedCourses as $relatedCourse)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    @if($relatedCourse->thumbnail_url)
                    <img src="{{ asset('storage/' . $relatedCourse->thumbnail_url) }}" class="card-img-top" alt="{{ $relatedCourse->title }}" style="height: 150px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="bi bi-book text-primary display-4"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        @if($relatedCourse->level_display)
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $relatedCourse->level_display }}</span>
                        @endif
                        <h6 class="card-title fw-bold mb-2">{{ $relatedCourse->title }}</h6>
                        <p class="card-text text-muted small">{{ $relatedCourse->units_count }} Units</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('courses.show', $relatedCourse) }}" class="btn btn-outline-primary btn-sm w-100">View Course</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    /* Mobile responsive styles for course show page */
    @media (max-width: 767.98px) {
        /* Hero section stats */
        .d-flex.flex-wrap.gap-4.mb-4 {
            gap: 1rem !important;
        }

        .d-flex.flex-wrap.gap-4.mb-4 span {
            font-size: 0.8rem;
        }

        .d-flex.flex-wrap.gap-4.mb-4 i {
            font-size: 0.9rem !important;
        }

        /* Unit list items */
        .border.rounded-3.p-3 {
            padding: 0.75rem !important;
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem;
        }

        .border.rounded-3.p-3 .d-flex.align-items-center:first-child {
            width: 100%;
        }

        .border.rounded-3.p-3 .bg-primary.rounded-circle {
            width: 28px !important;
            height: 28px !important;
            min-width: 28px !important;
            font-size: 0.75rem !important;
        }

        .border.rounded-3.p-3 .fw-medium {
            font-size: 0.85rem;
        }

        .border.rounded-3.p-3 .badge {
            font-size: 0.65rem;
            padding: 0.35em 0.5em;
        }

        /* Sidebar stats */
        .list-unstyled li {
            font-size: 0.85rem;
            padding: 0.5rem 0 !important;
        }

        /* CTA card */
        .bg-primary.text-white .display-4 {
            font-size: 2rem !important;
        }

        /* Hero buttons stack on mobile */
        .hero-gradient .btn-lg {
            display: block;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .hero-gradient .btn-lg.me-2 {
            margin-right: 0 !important;
        }

        /* Related courses - 2 columns on tablet */
        .col-md-4 {
            width: 50%;
        }

        /* Course code text */
        .opacity-75 {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 575.98px) {
        /* Unit number badge */
        .border.rounded-3.p-3 .bg-primary.rounded-circle {
            width: 24px !important;
            height: 24px !important;
            min-width: 24px !important;
            font-size: 0.7rem !important;
        }

        .border.rounded-3.p-3 .fw-medium {
            font-size: 0.8rem;
        }

        /* Related courses - single column */
        .col-md-4 {
            width: 100%;
        }

        /* Related course card image */
        .card-img-top[style*="height: 150px"] {
            height: 120px !important;
        }
    }
</style>
@endpush
