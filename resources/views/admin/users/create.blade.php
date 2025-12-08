@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create New User')
@section('page-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Users
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g., John Doe"
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
                           value="{{ old('email') }}"
                           placeholder="user@example.com"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">User will receive login credentials at this email</small>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Roles <span class="text-danger">*</span></label>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('roles') is-invalid @enderror"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           id="role_{{ $role->name }}"
                                           {{ in_array($role->name, old('roles', ['student'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->name }}">
                                        <span class="fw-medium">{{ ucwords(str_replace('-', ' ', $role->name)) }}</span>
                                        @if($role->name === 'super-admin')
                                            <small class="text-danger">(Full Access)</small>
                                        @elseif($role->name === 'admin')
                                            <small class="text-primary">(Manage All)</small>
                                        @elseif($role->name === 'content-manager')
                                            <small class="text-info">(Courses & Questions)</small>
                                        @elseif($role->name === 'question-editor')
                                            <small class="text-success">(Questions Only)</small>
                                        @elseif($role->name === 'student')
                                            <small class="text-muted">(Learning Access)</small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="subscription_tier" class="form-label fw-medium">Subscription Tier <span class="text-danger">*</span></label>
                    <select class="form-select @error('subscription_tier') is-invalid @enderror"
                            id="subscription_tier"
                            name="subscription_tier"
                            required>
                        <option value="">Select Tier</option>
                        <option value="free" {{ old('subscription_tier', 'free') === 'free' ? 'selected' : '' }}>Free</option>
                        <option value="premium" {{ old('subscription_tier') === 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>
                    @error('subscription_tier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Role Descriptions">
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <span class="badge bg-danger me-2">Super Admin</span>
                    <small class="text-muted">Full system access, can manage all settings and users</small>
                </li>
                <li class="mb-3">
                    <span class="badge bg-primary me-2">Admin</span>
                    <small class="text-muted">Manage courses, units, questions, users, and view logs</small>
                </li>
                <li class="mb-3">
                    <span class="badge bg-info me-2">Content Manager</span>
                    <small class="text-muted">Create and edit courses, units, and questions</small>
                </li>
                <li class="mb-3">
                    <span class="badge bg-success me-2">Question Editor</span>
                    <small class="text-muted">Add and edit questions only - perfect for helpers</small>
                </li>
                <li>
                    <span class="badge bg-secondary me-2">Student</span>
                    <small class="text-muted">Access learning materials and enroll in courses</small>
                </li>
            </ul>
        </x-card>

        <x-card title="Subscription Info" class="mt-4">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="bi bi-circle text-muted me-2"></i>
                    <strong>Free:</strong> Basic access to courses
                </li>
                <li>
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    <strong>Premium:</strong> Full access to all features
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
