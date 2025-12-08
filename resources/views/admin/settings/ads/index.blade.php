@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Google Ads Settings')
@section('page-actions')
    <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Settings
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Google AdSense Configuration">
            <form action="{{ route('admin.settings.ads.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Note:</strong> Ads will only be displayed to users without an active premium subscription.
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox"
                               class="form-check-input"
                               id="ads_enabled"
                               name="ads_enabled"
                               value="1"
                               {{ $adsSettings['enabled'] == '1' ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="ads_enabled">Enable Google Ads</label>
                        <small class="text-muted d-block">Turn on ads display for free users</small>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <label for="ads_client_id" class="form-label fw-medium">AdSense Publisher ID</label>
                    <div class="input-group">
                        <span class="input-group-text">ca-pub-</span>
                        <input type="text"
                               class="form-control @error('ads_client_id') is-invalid @enderror"
                               id="ads_client_id"
                               name="ads_client_id"
                               value="{{ $adsSettings['client_id'] }}"
                               placeholder="1234567890123456">
                        @error('ads_client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Your AdSense publisher ID (numbers after ca-pub-)</small>
                </div>

                <h6 class="fw-bold mb-3">Ad Slots</h6>
                <p class="text-muted small mb-4">Enter the Ad Unit IDs from your AdSense account for each placement.</p>

                <div class="mb-4">
                    <label for="ads_slot_header" class="form-label fw-medium">Header Ad Slot ID</label>
                    <input type="text"
                           class="form-control @error('ads_slot_header') is-invalid @enderror"
                           id="ads_slot_header"
                           name="ads_slot_header"
                           value="{{ $adsSettings['slot_header'] }}"
                           placeholder="1234567890">
                    <small class="text-muted">Displays at the top of pages (728x90 recommended)</small>
                    @error('ads_slot_header')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ads_slot_sidebar" class="form-label fw-medium">Sidebar Ad Slot ID</label>
                    <input type="text"
                           class="form-control @error('ads_slot_sidebar') is-invalid @enderror"
                           id="ads_slot_sidebar"
                           name="ads_slot_sidebar"
                           value="{{ $adsSettings['slot_sidebar'] }}"
                           placeholder="1234567890">
                    <small class="text-muted">Displays in sidebar areas (300x250 recommended)</small>
                    @error('ads_slot_sidebar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ads_slot_content" class="form-label fw-medium">In-Content Ad Slot ID</label>
                    <input type="text"
                           class="form-control @error('ads_slot_content') is-invalid @enderror"
                           id="ads_slot_content"
                           name="ads_slot_content"
                           value="{{ $adsSettings['slot_content'] }}"
                           placeholder="1234567890">
                    <small class="text-muted">Displays within content areas (responsive recommended)</small>
                    @error('ads_slot_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Setup Guide">
            <ol class="mb-0">
                <li class="mb-3">
                    <strong>Create AdSense Account</strong>
                    <p class="text-muted small mb-0">Sign up at <a href="https://www.google.com/adsense" target="_blank">google.com/adsense</a></p>
                </li>
                <li class="mb-3">
                    <strong>Get Publisher ID</strong>
                    <p class="text-muted small mb-0">Find your ca-pub-XXXXXXX ID in AdSense settings</p>
                </li>
                <li class="mb-3">
                    <strong>Create Ad Units</strong>
                    <p class="text-muted small mb-0">Create Display Ads for each placement</p>
                </li>
                <li class="mb-3">
                    <strong>Copy Slot IDs</strong>
                    <p class="text-muted small mb-0">Get the data-ad-slot value from each ad code</p>
                </li>
                <li>
                    <strong>Enable Ads</strong>
                    <p class="text-muted small mb-0">Toggle on and save your settings</p>
                </li>
            </ol>
        </x-card>

        <x-card title="Ad Placements" class="mt-4">
            <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-start">
                    <i class="bi bi-layout-text-window-reverse text-primary me-2 mt-1"></i>
                    <div>
                        <strong>Header</strong>
                        <p class="text-muted small mb-0">Top of dashboard and question pages</p>
                    </div>
                </li>
                <li class="mb-3 d-flex align-items-start">
                    <i class="bi bi-layout-sidebar text-primary me-2 mt-1"></i>
                    <div>
                        <strong>Sidebar</strong>
                        <p class="text-muted small mb-0">Right sidebar on desktop</p>
                    </div>
                </li>
                <li class="d-flex align-items-start">
                    <i class="bi bi-card-text text-primary me-2 mt-1"></i>
                    <div>
                        <strong>In-Content</strong>
                        <p class="text-muted small mb-0">Between questions on listing pages</p>
                    </div>
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
