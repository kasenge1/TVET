@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Maintenance Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <!-- Maintenance Mode Toggle -->
        <x-card title="Maintenance Mode" class="border-warning mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        @if(app()->isDownForMaintenance())
                            <span class="badge bg-danger me-2">ACTIVE</span>
                        @else
                            <span class="badge bg-success me-2">INACTIVE</span>
                        @endif
                        <span class="fw-medium">Maintenance Mode</span>
                    </div>
                    <p class="text-muted small mb-0">When enabled, visitors see the maintenance page.</p>
                </div>
                <div>
                    @if(app()->isDownForMaintenance())
                        <form action="{{ route('admin.settings.maintenance.disable') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-power me-1"></i>Disable
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.settings.maintenance.enable') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-tools me-1"></i>Enable
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </x-card>

        <!-- Maintenance Page Settings -->
        <x-card title="Maintenance Page Content" class="border-primary">
            <form action="{{ route('admin.settings.maintenance.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Customize the content shown to visitors during maintenance mode.
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="title" class="form-label fw-medium">Page Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $maintenanceSettings->title ?? 'We\'ll Be Back Soon!') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="subtitle" class="form-label fw-medium">Subtitle <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('subtitle') is-invalid @enderror"
                               id="subtitle"
                               name="subtitle"
                               value="{{ old('subtitle', $maintenanceSettings->subtitle ?? 'Scheduled Maintenance') }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label fw-medium">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('message') is-invalid @enderror"
                              id="message"
                              name="message"
                              rows="4">{{ old('message', $maintenanceSettings->message ?? 'We are currently performing scheduled maintenance to improve your experience. Please check back soon.') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="expected_duration" class="form-label fw-medium">Expected Duration <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('expected_duration') is-invalid @enderror"
                               id="expected_duration"
                               name="expected_duration"
                               value="{{ old('expected_duration', $maintenanceSettings->expected_duration ?? '2-4 hours') }}"
                               placeholder="e.g., 2-4 hours, 30 minutes">
                        @error('expected_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="support_email" class="form-label fw-medium">Support Email</label>
                        <input type="email"
                               class="form-control @error('support_email') is-invalid @enderror"
                               id="support_email"
                               name="support_email"
                               value="{{ old('support_email', $maintenanceSettings->support_email ?? '') }}"
                               placeholder="support@example.com">
                        @error('support_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-share me-2"></i>Social Links (Maintenance Page)</h6>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="facebook_url" class="form-label fw-medium">Facebook URL</label>
                        <input type="url"
                               class="form-control @error('facebook_url') is-invalid @enderror"
                               id="facebook_url"
                               name="facebook_url"
                               value="{{ old('facebook_url', $maintenanceSettings->facebook_url ?? '') }}"
                               placeholder="https://facebook.com/yourpage">
                        @error('facebook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="twitter_url" class="form-label fw-medium">Twitter URL</label>
                        <input type="url"
                               class="form-control @error('twitter_url') is-invalid @enderror"
                               id="twitter_url"
                               name="twitter_url"
                               value="{{ old('twitter_url', $maintenanceSettings->twitter_url ?? '') }}"
                               placeholder="https://twitter.com/yourhandle">
                        @error('twitter_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="instagram_url" class="form-label fw-medium">Instagram URL</label>
                        <input type="url"
                               class="form-control @error('instagram_url') is-invalid @enderror"
                               id="instagram_url"
                               name="instagram_url"
                               value="{{ old('instagram_url', $maintenanceSettings->instagram_url ?? '') }}"
                               placeholder="https://instagram.com/yourprofile">
                        @error('instagram_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="linkedin_url" class="form-label fw-medium">LinkedIn URL</label>
                        <input type="url"
                               class="form-control @error('linkedin_url') is-invalid @enderror"
                               id="linkedin_url"
                               name="linkedin_url"
                               value="{{ old('linkedin_url', $maintenanceSettings->linkedin_url ?? '') }}"
                               placeholder="https://linkedin.com/company/yourcompany">
                        @error('linkedin_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Settings
                    </button>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Status" class="border-secondary">
            <div class="text-center py-3">
                @if(app()->isDownForMaintenance())
                    <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-tools fs-3"></i>
                    </div>
                    <h5 class="text-danger">Maintenance Active</h5>
                    <p class="text-muted small">Site is currently offline for visitors</p>
                @else
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-globe fs-3"></i>
                    </div>
                    <h5 class="text-success">Site Online</h5>
                    <p class="text-muted small">All users can access the site</p>
                @endif
            </div>
        </x-card>

        <x-card title="Quick Tips" class="mt-4">
            <ul class="list-unstyled mb-0 small">
                <li class="mb-3 d-flex align-items-start">
                    <i class="bi bi-shield-check text-success me-2 mt-1"></i>
                    <div>
                        <strong>Admin Access</strong>
                        <div class="text-muted">Admins can always access the site during maintenance</div>
                    </div>
                </li>
                <li class="mb-3 d-flex align-items-start">
                    <i class="bi bi-clock text-primary me-2 mt-1"></i>
                    <div>
                        <strong>Schedule Wisely</strong>
                        <div class="text-muted">Perform maintenance during low-traffic hours</div>
                    </div>
                </li>
                <li class="d-flex align-items-start">
                    <i class="bi bi-megaphone text-warning me-2 mt-1"></i>
                    <div>
                        <strong>Notify Users</strong>
                        <div class="text-muted">Consider sending notifications before maintenance</div>
                    </div>
                </li>
            </ul>
        </x-card>

        <x-card title="Preview" class="mt-4">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary w-100">
                <i class="bi bi-eye me-1"></i>Preview Page
            </a>
            <small class="text-muted d-block text-center mt-2" style="font-size: 0.75rem;">Only visible if maintenance is active</small>
        </x-card>
    </div>
</div>
@endsection
