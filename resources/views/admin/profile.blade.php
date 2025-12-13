@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'My Profile')

@section('main')
<div class="row">
    <div class="col-xl-4">
        <x-card title="Profile Information">
            <div class="text-center mb-4">
                @if(auth()->user()->profile_photo_url)
                    <img src="{{ auth()->user()->profile_photo_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="rounded-circle mb-3"
                         style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width: 120px; height: 120px; font-size: 3rem; font-weight: 600;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
                <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-2">{{ auth()->user()->email }}</p>

                <div class="d-flex gap-2 justify-content-center mb-3">
                    <span class="badge bg-danger">
                        <i class="bi bi-shield-fill me-1"></i>Administrator
                    </span>
                    @if(auth()->user()->subscription_tier === 'premium')
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-star-fill me-1"></i>Premium
                        </span>
                    @endif
                </div>

                @if(auth()->user()->email_verified_at)
                    <div class="text-success small">
                        <i class="bi bi-check-circle-fill"></i> Email Verified
                    </div>
                @endif
            </div>

            <hr>

            <div class="mb-3">
                <div class="text-muted small mb-1">User ID</div>
                <div class="fw-medium">#{{ auth()->user()->id }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Member Since</div>
                <div class="fw-medium">{{ auth()->user()->created_at->format('F d, Y') }}</div>
                <div class="text-muted small">{{ auth()->user()->created_at->diffForHumans() }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Last Updated</div>
                <div class="fw-medium">{{ auth()->user()->updated_at->format('F d, Y') }}</div>
                <div class="text-muted small">{{ auth()->user()->updated_at->diffForHumans() }}</div>
            </div>

            @if(auth()->user()->google_id)
                <div class="mb-3">
                    <div class="text-muted small mb-1">Login Method</div>
                    <div>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-google"></i> Google Account
                        </span>
                    </div>
                </div>
            @endif
        </x-card>

        <x-card title="Quick Stats" class="mt-4">
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted">Total Users</span>
                <span class="fw-bold">{{ \App\Models\User::count() }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted">Total Courses</span>
                <span class="fw-bold">{{ \App\Models\Course::count() }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted">Active Subscriptions</span>
                <span class="fw-bold">{{ \App\Models\Subscription::where('status', 'active')->count() }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Total Questions</span>
                <span class="fw-bold">{{ \App\Models\Question::count() }}</span>
            </div>
        </x-card>
    </div>

    <div class="col-xl-8">
        <x-card title="Update Profile Information">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', auth()->user()->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email', auth()->user()->email) }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Changing your email will require verification</small>
                </div>

                <div class="border-top pt-4 mb-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="Change Password" class="mt-4">
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Leave all fields blank if you don't want to change your password
                </div>

                <div class="mb-4">
                    <label for="current_password" class="form-label fw-medium">Current Password</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password"
                               name="current_password">
                        <button class="btn btn-outline-secondary password-toggle" type="button" data-target="current_password">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-medium">New Password</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password">
                            <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation">
                            <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-shield-check me-2"></i>Change Password
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <x-card title="Account Security" class="border-warning">
            <div class="alert alert-warning mb-0">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-shield-exclamation fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-2">Security Tips</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mb-0 ps-3">
                                    <li>Use a strong, unique password for your account</li>
                                    <li>Never share your login credentials with anyone</li>
                                    <li>Enable two-factor authentication when available</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0 ps-3">
                                    <li>Regularly review your account activity</li>
                                    <li>Log out from shared or public computers</li>
                                    <li>Report any suspicious activity immediately</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.password-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
});
</script>
@endpush
