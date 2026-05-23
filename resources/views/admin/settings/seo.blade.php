@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'SEO Settings')

@section('main')
<!-- Quick Navigation -->
<div class="row g-2 mb-4">
    @can('manage settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-gear d-block mb-1"></i>
            <small>General</small>
        </a>
    </div>
    @endcan
    @can('manage branding')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.branding') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-palette d-block mb-1"></i>
            <small>Branding</small>
        </a>
    </div>
    @endcan
    @can('manage settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.seo') }}" class="btn btn-primary w-100 py-2">
            <i class="bi bi-search d-block mb-1"></i>
            <small>SEO</small>
        </a>
    </div>
    @endcan
    @can('manage social settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.social') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-share d-block mb-1"></i>
            <small>Social</small>
        </a>
    </div>
    @endcan
    @can('manage contact settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.contact') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-telephone d-block mb-1"></i>
            <small>Contact</small>
        </a>
    </div>
    @endcan
    @can('manage hero settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.hero') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-stars d-block mb-1"></i>
            <small>Hero</small>
        </a>
    </div>
    @endcan
    @can('manage payment settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.payments') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-credit-card d-block mb-1"></i>
            <small>Payments</small>
        </a>
    </div>
    @endcan
    @can('manage email settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.email') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-envelope d-block mb-1"></i>
            <small>Email</small>
        </a>
    </div>
    @endcan
    @can('manage ai settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.ai') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-robot d-block mb-1"></i>
            <small>AI</small>
        </a>
    </div>
    @endcan
    @can('manage maintenance')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.maintenance') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-tools d-block mb-1"></i>
            <small>Maintenance</small>
        </a>
    </div>
    @endcan
    @can('view system info')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.system') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-hdd-stack d-block mb-1"></i>
            <small>System</small>
        </a>
    </div>
    @endcan
    @can('manage security settings')
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('admin.settings.recaptcha') }}" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-shield-check d-block mb-1"></i>
            <small>reCAPTCHA</small>
        </a>
    </div>
    @endcan
</div>

<form action="{{ route('admin.settings.seo.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main SEO Settings -->
        <div class="col-lg-8">
            <!-- Default Meta Tags -->
            <x-card title="Default Meta Tags" class="mb-4">
                <p class="text-muted small mb-4">These are used as fallbacks when pages don't have their own meta tags.</p>

                <div class="mb-4">
                    <label for="seo_meta_title" class="form-label fw-medium">Default Meta Title</label>
                    <input type="text" class="form-control @error('seo_meta_title') is-invalid @enderror"
                           id="seo_meta_title" name="seo_meta_title"
                           value="{{ old('seo_meta_title', $seoSettings['meta_title']) }}"
                           placeholder="TVET Revision - KNEC Exam Preparation" maxlength="70">
                    @error('seo_meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Recommended: 50-60 characters. <span id="titleCounter" class="ms-1"></span></small>
                </div>

                <div class="mb-4">
                    <label for="seo_meta_description" class="form-label fw-medium">Default Meta Description</label>
                    <textarea class="form-control @error('seo_meta_description') is-invalid @enderror"
                              id="seo_meta_description" name="seo_meta_description"
                              rows="3" maxlength="160"
                              placeholder="Access thousands of past papers...">{{ old('seo_meta_description', $seoSettings['meta_description']) }}</textarea>
                    @error('seo_meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Recommended: 150-160 characters. <span id="descCounter" class="ms-1"></span></small>
                </div>

                <div class="mb-4">
                    <label for="seo_meta_keywords" class="form-label fw-medium">Default Meta Keywords</label>
                    <textarea class="form-control @error('seo_meta_keywords') is-invalid @enderror"
                              id="seo_meta_keywords" name="seo_meta_keywords"
                              rows="2" maxlength="500"
                              placeholder="TVET, KNEC, past papers...">{{ old('seo_meta_keywords', $seoSettings['meta_keywords']) }}</textarea>
                    @error('seo_meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                    <small class="text-muted">Comma-separated keywords</small>
                </div>

                <div class="mb-0">
                    <label for="seo_og_image" class="form-label fw-medium">Default OG Image URL</label>
                    <input type="text" class="form-control @error('seo_og_image') is-invalid @enderror"
                           id="seo_og_image" name="seo_og_image"
                           value="{{ old('seo_og_image', $seoSettings['og_image']) }}"
                           placeholder="{{ asset('images/og-default.png') }}">
                    @error('seo_og_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                    <small class="text-muted">Image used when sharing on social media. Recommended: 1200x630px</small>
                </div>
            </x-card>

            <!-- Google Integrations -->
            <x-card title="Google Integrations" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="seo_google_analytics_id" class="form-label fw-medium">Google Analytics ID</label>
                        <input type="text" class="form-control @error('seo_google_analytics_id') is-invalid @enderror"
                               id="seo_google_analytics_id" name="seo_google_analytics_id"
                               value="{{ old('seo_google_analytics_id', $seoSettings['google_analytics_id']) }}"
                               placeholder="G-XXXXXXXXXX">
                        @error('seo_google_analytics_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">e.g. G-XXXXXXXXXX</small>
                    </div>
                    <div class="col-md-6">
                        <label for="seo_google_tag_manager" class="form-label fw-medium">Google Tag Manager ID</label>
                        <input type="text" class="form-control @error('seo_google_tag_manager') is-invalid @enderror"
                               id="seo_google_tag_manager" name="seo_google_tag_manager"
                               value="{{ old('seo_google_tag_manager', $seoSettings['google_tag_manager']) }}"
                               placeholder="GTM-XXXXXXX">
                        @error('seo_google_tag_manager')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">e.g. GTM-XXXXXXX</small>
                    </div>
                    <div class="col-12">
                        <label for="seo_google_search_console" class="form-label fw-medium">Google Search Console Verification</label>
                        <input type="text" class="form-control @error('seo_google_search_console') is-invalid @enderror"
                               id="seo_google_search_console" name="seo_google_search_console"
                               value="{{ old('seo_google_search_console', $seoSettings['google_search_console']) }}"
                               placeholder="Your verification meta tag content">
                        @error('seo_google_search_console')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Paste the content value from the meta tag verification</small>
                    </div>
                </div>
            </x-card>

            <!-- Schema.org -->
            <x-card title="Schema.org Structured Data" class="mb-4">
                <div class="mb-4">
                    <label for="seo_schema_org_type" class="form-label fw-medium">Organization Type</label>
                    <select class="form-select @error('seo_schema_org_type') is-invalid @enderror"
                            id="seo_schema_org_type" name="seo_schema_org_type">
                        <option value="EducationalOrganization" {{ old('seo_schema_org_type', $seoSettings['schema_org_type']) === 'EducationalOrganization' ? 'selected' : '' }}>Educational Organization</option>
                        <option value="Organization" {{ old('seo_schema_org_type', $seoSettings['schema_org_type']) === 'Organization' ? 'selected' : '' }}>Organization</option>
                        <option value="LocalBusiness" {{ old('seo_schema_org_type', $seoSettings['schema_org_type']) === 'LocalBusiness' ? 'selected' : '' }}>Local Business</option>
                        <option value="Corporation" {{ old('seo_schema_org_type', $seoSettings['schema_org_type']) === 'Corporation' ? 'selected' : '' }}>Corporation</option>
                    </select>
                    @error('seo_schema_org_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="seo_schema_org_name" class="form-label fw-medium">Organization Name</label>
                    <input type="text" class="form-control @error('seo_schema_org_name') is-invalid @enderror"
                           id="seo_schema_org_name" name="seo_schema_org_name"
                           value="{{ old('seo_schema_org_name', $seoSettings['schema_org_name']) }}">
                    @error('seo_schema_org_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label for="seo_schema_org_description" class="form-label fw-medium">Organization Description</label>
                    <textarea class="form-control @error('seo_schema_org_description') is-invalid @enderror"
                              id="seo_schema_org_description" name="seo_schema_org_description"
                              rows="2">{{ old('seo_schema_org_description', $seoSettings['schema_org_description']) }}</textarea>
                    @error('seo_schema_org_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </x-card>

            <!-- Custom Code -->
            <x-card title="Custom Code Injection" class="mb-4">
                <p class="text-muted small mb-3">Add custom code to the &lt;head&gt; section of all pages. Useful for verification tags, analytics, or other scripts.</p>
                <div class="mb-0">
                    <label for="seo_custom_head_code" class="form-label fw-medium">Custom Head Code</label>
                    <textarea class="form-control font-monospace @error('seo_custom_head_code') is-invalid @enderror"
                              id="seo_custom_head_code" name="seo_custom_head_code"
                              rows="6" style="font-size: 0.85rem;"
                              placeholder="<!-- Add custom meta tags, scripts, or styles -->">{{ old('seo_custom_head_code', $seoSettings['custom_head_code']) }}</textarea>
                    @error('seo_custom_head_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                    <small class="text-muted">HTML code injected into every page's &lt;head&gt; section</small>
                </div>
            </x-card>

            <!-- Save Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Save SEO Settings
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Feature Toggles -->
            <x-card title="SEO Features" class="mb-4">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="seo_enable_json_ld" name="seo_enable_json_ld" value="1"
                           {{ old('seo_enable_json_ld', $seoSettings['enable_json_ld']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="seo_enable_json_ld">
                        <strong>JSON-LD</strong>
                        <small class="d-block text-muted">Structured data for rich search results</small>
                    </label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="seo_enable_open_graph" name="seo_enable_open_graph" value="1"
                           {{ old('seo_enable_open_graph', $seoSettings['enable_open_graph']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="seo_enable_open_graph">
                        <strong>Open Graph</strong>
                        <small class="d-block text-muted">Social media sharing tags</small>
                    </label>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="seo_enable_twitter_cards" name="seo_enable_twitter_cards" value="1"
                           {{ old('seo_enable_twitter_cards', $seoSettings['enable_twitter_cards']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="seo_enable_twitter_cards">
                        <strong>Twitter Cards</strong>
                        <small class="d-block text-muted">Twitter-specific sharing tags</small>
                    </label>
                </div>
            </x-card>

            <!-- SEO Status -->
            <x-card title="SEO Status" class="mb-4 border-success">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <span>Sitemaps configured</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <span>robots.txt present</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    @if($seoSettings['google_analytics_id'])
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span>Google Analytics connected</span>
                    @else
                        <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                        <span class="text-muted">Google Analytics not set</span>
                    @endif
                </div>
                <div class="d-flex align-items-center mb-3">
                    @if($seoSettings['google_search_console'])
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span>Search Console verified</span>
                    @else
                        <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                        <span class="text-muted">Search Console not verified</span>
                    @endif
                </div>
                <div class="d-flex align-items-center mb-3">
                    @if($seoSettings['meta_description'])
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span>Default description set</span>
                    @else
                        <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                        <span class="text-muted">No default description</span>
                    @endif
                </div>
                <div class="d-flex align-items-center mb-0">
                    @if($seoSettings['og_image'])
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span>OG image configured</span>
                    @else
                        <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                        <span class="text-muted">No OG image set</span>
                    @endif
                </div>
            </x-card>

            <!-- Sitemap Links -->
            <x-card title="Sitemaps" class="mb-4">
                <div class="list-group list-group-flush">
                    <a href="{{ route('sitemap') }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <span><i class="bi bi-diagram-3 me-2"></i>sitemap.xml</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                    <a href="{{ route('sitemap.pages') }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <span><i class="bi bi-file-earmark me-2"></i>sitemap-pages.xml</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                    <a href="{{ route('sitemap.courses') }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <span><i class="bi bi-collection me-2"></i>sitemap-courses.xml</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                    <a href="{{ route('sitemap.blog') }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <span><i class="bi bi-newspaper me-2"></i>sitemap-blog.xml</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</form>

<!-- robots.txt Section (separate form) -->
<div class="row mt-4">
    <div class="col-12">
        <x-card title="robots.txt Editor">
            <form action="{{ route('admin.settings.seo.robots.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <textarea class="form-control font-monospace @error('robots_txt') is-invalid @enderror"
                              id="robots_txt" name="robots_txt"
                              rows="12" style="font-size: 0.85rem;">{{ old('robots_txt', $seoSettings['robots_txt'] ?: file_get_contents(public_path('robots.txt'))) }}</textarea>
                    @error('robots_txt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This updates both the database and the physical robots.txt file in the public directory.</small>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-outline-primary px-4">
                        <i class="bi bi-file-earmark-code me-2"></i>Update robots.txt
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const titleInput = document.getElementById('seo_meta_title');
    const descInput = document.getElementById('seo_meta_description');
    const titleCounter = document.getElementById('titleCounter');
    const descCounter = document.getElementById('descCounter');

    function updateCounter(input, counter, max) {
        const len = input.value.length;
        counter.textContent = len + '/' + max;
        counter.className = 'ms-1 ' + (len > max ? 'text-danger' : len > max * 0.9 ? 'text-warning' : 'text-muted');
    }

    if (titleInput && titleCounter) {
        titleInput.addEventListener('input', function() { updateCounter(this, titleCounter, 70); });
        updateCounter(titleInput, titleCounter, 70);
    }

    if (descInput && descCounter) {
        descInput.addEventListener('input', function() { updateCounter(this, descCounter, 160); });
        updateCounter(descInput, descCounter, 160);
    }
});
</script>
@endpush
