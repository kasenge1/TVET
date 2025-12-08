@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Social Media Links')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Social Media Settings" class="border-primary">
            <form action="{{ route('admin.settings.social.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-share me-2"></i>
                    Add your social media links. These will be displayed in the footer and contact page.
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="social_facebook" class="form-label fw-medium">
                            <i class="bi bi-facebook text-primary me-1"></i>Facebook
                        </label>
                        <input type="url"
                               class="form-control @error('social_facebook') is-invalid @enderror"
                               id="social_facebook"
                               name="social_facebook"
                               value="{{ old('social_facebook', $socialSettings['facebook'] ?? '') }}"
                               placeholder="https://facebook.com/yourpage">
                        @error('social_facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="social_twitter" class="form-label fw-medium">
                            <i class="bi bi-twitter-x me-1"></i>Twitter/X
                        </label>
                        <input type="url"
                               class="form-control @error('social_twitter') is-invalid @enderror"
                               id="social_twitter"
                               name="social_twitter"
                               value="{{ old('social_twitter', $socialSettings['twitter'] ?? '') }}"
                               placeholder="https://twitter.com/yourhandle">
                        @error('social_twitter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="social_instagram" class="form-label fw-medium">
                            <i class="bi bi-instagram text-danger me-1"></i>Instagram
                        </label>
                        <input type="url"
                               class="form-control @error('social_instagram') is-invalid @enderror"
                               id="social_instagram"
                               name="social_instagram"
                               value="{{ old('social_instagram', $socialSettings['instagram'] ?? '') }}"
                               placeholder="https://instagram.com/yourprofile">
                        @error('social_instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="social_youtube" class="form-label fw-medium">
                            <i class="bi bi-youtube text-danger me-1"></i>YouTube
                        </label>
                        <input type="url"
                               class="form-control @error('social_youtube') is-invalid @enderror"
                               id="social_youtube"
                               name="social_youtube"
                               value="{{ old('social_youtube', $socialSettings['youtube'] ?? '') }}"
                               placeholder="https://youtube.com/@yourchannel">
                        @error('social_youtube')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="social_tiktok" class="form-label fw-medium">
                            <i class="bi bi-tiktok me-1"></i>TikTok
                        </label>
                        <input type="url"
                               class="form-control @error('social_tiktok') is-invalid @enderror"
                               id="social_tiktok"
                               name="social_tiktok"
                               value="{{ old('social_tiktok', $socialSettings['tiktok'] ?? '') }}"
                               placeholder="https://tiktok.com/@yourprofile">
                        @error('social_tiktok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="social_linkedin" class="form-label fw-medium">
                            <i class="bi bi-linkedin text-primary me-1"></i>LinkedIn
                        </label>
                        <input type="url"
                               class="form-control @error('social_linkedin') is-invalid @enderror"
                               id="social_linkedin"
                               name="social_linkedin"
                               value="{{ old('social_linkedin', $socialSettings['linkedin'] ?? '') }}"
                               placeholder="https://linkedin.com/company/yourcompany">
                        @error('social_linkedin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="social_whatsapp" class="form-label fw-medium">
                        <i class="bi bi-whatsapp text-success me-1"></i>WhatsApp Number
                    </label>
                    <input type="text"
                           class="form-control @error('social_whatsapp') is-invalid @enderror"
                           id="social_whatsapp"
                           name="social_whatsapp"
                           value="{{ old('social_whatsapp', $socialSettings['whatsapp'] ?? '') }}"
                           placeholder="254700000000">
                    @error('social_whatsapp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Enter number without + or spaces (e.g., 254700000000)</small>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Social Media Links
                    </button>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Connected Platforms" class="border-secondary">
            @php
                $platforms = [
                    ['key' => 'facebook', 'icon' => 'bi-facebook', 'color' => 'primary', 'name' => 'Facebook'],
                    ['key' => 'twitter', 'icon' => 'bi-twitter-x', 'color' => 'dark', 'name' => 'Twitter/X'],
                    ['key' => 'instagram', 'icon' => 'bi-instagram', 'color' => 'danger', 'name' => 'Instagram'],
                    ['key' => 'youtube', 'icon' => 'bi-youtube', 'color' => 'danger', 'name' => 'YouTube'],
                    ['key' => 'tiktok', 'icon' => 'bi-tiktok', 'color' => 'dark', 'name' => 'TikTok'],
                    ['key' => 'linkedin', 'icon' => 'bi-linkedin', 'color' => 'primary', 'name' => 'LinkedIn'],
                    ['key' => 'whatsapp', 'icon' => 'bi-whatsapp', 'color' => 'success', 'name' => 'WhatsApp'],
                ];
            @endphp

            @foreach($platforms as $platform)
                <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-center">
                        <i class="bi {{ $platform['icon'] }} text-{{ $platform['color'] }} me-2 fs-5"></i>
                        <span>{{ $platform['name'] }}</span>
                    </div>
                    @if(!empty($socialSettings[$platform['key']]))
                        <span class="badge bg-success"><i class="bi bi-check"></i> Connected</span>
                    @else
                        <span class="badge bg-secondary">Not set</span>
                    @endif
                </div>
            @endforeach
        </x-card>
    </div>
</div>
@endsection
