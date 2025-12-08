@extends('layouts.guest')

@section('title', 'Forgot Password - TVET Revision')

@section('main')
<div class="auth-header">
    <h2>Forgot Password</h2>
    <p>Enter your email to receive a password reset link</p>
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success mb-4">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-with-icon">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- reCAPTCHA -->
    <x-recaptcha form="password_reset" />

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-envelope me-2"></i>Send Reset Link
        </button>
    </div>

    <div class="auth-footer">
        <span>Remember your password?</span>
        <a href="{{ route('login') }}" class="ms-1">Sign in</a>
    </div>
</form>
@endsection
