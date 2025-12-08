@extends('layouts.guest')

@section('title', 'Account Blocked - TVET Revision')

@section('main')
<div class="text-center py-4">
    <div class="mb-4">
        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
            <i class="bi bi-shield-x text-danger" style="font-size: 3rem;"></i>
        </div>
    </div>

    <h2 class="fw-bold text-danger mb-3">Account Blocked</h2>

    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-start mb-4">
        <div class="d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill text-danger me-3 mt-1"></i>
            <div>
                <strong>Access Denied</strong>
                <p class="mb-0 mt-1">Your account has been blocked and you cannot access this website. If you believe this is a mistake, please contact the administrator.</p>
            </div>
        </div>
    </div>

    <div class="bg-light rounded-3 p-4 mb-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-question-circle me-2"></i>Why was my account blocked?</h6>
        <p class="text-muted small mb-0">
            Accounts may be blocked for various reasons including violation of terms of service, suspicious activity, or at the administrator's discretion. For more information about your specific case, please contact the support team.
        </p>
    </div>

    <div class="d-flex flex-column gap-3">
        <a href="{{ route('contact') }}" class="btn btn-primary">
            <i class="bi bi-envelope me-2"></i>Contact Support
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-house me-2"></i>Go to Homepage
        </a>
    </div>

    <p class="text-muted small mt-4 mb-0">
        <i class="bi bi-info-circle me-1"></i>
        Reference your email address when contacting support for faster assistance.
    </p>
</div>
@endsection
