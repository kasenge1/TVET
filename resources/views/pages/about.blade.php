@extends('layouts.frontend')

@section('title', 'About Us - TVET Revision')
@section('description', 'Learn about TVET Revision, your trusted platform for TVET exam preparation in Kenya.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container text-center" style="padding: 3.5rem 0;">
        <h1 class="fe-hero-title" style="font-size: 2.5rem;">About TVET Revision</h1>
        <p class="fe-hero-subtitle mx-auto" style="max-width: 600px;">Empowering TVET students across Kenya to achieve academic excellence through comprehensive exam preparation resources.</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="fe-section bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="fe-section-label">Our Story</span>
                <h2 class="fe-section-title">Bridging the Gap in TVET Education</h2>
                <p style="color: var(--fe-text-secondary); line-height: 1.8; margin-bottom: 1rem;">TVET Revision was born from a simple observation: TVET students in Kenya deserve better access to quality exam preparation materials.</p>
                <p style="color: var(--fe-text-secondary); line-height: 1.8; margin-bottom: 1rem;">We noticed that while university students had abundant resources, TVET students often struggled to find comprehensive past papers and study materials organized in an accessible way.</p>
                <p style="color: var(--fe-text-secondary); line-height: 1.8; margin-bottom: 0;">Our platform bridges this gap by providing a structured, easy-to-use repository of past exam questions with detailed answers, helping students prepare effectively for their KNEC examinations.</p>
            </div>
            <div class="col-lg-6">
                <div class="fe-sidebar-card text-center" style="background: var(--fe-bg); padding: 2.5rem;">
                    <i class="bi bi-mortarboard-fill display-1 mb-3" style="color: var(--fe-primary);"></i>
                    <h4 class="fw-bold mb-3">Our Mission</h4>
                    <p style="color: var(--fe-text-secondary); line-height: 1.8; margin-bottom: 0;">To democratize access to quality TVET exam preparation resources and help every student achieve their academic goals.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="fe-section" style="background: var(--fe-bg);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="fe-section-label">Our Values</span>
            <h2 class="fe-section-title">The Principles That Guide Us</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="fe-feature-card text-center">
                    <div class="fe-feature-icon fe-feature-icon-blue mx-auto"><i class="bi bi-star"></i></div>
                    <h5 class="fe-feature-title">Quality</h5>
                    <p class="fe-feature-text">We ensure all our content is accurate, relevant, and aligned with KNEC standards.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fe-feature-card text-center">
                    <div class="fe-feature-icon fe-feature-icon-green mx-auto"><i class="bi bi-people"></i></div>
                    <h5 class="fe-feature-title">Accessibility</h5>
                    <p class="fe-feature-text">Education should be accessible to all. We strive to make our platform affordable and easy to use.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fe-feature-card text-center">
                    <div class="fe-feature-icon fe-feature-icon-amber mx-auto"><i class="bi bi-lightbulb"></i></div>
                    <h5 class="fe-feature-title">Innovation</h5>
                    <p class="fe-feature-text">We continuously improve our platform to provide the best learning experience possible.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="fe-section bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--fe-primary); line-height: 1;">{{ \App\Models\Course::where('is_published', true)->count() }}+</div>
                <p style="color: var(--fe-text-secondary); font-weight: 600; margin-top: 0.25rem;">Courses</p>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--fe-primary); line-height: 1;">{{ \App\Models\Question::count() }}+</div>
                <p style="color: var(--fe-text-secondary); font-weight: 600; margin-top: 0.25rem;">Questions</p>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--fe-primary); line-height: 1;">{{ \App\Models\User::where('role', 'student')->count() }}+</div>
                <p style="color: var(--fe-text-secondary); font-weight: 600; margin-top: 0.25rem;">Students</p>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--fe-primary); line-height: 1;">{{ \App\Models\Unit::count() }}+</div>
                <p style="color: var(--fe-text-secondary); font-weight: 600; margin-top: 0.25rem;">Units</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="fe-cta text-center">
    <div class="container">
        <h2 class="fe-cta-title">Join Our Learning Community</h2>
        <p class="fe-cta-subtitle">Start your journey to exam success today.</p>
        @guest
        <a href="{{ route('register') }}" class="fe-btn fe-btn-white fe-btn-lg">
            <i class="bi bi-person-plus me-2"></i>Get Started Free
        </a>
        @else
        <a href="{{ route('courses.index') }}" class="fe-btn fe-btn-white fe-btn-lg">
            <i class="bi bi-book me-2"></i>Browse Courses
        </a>
        @endguest
    </div>
</section>
@endsection
