@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Feature Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <!-- Subscription Settings -->
        <x-card title="Subscription & Payments" class="mb-4">
            <form action="{{ route('admin.settings.subscription.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Control whether students can see and purchase subscriptions. Disable this when payment gateway is not configured or under maintenance.
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="subscriptions_enabled"
                               name="subscriptions_enabled" value="1"
                               {{ $subscriptionSettings['subscriptions_enabled'] ? 'checked' : '' }}>
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-check-label fw-medium" for="subscriptions_enabled">
                            Enable Subscriptions
                        </label>
                        <p class="text-muted small mb-0">
                            When enabled, students will see subscription plans and can make purchases.
                            When disabled, the subscription page will show a custom notice message.
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="subscription_notice" class="form-label fw-medium">Disabled Notice Message</label>
                    <textarea class="form-control @error('subscription_notice') is-invalid @enderror"
                              id="subscription_notice"
                              name="subscription_notice"
                              rows="3"
                              placeholder="Message shown when subscriptions are disabled...">{{ old('subscription_notice', $subscriptionSettings['subscription_notice']) }}</textarea>
                    @error('subscription_notice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This message is displayed to students when subscriptions are disabled.</small>
                </div>

                <div class="border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Subscription Settings
                    </button>
                </div>
            </form>
        </x-card>

        <!-- Appearance Settings -->
        <x-card title="Appearance & Theme" class="mb-4">
            <form action="{{ route('admin.settings.appearance.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="dark_mode_enabled"
                               name="dark_mode_enabled" value="1"
                               {{ $appearanceSettings['dark_mode_enabled'] ? 'checked' : '' }}>
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-check-label fw-medium" for="dark_mode_enabled">
                            Enable Dark Mode Toggle
                        </label>
                        <p class="text-muted small mb-0">
                            Allow students to switch between light and dark themes. The toggle will appear in their settings and navigation.
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Default Theme</label>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-check border rounded p-3 {{ ($appearanceSettings['default_theme'] ?? 'light') === 'light' ? 'border-primary bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="default_theme" id="theme_light" value="light"
                                       {{ ($appearanceSettings['default_theme'] ?? 'light') === 'light' ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_light">
                                    <i class="bi bi-sun me-1"></i> <strong>Light</strong>
                                    <div class="text-muted small">Default light theme</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check border rounded p-3 {{ ($appearanceSettings['default_theme'] ?? 'light') === 'dark' ? 'border-primary bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="default_theme" id="theme_dark" value="dark"
                                       {{ ($appearanceSettings['default_theme'] ?? 'light') === 'dark' ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_dark">
                                    <i class="bi bi-moon me-1"></i> <strong>Dark</strong>
                                    <div class="text-muted small">Default dark theme</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check border rounded p-3 {{ ($appearanceSettings['default_theme'] ?? 'light') === 'system' ? 'border-primary bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="default_theme" id="theme_system" value="system"
                                       {{ ($appearanceSettings['default_theme'] ?? 'light') === 'system' ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_system">
                                    <i class="bi bi-circle-half me-1"></i> <strong>System</strong>
                                    <div class="text-muted small">Follow device setting</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Appearance Settings
                    </button>
                </div>
            </form>
        </x-card>

        <!-- PWA/Offline Settings -->
        <x-card title="PWA & Offline Mode" class="mb-4">
            <form action="{{ route('admin.settings.pwa.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info mb-4">
                    <i class="bi bi-phone me-2"></i>
                    Progressive Web App (PWA) allows students to install the app on their devices and access content offline. This is especially useful for students with limited internet connectivity.
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="pwa_enabled"
                               name="pwa_enabled" value="1"
                               {{ $pwaSettings['pwa_enabled'] ? 'checked' : '' }}>
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-check-label fw-medium" for="pwa_enabled">
                            Enable PWA / Offline Mode
                        </label>
                        <p class="text-muted small mb-0">
                            Allow students to install the app and download content for offline study.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="pwa_requires_subscription"
                               name="pwa_requires_subscription" value="1"
                               {{ $pwaSettings['pwa_requires_subscription'] ? 'checked' : '' }}>
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-check-label fw-medium" for="pwa_requires_subscription">
                            Require Subscription for Offline Access
                        </label>
                        <p class="text-muted small mb-0">
                            When enabled, only subscribed students can download content for offline use. Free users can still install the app but won't be able to save content offline.
                        </p>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save PWA Settings
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <!-- Status Cards -->
        <x-card title="Subscription Status" class="mb-4">
            @if($subscriptionSettings['subscriptions_enabled'])
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-credit-card text-success" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-success mb-1">Subscriptions Active</h5>
                    <p class="text-muted small mb-0">Students can purchase plans</p>
                </div>
            @else
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-credit-card text-warning" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-warning mb-1">Subscriptions Disabled</h5>
                    <p class="text-muted small mb-0">Students see notice message</p>
                </div>
            @endif
        </x-card>

        <x-card title="PWA Status" class="mb-4">
            @if($pwaSettings['pwa_enabled'])
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-phone text-success" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-success mb-1">PWA Enabled</h5>
                    <p class="text-muted small mb-0">
                        @if($pwaSettings['pwa_requires_subscription'])
                            Subscribers only
                        @else
                            All students
                        @endif
                    </p>
                </div>
            @else
                <div class="text-center py-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-phone text-secondary" style="font-size: 1.75rem;"></i>
                    </div>
                    <h5 class="text-secondary mb-1">PWA Disabled</h5>
                    <p class="text-muted small mb-0">Offline mode unavailable</p>
                </div>
            @endif
        </x-card>

        <x-card title="Theme Preview">
            <div class="row g-2">
                <div class="col-6">
                    <div class="border rounded p-3 bg-white text-dark text-center">
                        <i class="bi bi-sun d-block mb-1"></i>
                        <small>Light</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-3 text-white text-center" style="background: #1a1a2e;">
                        <i class="bi bi-moon d-block mb-1"></i>
                        <small>Dark</small>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
