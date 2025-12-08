@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Security Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <!-- Email Verification Settings -->
        <x-card title="Email Verification" class="mb-4">
            <form action="{{ route('admin.settings.security.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="d-flex align-items-start gap-3">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="email_verification_required"
                               name="email_verification_required" value="1"
                               {{ $securitySettings['email_verification_required'] ? 'checked' : '' }}>
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-check-label fw-medium" for="email_verification_required">
                            Require Email Verification
                        </label>
                        <p class="text-muted small mb-0">
                            When enabled, new users must verify their email address before accessing the learning area.
                            This helps prevent fake registrations and ensures users have valid email addresses.
                        </p>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Email Settings
                    </button>
                </div>
            </form>
        </x-card>

        <!-- reCAPTCHA Settings -->
        <x-card title="Google reCAPTCHA Configuration">
            <form action="{{ route('admin.settings.recaptcha.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Google reCAPTCHA helps protect your site from spam and abuse. Get your keys from
                    <a href="https://www.google.com/recaptcha/admin" target="_blank" class="alert-link">Google reCAPTCHA Admin Console</a>.
                </div>

                <!-- Enable/Disable reCAPTCHA -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="recaptcha_enabled" name="recaptcha_enabled" value="1"
                               {{ $recaptchaSettings['enabled'] ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="recaptcha_enabled">
                            Enable reCAPTCHA Protection
                        </label>
                    </div>
                    <small class="text-muted">When enabled, reCAPTCHA will be shown on selected forms</small>
                </div>

                <hr class="my-4">

                <!-- reCAPTCHA Version -->
                <div class="mb-4">
                    <label class="form-label fw-medium">reCAPTCHA Version</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check border rounded p-3 {{ ($recaptchaSettings['version'] ?? 'v2') === 'v2' ? 'border-primary bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="recaptcha_version" id="version_v2" value="v2"
                                       {{ ($recaptchaSettings['version'] ?? 'v2') === 'v2' ? 'checked' : '' }}>
                                <label class="form-check-label" for="version_v2">
                                    <strong>reCAPTCHA v2</strong> (Checkbox)
                                    <div class="text-muted small">Users click "I'm not a robot" checkbox</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check border rounded p-3 {{ ($recaptchaSettings['version'] ?? 'v2') === 'v3' ? 'border-primary bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="recaptcha_version" id="version_v3" value="v3"
                                       {{ ($recaptchaSettings['version'] ?? 'v2') === 'v3' ? 'checked' : '' }}>
                                <label class="form-check-label" for="version_v3">
                                    <strong>reCAPTCHA v3</strong> (Invisible)
                                    <div class="text-muted small">Invisible verification based on behavior score</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Keys -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="recaptcha_site_key" class="form-label fw-medium">Site Key <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('recaptcha_site_key') is-invalid @enderror"
                               id="recaptcha_site_key"
                               name="recaptcha_site_key"
                               value="{{ old('recaptcha_site_key', $recaptchaSettings['site_key']) }}"
                               placeholder="6LcXXXXXXXXXXXXXXXXXXXXXXXXX">
                        @error('recaptcha_site_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Public key used in the frontend</small>
                    </div>

                    <div class="col-md-6">
                        <label for="recaptcha_secret_key" class="form-label fw-medium">Secret Key <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('recaptcha_secret_key') is-invalid @enderror"
                                   id="recaptcha_secret_key"
                                   name="recaptcha_secret_key"
                                   value="{{ old('recaptcha_secret_key', $recaptchaSettings['secret_key']) }}"
                                   placeholder="6LcXXXXXXXXXXXXXXXXXXXXXXXXX">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('recaptcha_secret_key')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('recaptcha_secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Private key for server-side verification</small>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Form Selection -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Enable reCAPTCHA on Forms</label>
                    <small class="text-muted d-block mb-3">Select which forms should have reCAPTCHA protection</small>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="login_enabled" name="recaptcha_login_enabled" value="1"
                                       {{ $recaptchaSettings['login_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="login_enabled">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login Form
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="register_enabled" name="recaptcha_register_enabled" value="1"
                                       {{ $recaptchaSettings['register_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="register_enabled">
                                    <i class="bi bi-person-plus me-1"></i> Registration Form
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="contact_enabled" name="recaptcha_contact_enabled" value="1"
                                       {{ $recaptchaSettings['contact_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="contact_enabled">
                                    <i class="bi bi-envelope me-1"></i> Contact Form
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="password_reset_enabled" name="recaptcha_password_reset_enabled" value="1"
                                       {{ $recaptchaSettings['password_reset_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="password_reset_enabled">
                                    <i class="bi bi-key me-1"></i> Password Reset Form
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4 d-flex flex-wrap gap-2 align-items-center">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save reCAPTCHA Settings
                    </button>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Settings
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <!-- Status Card -->
        <x-card title="Status" class="mb-4">
            @if($recaptchaSettings['enabled'] && !empty($recaptchaSettings['site_key']) && !empty($recaptchaSettings['secret_key']))
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-shield-check text-success" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-success mb-1">reCAPTCHA Active</h5>
                    <p class="text-muted small mb-0">Your forms are protected</p>
                </div>
            @elseif(!empty($recaptchaSettings['site_key']) && !empty($recaptchaSettings['secret_key']))
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-shield-exclamation text-warning" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-warning mb-1">reCAPTCHA Configured</h5>
                    <p class="text-muted small mb-0">Enable the toggle to activate protection</p>
                </div>
            @else
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-shield text-secondary" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-secondary mb-1">Not Configured</h5>
                    <p class="text-muted small mb-0">Enter your API keys to enable protection</p>
                </div>
            @endif
        </x-card>

        <!-- Help Card -->
        <x-card title="Setup Guide">
            <ol class="small mb-0 ps-3">
                <li class="mb-2">
                    Go to <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin</a>
                </li>
                <li class="mb-2">
                    Click <strong>"Create"</strong> or <strong>"+"</strong> to register a new site
                </li>
                <li class="mb-2">
                    Enter a label (e.g., "TVET Revision")
                </li>
                <li class="mb-2">
                    Choose <strong>reCAPTCHA v2</strong> (Checkbox) or <strong>v3</strong>
                </li>
                <li class="mb-2">
                    Add your domain(s) including <code>localhost</code> for testing
                </li>
                <li class="mb-2">
                    Accept the terms and click <strong>Submit</strong>
                </li>
                <li class="mb-0">
                    Copy the <strong>Site Key</strong> and <strong>Secret Key</strong> here
                </li>
            </ol>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
@endsection
