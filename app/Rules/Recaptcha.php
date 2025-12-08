<?php

namespace App\Rules;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    protected string $form;

    public function __construct(string $form = 'register')
    {
        $this->form = $form;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if reCAPTCHA is not enabled for this form
        if (!SiteSetting::recaptchaEnabledFor($this->form)) {
            return;
        }

        $secretKey = SiteSetting::getRecaptchaSecretKey();

        if (empty($secretKey)) {
            return; // Skip if not configured
        }

        if (empty($value)) {
            $fail('Please complete the reCAPTCHA verification.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false)) {
                $fail('reCAPTCHA verification failed. Please try again.');
                return;
            }

            // For reCAPTCHA v3, check the score
            $settings = SiteSetting::getRecaptchaSettings();
            if (($settings['version'] ?? 'v2') === 'v3') {
                $score = $result['score'] ?? 0;
                if ($score < 0.5) {
                    $fail('reCAPTCHA verification failed. Please try again.');
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't block the user
            \Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            // Optionally fail validation on error
            // $fail('reCAPTCHA verification failed. Please try again.');
        }
    }
}
