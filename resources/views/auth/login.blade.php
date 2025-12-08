@extends('layouts.guest')

@section('title', 'Sign In - TVET Revision')

@section('main')
<div class="auth-header">
    <h2>Welcome Back</h2>
    <p>Sign in to continue your learning journey</p>
</div>

<form method="POST" action="{{ route('login') }}">
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

    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <div class="input-with-icon password-field">
            <i class="bi bi-lock input-icon"></i>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="Enter your password">
            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label small" for="remember_me">Remember me</label>
        </div>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small" style="color: #667eea; text-decoration: none;">Forgot password?</a>
        @endif
    </div>

    <!-- reCAPTCHA -->
    <x-recaptcha form="login" />

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>
    </div>

    @if (Route::has('register'))
        <div class="auth-footer">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="ms-1">Create one</a>
        </div>
    @endif
</form>
@endsection

@push('styles')
<style>
    .password-field {
        position: relative;
    }
    .password-field .form-control {
        padding-right: 3rem;
    }
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        z-index: 5;
        transition: color 0.2s;
    }
    .password-toggle:hover {
        color: #667eea;
    }
</style>
@endpush

@push('scripts')
<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endpush
