@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'General Settings')

@section('main')
<!-- Quick Navigation -->
<div class="row g-2 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.branding') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-palette d-block mb-1"></i>
            <small>Branding</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.hero') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-stars d-block mb-1"></i>
            <small>Hero</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.contact') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-telephone d-block mb-1"></i>
            <small>Contact</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.social') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-share d-block mb-1"></i>
            <small>Social</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.payments') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-credit-card d-block mb-1"></i>
            <small>Payments</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.email') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-envelope d-block mb-1"></i>
            <small>Email</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.ai') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-robot d-block mb-1"></i>
            <small>AI</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.maintenance') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-tools d-block mb-1"></i>
            <small>Maintenance</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.system') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-hdd-stack d-block mb-1"></i>
            <small>System</small>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.recaptcha') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-shield-check d-block mb-1"></i>
            <small>reCAPTCHA</small>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <x-card title="Application Settings">
            <form action="{{ route('admin.settings.general.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="app_name" class="form-label fw-medium">Application Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('app_name') is-invalid @enderror"
                           id="app_name"
                           name="app_name"
                           value="{{ old('app_name', config('app.name')) }}"
                           required>
                    @error('app_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This will be displayed throughout the application</small>
                </div>

                <div class="mb-4">
                    <label for="app_url" class="form-label fw-medium">Application URL</label>
                    <input type="url"
                           class="form-control @error('app_url') is-invalid @enderror"
                           id="app_url"
                           name="app_url"
                           value="{{ old('app_url', config('app.url')) }}"
                           readonly>
                    @error('app_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Read-only: Change in .env file</small>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="timezone" class="form-label fw-medium">Timezone</label>
                        <select class="form-select @error('timezone') is-invalid @enderror"
                                id="timezone"
                                name="timezone">
                            <option value="Africa/Nairobi" {{ config('app.timezone') === 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT)</option>
                            <option value="UTC" {{ config('app.timezone') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Africa/Lagos" {{ config('app.timezone') === 'Africa/Lagos' ? 'selected' : '' }}>Africa/Lagos (WAT)</option>
                            <option value="Africa/Johannesburg" {{ config('app.timezone') === 'Africa/Johannesburg' ? 'selected' : '' }}>Africa/Johannesburg (SAST)</option>
                        </select>
                        @error('timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="locale" class="form-label fw-medium">Language</label>
                        <select class="form-select @error('locale') is-invalid @enderror"
                                id="locale"
                                name="locale">
                            <option value="en" {{ config('app.locale') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ config('app.locale') === 'sw' ? 'selected' : '' }}>Swahili</option>
                        </select>
                        @error('locale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save General Settings
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="System Information" class="border-info">
            <div class="row g-3">
                <div class="col-6">
                    <div class="text-muted small">Laravel</div>
                    <div class="fw-medium">{{ app()->version() }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">PHP</div>
                    <div class="fw-medium">{{ PHP_VERSION }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Environment</div>
                    @if(config('app.env') === 'production')
                        <span class="badge bg-success">Production</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ ucfirst(config('app.env')) }}</span>
                    @endif
                </div>
                <div class="col-6">
                    <div class="text-muted small">Debug</div>
                    @if(config('app.debug'))
                        <span class="badge bg-danger">Enabled</span>
                    @else
                        <span class="badge bg-success">Disabled</span>
                    @endif
                </div>
                <div class="col-6">
                    <div class="text-muted small">Database</div>
                    <div class="fw-medium">{{ config('database.default') }}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Cache</div>
                    <div class="fw-medium">{{ config('cache.default') }}</div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
