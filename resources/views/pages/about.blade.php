@extends('layouts.frontend')

@section('title', 'About Us - TVET Revision')
@section('description', 'Learn about TVET Revision, your trusted platform for TVET exam preparation in Kenya.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 fw-bold mb-3">About TVET Revision</h1>
                <p class="lead opacity-90">Empowering TVET students across Kenya to achieve academic excellence through comprehensive exam preparation resources.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Our Story</h2>
                <p class="text-muted mb-3">TVET Revision was born from a simple observation: TVET students in Kenya deserve better access to quality exam preparation materials.</p>
                <p class="text-muted mb-3">We noticed that while university students had abundant resources, TVET students often struggled to find comprehensive past papers and study materials organized in an accessible way.</p>
                <p class="text-muted">Our platform bridges this gap by providing a structured, easy-to-use repository of past exam questions with detailed answers, helping students prepare effectively for their KNEC examinations.</p>
            </div>
            <div class="col-lg-6">
                <div class="bg-light rounded-4 p-5 text-center">
                    <i class="bi bi-mortarboard-fill display-1 text-primary mb-3"></i>
                    <h4 class="fw-bold">Our Mission</h4>
                    <p class="text-muted mb-0">To democratize access to quality TVET exam preparation resources and help every student achieve their academic goals.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Our Values</h2>
            <p class="text-muted">The principles that guide everything we do</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                            <i class="bi bi-star"></i>
                        </div>
                        <h5 class="fw-bold">Quality</h5>
                        <p class="text-muted small mb-0">We ensure all our content is accurate, relevant, and aligned with KNEC standards.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto mb-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5 class="fw-bold">Accessibility</h5>
                        <p class="text-muted small mb-0">Education should be accessible to all. We strive to make our platform affordable and easy to use.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <h5 class="fw-bold">Innovation</h5>
                        <p class="text-muted small mb-0">We continuously improve our platform to provide the best learning experience possible.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\Course::where('is_published', true)->count() }}+</div>
                <p class="text-muted fw-medium">Courses</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\Question::count() }}+</div>
                <p class="text-muted fw-medium">Questions</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\User::where('role', 'student')->count() }}+</div>
                <p class="text-muted fw-medium">Students</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\Unit::count() }}+</div>
                <p class="text-muted fw-medium">Units</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 text-center position-relative">
        <h2 class="fw-bold mb-3">Join Our Learning Community</h2>
        <p class="lead mb-4 opacity-90">Start your journey to exam success today.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
            <i class="bi bi-person-plus me-2"></i>Get Started Free
        </a>
        @else
        <a href="{{ route('courses.index') }}" class="btn btn-light btn-lg px-5">
            <i class="bi bi-book me-2"></i>Browse Courses
        </a>
        @endguest
    </div>
</section>
@endsection
