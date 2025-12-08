@extends('layouts.guest')

@section('title', 'Verify Email - TVET Revision')

@section('main')
<div class="auth-header">
    <div class="d-flex justify-content-center mb-3">
        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
            <i class="bi bi-envelope-check text-primary" style="font-size: 2.5rem;"></i>
        </div>
    </div>
    <h2>Verify Your Email</h2>
    <p>We've sent a verification link to your email</p>
</div>

<div class="text-center mb-4">
    <p class="text-muted">
        Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to <strong>{{ auth()->user()->email }}</strong>.
    </p>
    <p class="text-muted small">
        If you didn't receive the email, check your spam folder or click below to resend.
    </p>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle me-2"></i>
        A new verification link has been sent to your email address.
    </div>
@endif

<form method="POST" action="{{ route('verification.send') }}" class="mb-3">
    @csrf
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-envelope me-2"></i>Resend Verification Email
        </button>
    </div>
</form>

<div class="text-center">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link text-muted">
            <i class="bi bi-box-arrow-left me-1"></i>Log out and try another account
        </button>
    </form>
</div>

<div class="mt-4 pt-4 border-top">
    <div class="text-center">
        <p class="text-muted small mb-2">Need help?</p>
        <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-question-circle me-1"></i>Contact Support
        </a>
    </div>
</div>
@endsection
