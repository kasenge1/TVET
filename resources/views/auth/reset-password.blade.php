@extends('layouts.guest')

@section('title', 'Reset Password - TVET Revision')

@section('main')
<div class="auth-header">
    <h2>Reset Password</h2>
    <p>Enter your new password below</p>
</div>

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Address -->
    <div class="mb-4">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-with-icon">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', $request->email) }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-4">
        <label for="password" class="form-label">New Password</label>
        <div class="input-with-icon password-field">
            <i class="bi bi-lock input-icon"></i>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   required
                   autocomplete="new-password"
                   placeholder="Enter your new password">
            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <small class="text-muted">Minimum 8 characters</small>
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-with-icon password-field">
            <i class="bi bi-lock input-icon"></i>
            <input type="password"
                   class="form-control"
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   autocomplete="new-password"
                   placeholder="Confirm your new password">
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password_confirmation')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-shield-check me-2"></i>Reset Password
        </button>
    </div>

    <div class="auth-footer">
        <span>Remember your password?</span>
        <a href="{{ route('login') }}" class="ms-1">Sign in</a>
    </div>
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
