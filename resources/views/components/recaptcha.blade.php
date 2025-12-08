@props(['form' => 'register'])

@php
    use App\Models\SiteSetting;
    $recaptchaEnabled = SiteSetting::recaptchaEnabledFor($form);
    $siteKey = SiteSetting::getRecaptchaSiteKey();
    $settings = SiteSetting::getRecaptchaSettings();
    $version = $settings['version'] ?? 'v2';
@endphp

@if($recaptchaEnabled && $siteKey)
    @if($version === 'v2')
        {{-- reCAPTCHA v2 Checkbox --}}
        <div class="mb-3">
            <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
            @error('g-recaptcha-response')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    @else
        {{-- reCAPTCHA v3 Invisible --}}
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-{{ $form }}">
        @error('g-recaptcha-response')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    @endif

    @once
        @push('scripts')
            @if($version === 'v2')
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            @else
                <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
                <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $siteKey }}', {action: '{{ $form }}'}).then(function(token) {
                            document.getElementById('g-recaptcha-response-{{ $form }}').value = token;
                        });
                    });
                </script>
            @endif
        @endpush
    @endonce
@endif
