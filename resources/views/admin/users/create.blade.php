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
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Show/Hide Password">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                            <button class="btn btn-outline-primary" type="button" id="generatePassword" title="Generate Random Password">
                                <i class="bi bi-shuffle"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" title="Show/Hide Password">
                                <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Generated Password Display -->
                <div class="mb-4" id="generatedPasswordSection" style="display: none;">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-key-fill me-2 fs-5"></i>
                        <div class="flex-grow-1">
                            <strong>Generated Password:</strong>
                            <code id="generatedPasswordDisplay" class="ms-2 fs-6 user-select-all"></code>
                        </div>
                        <button type="button" class="btn btn-sm btn-success ms-2" id="copyPassword" title="Copy to Clipboard">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Save this password before creating the user. You won't be able to see it again!
                    </small>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="send_credentials" name="send_credentials" value="1" checked>
                        <label class="form-check-label" for="send_credentials">
                            <i class="bi bi-envelope me-1"></i>Send login credentials to user via email
                        </label>
                    </div>
                    <small class="text-muted">The user will receive an email with their login details</small>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');
    const togglePasswordConfirmIcon = document.getElementById('togglePasswordConfirmIcon');
    const generatePasswordBtn = document.getElementById('generatePassword');
    const generatedPasswordSection = document.getElementById('generatedPasswordSection');
    const generatedPasswordDisplay = document.getElementById('generatedPasswordDisplay');
    const copyPasswordBtn = document.getElementById('copyPassword');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePasswordIcon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
        passwordConfirmInput.type = type;
        togglePasswordConfirmIcon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // Generate random password
    generatePasswordBtn.addEventListener('click', function() {
        const length = 12;
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const numbers = '0123456789';
        const symbols = '!@#$%^&*';
        const allChars = lowercase + uppercase + numbers + symbols;

        let password = '';
        // Ensure at least one of each type
        password += lowercase[Math.floor(Math.random() * lowercase.length)];
        password += uppercase[Math.floor(Math.random() * uppercase.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];

        // Fill the rest randomly
        for (let i = password.length; i < length; i++) {
            password += allChars[Math.floor(Math.random() * allChars.length)];
        }

        // Shuffle the password
        password = password.split('').sort(() => Math.random() - 0.5).join('');

        // Set the password in both fields
        passwordInput.value = password;
        passwordConfirmInput.value = password;

        // Show the generated password section
        generatedPasswordDisplay.textContent = password;
        generatedPasswordSection.style.display = 'block';

        // Make password fields visible
        passwordInput.type = 'text';
        passwordConfirmInput.type = 'text';
        togglePasswordIcon.className = 'bi bi-eye-slash';
        togglePasswordConfirmIcon.className = 'bi bi-eye-slash';
    });

    // Copy password to clipboard
    copyPasswordBtn.addEventListener('click', function() {
        const password = generatedPasswordDisplay.textContent;
        navigator.clipboard.writeText(password).then(function() {
            const originalHTML = copyPasswordBtn.innerHTML;
            copyPasswordBtn.innerHTML = '<i class="bi bi-check"></i> Copied!';
            copyPasswordBtn.classList.remove('btn-success');
            copyPasswordBtn.classList.add('btn-primary');
            setTimeout(function() {
                copyPasswordBtn.innerHTML = originalHTML;
                copyPasswordBtn.classList.remove('btn-primary');
                copyPasswordBtn.classList.add('btn-success');
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection
