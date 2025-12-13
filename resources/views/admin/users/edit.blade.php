@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit User')
@section('page-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Users
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
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
                           value="{{ old('email', $user->email) }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Change Password</label>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>Leave blank to keep current password
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
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
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
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
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Roles <span class="text-danger">*</span></label>
                    @if($user->id === auth()->id())
                        <div class="alert alert-warning py-2 mb-2">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <small>Cannot change your own roles</small>
                        </div>
                    @endif
                    <div class="row">
                        @php
                            $userRoles = $user->roles->pluck('name')->toArray();
                        @endphp
                        @foreach($roles as $role)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('roles') is-invalid @enderror"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           id="role_{{ $role->name }}"
                                           {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
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
                    @if($user->id === auth()->id())
                        @foreach($userRoles as $roleName)
                            <input type="hidden" name="roles[]" value="{{ $roleName }}">
                        @endforeach
                    @endif
                    @error('roles')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="subscription_tier" class="form-label fw-medium">Subscription Tier <span class="text-danger">*</span></label>
                        <select class="form-select @error('subscription_tier') is-invalid @enderror"
                                id="subscription_tier"
                                name="subscription_tier"
                                required>
                            <option value="free" {{ old('subscription_tier', $user->subscription_tier) === 'free' ? 'selected' : '' }}>Free</option>
                            <option value="premium" {{ old('subscription_tier', $user->subscription_tier) === 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                        @error('subscription_tier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="subscription_expires_at" class="form-label fw-medium">Subscription Expiry</label>
                        <input type="date"
                               class="form-control @error('subscription_expires_at') is-invalid @enderror"
                               id="subscription_expires_at"
                               name="subscription_expires_at"
                               value="{{ old('subscription_expires_at', $user->subscription_expires_at?->format('Y-m-d')) }}">
                        @error('subscription_expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">For premium users</small>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="User Statistics">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">User ID</span>
                <span class="fw-bold">#{{ $user->id }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Current Roles</span>
                <span>
                    @forelse($user->roles as $role)
                        @if($role->name === 'super-admin')
                            <span class="badge bg-danger">{{ ucwords(str_replace('-', ' ', $role->name)) }}</span>
                        @elseif($role->name === 'admin')
                            <span class="badge bg-primary">{{ ucwords(str_replace('-', ' ', $role->name)) }}</span>
                        @elseif($role->name === 'content-manager')
                            <span class="badge bg-info">{{ ucwords(str_replace('-', ' ', $role->name)) }}</span>
                        @elseif($role->name === 'question-editor')
                            <span class="badge bg-success">{{ ucwords(str_replace('-', ' ', $role->name)) }}</span>
                        @else
                            <span class="badge bg-secondary">{{ ucwords(str_replace('-', ' ', $role->name)) }}</span>
                        @endif
                    @empty
                        <span class="text-muted">No roles</span>
                    @endforelse
                </span>
            </div>
            @if($user->enrollment)
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Enrolled Course</span>
                    <span class="fw-bold">{{ $user->enrollment->course->title ?? 'N/A' }}</span>
                </div>
            @endif
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Account Created</span>
                <span>{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Last Updated</span>
                <span>{{ $user->updated_at->format('M d, Y') }}</span>
            </div>
        </x-card>

        @if($user->id === auth()->id())
            <x-card title="Notice" class="mt-4 border-warning">
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    You are editing your own account. Role changes are disabled.
                </div>
            </x-card>
        @endif

        @if($user->isPremium())
            <x-card title="Premium Status" class="mt-4 border-success">
                <div class="alert alert-success mb-0">
                    <i class="bi bi-star-fill me-2"></i>
                    This user has an active premium subscription
                    @if($user->subscription_expires_at)
                        expiring on {{ $user->subscription_expires_at->format('M d, Y') }}
                    @endif
                </div>
            </x-card>
        @endif
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
