@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Hero Section Settings')
@section('page-actions')
    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Settings
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Homepage Hero Section">
            <form action="{{ route('admin.settings.hero.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Note:</strong> These settings control the text displayed in the hero section on the homepage.
                </div>

                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-stars me-2"></i>Main Hero Section</h6>

                <div class="mb-4">
                    <label for="hero_heading" class="form-label fw-medium">Main Heading <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('hero_heading') is-invalid @enderror"
                           id="hero_heading"
                           name="hero_heading"
                           value="{{ old('hero_heading', $heroSettings['heading']) }}"
                           placeholder="Kenya KNEC TVET Exam Preparation Made Simple"
                           required>
                    @error('hero_heading')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">The main headline displayed in the hero section</small>
                </div>

                <div class="mb-4">
                    <label for="hero_subheading" class="form-label fw-medium">Subheading <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('hero_subheading') is-invalid @enderror"
                              id="hero_subheading"
                              name="hero_subheading"
                              rows="3"
                              placeholder="Master your KNEC exams with past papers, detailed answers, and progress tracking."
                              required>{{ old('hero_subheading', $heroSettings['subheading']) }}</textarea>
                    @error('hero_subheading')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Supporting text below the main heading (max 500 characters)</small>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="hero_primary_button_text" class="form-label fw-medium">Primary Button Text <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('hero_primary_button_text') is-invalid @enderror"
                               id="hero_primary_button_text"
                               name="hero_primary_button_text"
                               value="{{ old('hero_primary_button_text', $heroSettings['primary_button_text']) }}"
                               placeholder="Browse Courses"
                               maxlength="50"
                               required>
                        @error('hero_primary_button_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="hero_secondary_button_text" class="form-label fw-medium">Secondary Button Text <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('hero_secondary_button_text') is-invalid @enderror"
                               id="hero_secondary_button_text"
                               name="hero_secondary_button_text"
                               value="{{ old('hero_secondary_button_text', $heroSettings['secondary_button_text']) }}"
                               placeholder="Start Free"
                               maxlength="50"
                               required>
                        @error('hero_secondary_button_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Shown to guests only</small>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-megaphone me-2"></i>Call-to-Action Section</h6>
                <p class="text-muted small mb-3">This section appears at the bottom of the homepage before the footer.</p>

                <div class="mb-4">
                    <label for="hero_cta_heading" class="form-label fw-medium">CTA Heading <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('hero_cta_heading') is-invalid @enderror"
                           id="hero_cta_heading"
                           name="hero_cta_heading"
                           value="{{ old('hero_cta_heading', $heroSettings['cta_heading']) }}"
                           placeholder="Ready to Ace Your Exams?"
                           required>
                    @error('hero_cta_heading')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="hero_cta_subheading" class="form-label fw-medium">CTA Subheading <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('hero_cta_subheading') is-invalid @enderror"
                              id="hero_cta_subheading"
                              name="hero_cta_subheading"
                              rows="2"
                              placeholder="Join thousands of students preparing smarter with TVET Revision."
                              required>{{ old('hero_cta_subheading', $heroSettings['cta_subheading']) }}</textarea>
                    @error('hero_cta_subheading')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Hero Settings
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Preview" class="border-primary">
            <div class="bg-primary text-white rounded p-3 mb-3">
                <h6 class="fw-bold mb-2" id="preview-heading">{{ $heroSettings['heading'] }}</h6>
                <p class="small mb-0 opacity-75" id="preview-subheading">{{ $heroSettings['subheading'] }}</p>
            </div>
            <div class="d-flex gap-2 mb-3">
                <span class="badge bg-light text-dark" id="preview-primary-btn">{{ $heroSettings['primary_button_text'] }}</span>
                <span class="badge bg-outline-light border" id="preview-secondary-btn">{{ $heroSettings['secondary_button_text'] }}</span>
            </div>
            <hr>
            <div class="bg-primary bg-opacity-10 rounded p-3">
                <h6 class="fw-bold mb-1 text-primary" id="preview-cta-heading">{{ $heroSettings['cta_heading'] }}</h6>
                <p class="small mb-0 text-muted" id="preview-cta-subheading">{{ $heroSettings['cta_subheading'] }}</p>
            </div>
        </x-card>

        <x-card title="Tips" class="mt-4">
            <ul class="small text-muted mb-0">
                <li class="mb-2"><strong>Keep it concise:</strong> Short, impactful headlines work best</li>
                <li class="mb-2"><strong>Action-oriented:</strong> Use verbs that encourage action</li>
                <li class="mb-2"><strong>Value proposition:</strong> Highlight what makes your platform unique</li>
                <li><strong>Target audience:</strong> Speak directly to TVET students</li>
            </ul>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live preview updates
    const fields = {
        'hero_heading': 'preview-heading',
        'hero_subheading': 'preview-subheading',
        'hero_primary_button_text': 'preview-primary-btn',
        'hero_secondary_button_text': 'preview-secondary-btn',
        'hero_cta_heading': 'preview-cta-heading',
        'hero_cta_subheading': 'preview-cta-subheading'
    };

    Object.keys(fields).forEach(function(inputId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(fields[inputId]);

        if (input && preview) {
            input.addEventListener('input', function() {
                preview.textContent = this.value || this.placeholder;
            });
        }
    });
});
</script>
@endpush
