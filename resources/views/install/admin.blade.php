@extends('install.layout')

@section('title', 'Create Admin Account')
@section('step1-class', 'completed')
@section('step2-class', 'completed')
@section('step3-class', 'completed')
@section('step4-class', 'completed')
@section('step5-class', 'active')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-3">Create Admin Account</h4>
    <p class="text-muted">
        Create your administrator account. You'll use these credentials to log in.
    </p>
</div>

<form action="{{ route('install.admin.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="admin_name" class="form-label">Full Name</label>
        <input type="text"
               class="form-control @error('admin_name') is-invalid @enderror"
               id="admin_name"
               name="admin_name"
               value="{{ old('admin_name') }}"
               placeholder="John Doe"
               required>
        @error('admin_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="admin_email" class="form-label">Email Address</label>
        <input type="email"
               class="form-control @error('admin_email') is-invalid @enderror"
               id="admin_email"
               name="admin_email"
               value="{{ old('admin_email') }}"
               placeholder="admin@example.com"
               required>
        @error('admin_email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">You'll use this email to log in.</div>
    </div>

    <div class="mb-3">
        <label for="admin_password" class="form-label">Password</label>
        <input type="password"
               class="form-control @error('admin_password') is-invalid @enderror"
               id="admin_password"
               name="admin_password"
               placeholder="Minimum 8 characters"
               required
               minlength="8">
        @error('admin_password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
        <input type="password"
               class="form-control"
               id="admin_password_confirmation"
               name="admin_password_confirmation"
               placeholder="Repeat password"
               required
               minlength="8">
    </div>

    <div class="alert alert-warning small">
        <i class="bi bi-shield-lock me-2"></i>
        <strong>Important:</strong> Save these credentials securely. You won't be able to recover them through the installer.
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('install.application') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
        <button type="submit" class="btn btn-install">
            Continue<i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
</form>
@endsection
