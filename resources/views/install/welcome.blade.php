@extends('install.layout')

@section('title', 'Welcome')
@section('step1-class', 'active')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-3">Welcome to TVET Revision</h4>
    <p class="text-muted">
        Thank you for choosing TVET Revision! This wizard will guide you through the installation process.
        It should only take a few minutes.
    </p>
</div>

<div class="card border-0 bg-light mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Before You Begin</h6>
        <ul class="mb-0 text-muted small">
            <li class="mb-2">Make sure you have created a MySQL database for this application</li>
            <li class="mb-2">Have your database credentials ready (host, username, password)</li>
            <li class="mb-2">Ensure PHP 8.2 or higher is installed on your server</li>
            <li class="mb-2">The following directories should be writable: <code>storage/</code>, <code>bootstrap/cache/</code></li>
        </ul>
    </div>
</div>

<div class="card border-0 bg-light mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-star text-warning me-2"></i>Features Included</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-start">
                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                    <span class="small">Course & Unit Management</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start">
                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                    <span class="small">Question Bank System</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start">
                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                    <span class="small">Student Learning Portal</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start">
                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                    <span class="small">M-Pesa Payment Integration</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start">
                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                    <span class="small">Blog & SEO Tools</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start">
                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                    <span class="small">Admin Dashboard & Analytics</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-grid">
    <a href="{{ route('install.requirements') }}" class="btn btn-install btn-lg">
        <i class="bi bi-arrow-right me-2"></i>Let's Get Started
    </a>
</div>
@endsection
