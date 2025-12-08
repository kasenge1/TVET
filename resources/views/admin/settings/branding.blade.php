@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Branding Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Site Name & Favicon">
            <form action="{{ route('admin.settings.branding.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Configure your site name (displayed as text logo) and favicon.
                </div>

                <!-- Site Name / Logo Text -->
                <div class="mb-4">
                    <label for="logo_alt" class="form-label fw-medium">Site Name</label>
                    <input type="text"
                           class="form-control @error('logo_alt') is-invalid @enderror"
                           id="logo_alt"
                           name="logo_alt"
                           value="{{ old('logo_alt', $brandingSettings['logo_alt']) }}"
                           placeholder="{{ config('app.name') }}">
                    @error('logo_alt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This name will be displayed as your site logo with an icon</small>
                </div>

                <hr class="my-4">

                <!-- Favicon Upload -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Site Favicon (Browser Icon)</label>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center bg-light" style="min-height: 120px;">
                                @if(!empty($brandingSettings['favicon']))
                                    <img src="{{ asset($brandingSettings['favicon']) }}" alt="Current Favicon" style="width: 64px; height: 64px;">
                                @else
                                    <div class="text-muted py-4">
                                        <i class="bi bi-app display-4"></i>
                                        <div class="small mt-2">No favicon uploaded</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="file"
                                   class="form-control @error('favicon') is-invalid @enderror"
                                   id="favicon"
                                   name="favicon"
                                   accept="image/png,image/x-icon,image/svg+xml">
                            @error('favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Recommended: PNG or ICO, max 512KB. Size: 32x32px or 64x64px</small>
                            @if(!empty($brandingSettings['favicon']))
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_favicon" value="1" id="remove_favicon">
                                    <label class="form-check-label text-danger" for="remove_favicon">
                                        <i class="bi bi-trash me-1"></i>Remove current favicon
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4 d-flex flex-wrap gap-2 align-items-center">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Branding
                    </button>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Preview" class="border-primary">
            <div class="text-center mb-4">
                <div class="bg-dark text-white p-3 rounded mb-3">
                    <strong>Admin Sidebar Preview</strong>
                    <div class="mt-2 py-2 border-top border-secondary">
                        <h5 class="mb-0 text-white fw-bold">
                            <i class="bi bi-shield-check me-2"></i>{{ $brandingSettings['logo_alt'] ?: config('app.name') }}
                        </h5>
                    </div>
                </div>
                <div class="bg-white border p-3 rounded">
                    <strong class="text-muted small">Frontend Navbar Preview</strong>
                    <div class="mt-2 py-2 border-top">
                        <span class="d-inline-flex align-items-center">
                            <span class="d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); border-radius: 8px;">
                                <i class="bi bi-mortarboard-fill text-white"></i>
                            </span>
                            <span class="fw-bold" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                {{ $brandingSettings['logo_alt'] ?: config('app.name') }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="small text-muted">
                <i class="bi bi-info-circle me-1"></i>
                This shows how your site name will appear in the navigation areas.
            </div>
        </x-card>

        <x-card title="Tips" class="mt-4">
            <ul class="list-unstyled mb-0 small">
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Site Name:</strong> Keep it short and memorable (2-3 words ideal)
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Favicon:</strong> Should be square (32x32 or 64x64 pixels)
                </li>
                <li>
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Browser Tab:</strong> Favicon appears in browser tabs for easy identification
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
