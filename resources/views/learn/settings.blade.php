@extends('layouts.frontend')

@section('title', 'Account Settings - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">My Course</a></li>
            <li class="breadcrumb-item active" aria-current="page">Settings</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                    <i class="bi bi-gear-fill fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Account Settings</h4>
                    <p class="mb-0 opacity-75">Manage your profile and preferences</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>Please fix the errors below.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Profile Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('learn.settings.profile') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Full Name</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-medium">Phone Number</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="e.g., 0712345678">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Used for M-Pesa payments</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-lock me-2"></i>Change Password</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('learn.settings.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-medium">Current Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-medium">New Password</label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-check me-1"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-bell me-2"></i>Notification Preferences</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Choose how you want to receive notifications.</p>
                    <form method="POST" action="{{ route('learn.settings.notifications') }}">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0">Notification Type</th>
                                        <th class="border-0 text-center" style="width: 80px;">In-App</th>
                                        <th class="border-0 text-center" style="width: 80px;">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notificationPreferences as $type => $pref)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="small">{{ $pref['label'] }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="form-check form-switch d-inline-block mb-0">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       name="preferences[{{ $type }}][in_app]"
                                                       {{ $pref['in_app'] ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="form-check form-switch d-inline-block mb-0">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       name="preferences[{{ $type }}][email]"
                                                       {{ $pref['email'] ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Account Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">Account Info</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Member Since</span>
                        <span class="fw-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Course</span>
                        <span class="fw-medium">{{ $enrollment->course->title ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Saved Questions</span>
                        <span class="fw-medium">{{ $savedCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">Quick Links</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('learn.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-book me-2"></i>My Course
                        </a>
                        <a href="{{ route('learn.saved') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-bookmark-fill me-2"></i>Saved Questions
                        </a>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h6>
                </div>
                <div class="card-body pt-0">
                    <p class="small text-muted mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-1"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('learn.settings.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.</p>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-medium">Enter your password to confirm</label>
                        <input type="password" class="form-control" id="delete_password" name="password" required placeholder="Your current password">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete My Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePasswordVisibility(inputId, button) {
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
