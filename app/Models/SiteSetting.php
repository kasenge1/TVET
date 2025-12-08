<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("site_setting_{$key}");

        return $setting;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)->pluck('value', 'key');
    }

    /**
     * Get Google Ads settings
     */
    public static function getAdsSettings()
    {
        return [
            'enabled' => static::get('ads_enabled', false),
            'client_id' => static::get('ads_client_id', ''),
            'slot_header' => static::get('ads_slot_header', ''),
            'slot_sidebar' => static::get('ads_slot_sidebar', ''),
            'slot_content' => static::get('ads_slot_content', ''),
        ];
    }

    /**
     * Check if ads are enabled
     */
    public static function adsEnabled()
    {
        return static::get('ads_enabled', false) == '1';
    }

    /**
     * Get M-Pesa settings
     */
    public static function getMpesaSettings()
    {
        return [
            'consumer_key' => static::get('mpesa_consumer_key', ''),
            'consumer_secret' => static::get('mpesa_consumer_secret', ''),
            'shortcode' => static::get('mpesa_shortcode', ''),
            'passkey' => static::get('mpesa_passkey', ''),
            'environment' => static::get('mpesa_environment', 'sandbox'),
            'callback_url' => static::get('mpesa_callback_url', ''),
        ];
    }

    /**
     * Set M-Pesa settings
     */
    public static function setMpesaSettings(array $settings)
    {
        foreach ($settings as $key => $value) {
            if (!empty($value)) {
                static::set("mpesa_{$key}", $value, 'mpesa');
            }
        }

        return static::getMpesaSettings();
    }

    /**
     * Check if M-Pesa is configured
     */
    public static function mpesaConfigured()
    {
        $settings = static::getMpesaSettings();
        return !empty($settings['consumer_key'])
            && !empty($settings['consumer_secret'])
            && !empty($settings['shortcode'])
            && !empty($settings['passkey']);
    }

    /**
     * Get AI settings
     */
    public static function getAiSettings()
    {
        return [
            'provider' => static::get('ai_provider', 'openai'),
            'api_key' => static::get('ai_api_key', ''),
            'model' => static::get('ai_model', 'gpt-4o-mini'),
            'max_tokens' => (int) static::get('ai_max_tokens', 1000),
            'temperature' => (float) static::get('ai_temperature', 0.7),
            'system_prompt' => static::get('ai_system_prompt', 'You are an expert TVET educator in Kenya. Provide clear, accurate, and educational answers to questions. Format your answers in a way that helps students understand the concepts.'),
        ];
    }

    /**
     * Set AI settings
     */
    public static function setAiSettings(array $settings)
    {
        $allowedKeys = ['provider', 'api_key', 'model', 'max_tokens', 'temperature', 'system_prompt'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys) && $value !== null && $value !== '') {
                static::set("ai_{$key}", $value, 'ai');
            }
        }

        return static::getAiSettings();
    }

    /**
     * Check if AI is configured
     */
    public static function aiConfigured()
    {
        $settings = static::getAiSettings();
        return !empty($settings['api_key']);
    }

    /**
     * Get contact settings
     */
    public static function getContactSettings()
    {
        return [
            'email' => static::get('contact_email', 'support@tvetrevision.co.ke'),
            'phone' => static::get('contact_phone', '+254 700 000 000'),
            'address' => static::get('contact_address', 'Nairobi, Kenya'),
            'address_line2' => static::get('contact_address_line2', ''),
            'working_hours' => static::get('contact_working_hours', ''),
        ];
    }

    /**
     * Set contact settings
     */
    public static function setContactSettings(array $settings)
    {
        $allowedKeys = ['email', 'phone', 'address', 'address_line2', 'working_hours'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set("contact_{$key}", $value ?? '', 'contact');
            }
        }

        return static::getContactSettings();
    }

    /**
     * Get social media settings
     */
    public static function getSocialSettings()
    {
        return [
            'facebook' => static::get('social_facebook', ''),
            'twitter' => static::get('social_twitter', ''),
            'instagram' => static::get('social_instagram', ''),
            'youtube' => static::get('social_youtube', ''),
            'tiktok' => static::get('social_tiktok', ''),
            'linkedin' => static::get('social_linkedin', ''),
            'whatsapp' => static::get('social_whatsapp', ''),
        ];
    }

    /**
     * Set social media settings
     */
    public static function setSocialSettings(array $settings)
    {
        $allowedKeys = ['facebook', 'twitter', 'instagram', 'youtube', 'tiktok', 'linkedin', 'whatsapp'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set("social_{$key}", $value ?? '', 'social');
            }
        }

        return static::getSocialSettings();
    }

    /**
     * Get email/SMTP settings
     */
    public static function getEmailSettings()
    {
        return [
            'driver' => static::get('mail_driver', config('mail.default', 'smtp')),
            'host' => static::get('mail_host', config('mail.mailers.smtp.host', '')),
            'port' => static::get('mail_port', config('mail.mailers.smtp.port', '587')),
            'username' => static::get('mail_username', config('mail.mailers.smtp.username', '')),
            'password' => static::get('mail_password', ''),
            'encryption' => static::get('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'from_address' => static::get('mail_from_address', config('mail.from.address', '')),
            'from_name' => static::get('mail_from_name', config('mail.from.name', 'TVET Revision')),
        ];
    }

    /**
     * Set email/SMTP settings
     */
    public static function setEmailSettings(array $settings)
    {
        $allowedKeys = ['driver', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                // Only update password if provided (not empty)
                if ($key === 'password' && empty($value)) {
                    continue;
                }
                static::set("mail_{$key}", $value ?? '', 'mail');
            }
        }

        return static::getEmailSettings();
    }

    /**
     * Check if email is configured
     */
    public static function emailConfigured()
    {
        $settings = static::getEmailSettings();
        return !empty($settings['host']) && !empty($settings['from_address']);
    }

    /**
     * Get site info settings
     */
    public static function getSiteInfoSettings()
    {
        return [
            'name' => static::get('site_name', 'TVET Revision'),
            'tagline' => static::get('site_tagline', 'Your comprehensive platform for TVET exam preparation'),
            'description' => static::get('site_description', 'Access thousands of past papers, study materials, and practice questions to ace your KNEC examinations.'),
        ];
    }

    /**
     * Set site info settings
     */
    public static function setSiteInfoSettings(array $settings)
    {
        $allowedKeys = ['name', 'tagline', 'description'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set("site_{$key}", $value ?? '', 'site');
            }
        }

        return static::getSiteInfoSettings();
    }

    /**
     * Get branding settings (logo, favicon)
     */
    public static function getBrandingSettings()
    {
        return [
            'logo' => static::get('site_logo', ''),
            'favicon' => static::get('site_favicon', ''),
            'logo_alt' => static::get('site_logo_alt', config('app.name', 'TVET Revision')),
        ];
    }

    /**
     * Set branding settings
     */
    public static function setBrandingSettings(array $settings)
    {
        $allowedKeys = ['logo', 'favicon', 'logo_alt'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set("site_{$key}", $value ?? '', 'branding');
            }
        }

        return static::getBrandingSettings();
    }

    /**
     * Get the site logo URL or null if not set
     */
    public static function getLogo()
    {
        $logo = static::get('site_logo', '');
        return !empty($logo) ? $logo : null;
    }

    /**
     * Get the site favicon URL or null if not set
     */
    public static function getFavicon()
    {
        $favicon = static::get('site_favicon', '');
        return !empty($favicon) ? $favicon : null;
    }

    /**
     * Get reCAPTCHA settings
     */
    public static function getRecaptchaSettings()
    {
        return [
            'enabled' => static::get('recaptcha_enabled', '0') === '1',
            'site_key' => static::get('recaptcha_site_key', ''),
            'secret_key' => static::get('recaptcha_secret_key', ''),
            'version' => static::get('recaptcha_version', 'v2'),
            'login_enabled' => static::get('recaptcha_login_enabled', '1') === '1',
            'register_enabled' => static::get('recaptcha_register_enabled', '1') === '1',
            'contact_enabled' => static::get('recaptcha_contact_enabled', '1') === '1',
            'password_reset_enabled' => static::get('recaptcha_password_reset_enabled', '1') === '1',
        ];
    }

    /**
     * Set reCAPTCHA settings
     */
    public static function setRecaptchaSettings(array $settings)
    {
        $allowedKeys = [
            'enabled', 'site_key', 'secret_key', 'version',
            'login_enabled', 'register_enabled', 'contact_enabled', 'password_reset_enabled'
        ];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set("recaptcha_{$key}", $value ?? '', 'recaptcha');
            }
        }

        return static::getRecaptchaSettings();
    }

    /**
     * Check if reCAPTCHA is configured and enabled
     */
    public static function recaptchaConfigured()
    {
        $settings = static::getRecaptchaSettings();
        return $settings['enabled'] && !empty($settings['site_key']) && !empty($settings['secret_key']);
    }

    /**
     * Check if reCAPTCHA is enabled for a specific form
     */
    public static function recaptchaEnabledFor($form)
    {
        if (!static::recaptchaConfigured()) {
            return false;
        }

        $settings = static::getRecaptchaSettings();
        $key = "{$form}_enabled";

        return $settings[$key] ?? false;
    }

    /**
     * Get reCAPTCHA site key (for frontend use)
     */
    public static function getRecaptchaSiteKey()
    {
        return static::get('recaptcha_site_key', '');
    }

    /**
     * Get reCAPTCHA secret key (for backend verification)
     */
    public static function getRecaptchaSecretKey()
    {
        return static::get('recaptcha_secret_key', '');
    }

    /**
     * Get security settings
     */
    public static function getSecuritySettings()
    {
        return [
            'email_verification_required' => static::get('email_verification_required', '1') === '1',
        ];
    }

    /**
     * Set security settings
     */
    public static function setSecuritySettings(array $settings)
    {
        $allowedKeys = ['email_verification_required'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set($key, $value ?? '', 'security');
            }
        }

        return static::getSecuritySettings();
    }

    /**
     * Check if email verification is required
     */
    public static function emailVerificationRequired()
    {
        return static::get('email_verification_required', '1') === '1';
    }

    /**
     * Get subscription/monetization settings
     */
    public static function getSubscriptionSettings()
    {
        return [
            'subscriptions_enabled' => static::get('subscriptions_enabled', '0') === '1',
            'subscription_notice' => static::get('subscription_notice', 'Premium subscriptions are currently unavailable. Please check back later.'),
        ];
    }

    /**
     * Set subscription settings
     */
    public static function setSubscriptionSettings(array $settings)
    {
        $allowedKeys = ['subscriptions_enabled', 'subscription_notice'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set($key, $value ?? '', 'subscription');
            }
        }

        return static::getSubscriptionSettings();
    }

    /**
     * Check if subscriptions are enabled
     */
    public static function subscriptionsEnabled()
    {
        return static::get('subscriptions_enabled', '0') === '1';
    }

    /**
     * Get the subscription disabled notice message
     */
    public static function getSubscriptionNotice()
    {
        return static::get('subscription_notice', 'Premium subscriptions are currently unavailable. Please check back later.');
    }

    /**
     * Get appearance/theme settings
     */
    public static function getAppearanceSettings()
    {
        return [
            'dark_mode_enabled' => static::get('dark_mode_enabled', '1') === '1',
            'default_theme' => static::get('default_theme', 'light'),
        ];
    }

    /**
     * Set appearance settings
     */
    public static function setAppearanceSettings(array $settings)
    {
        $allowedKeys = ['dark_mode_enabled', 'default_theme'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set($key, $value ?? '', 'appearance');
            }
        }

        return static::getAppearanceSettings();
    }

    /**
     * Check if dark mode toggle is enabled for users
     */
    public static function darkModeEnabled()
    {
        return static::get('dark_mode_enabled', '1') === '1';
    }

    /**
     * Get PWA/Offline settings
     */
    public static function getPwaSettings()
    {
        return [
            'pwa_enabled' => static::get('pwa_enabled', '1') === '1',
            'pwa_requires_subscription' => static::get('pwa_requires_subscription', '1') === '1',
        ];
    }

    /**
     * Set PWA settings
     */
    public static function setPwaSettings(array $settings)
    {
        $allowedKeys = ['pwa_enabled', 'pwa_requires_subscription'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set($key, $value ?? '', 'pwa');
            }
        }

        return static::getPwaSettings();
    }

    /**
     * Check if PWA is enabled
     */
    public static function pwaEnabled()
    {
        return static::get('pwa_enabled', '1') === '1';
    }

    /**
     * Check if PWA requires subscription
     */
    public static function pwaRequiresSubscription()
    {
        return static::get('pwa_requires_subscription', '1') === '1';
    }

    /**
     * Get hero section settings
     */
    public static function getHeroSettings()
    {
        return [
            'heading' => static::get('hero_heading', 'Kenya KNEC TVET Exam Preparation Made Simple'),
            'subheading' => static::get('hero_subheading', 'Master your KNEC exams with past papers, detailed answers, and progress tracking. Study smarter, not harder.'),
            'primary_button_text' => static::get('hero_primary_button_text', 'Browse Courses'),
            'secondary_button_text' => static::get('hero_secondary_button_text', 'Start Free'),
            'cta_heading' => static::get('hero_cta_heading', 'Ready to Ace Your Exams?'),
            'cta_subheading' => static::get('hero_cta_subheading', 'Join thousands of students preparing smarter with TVET Revision.'),
        ];
    }

    /**
     * Set hero section settings
     */
    public static function setHeroSettings(array $settings)
    {
        $allowedKeys = ['heading', 'subheading', 'primary_button_text', 'secondary_button_text', 'cta_heading', 'cta_subheading'];

        foreach ($settings as $key => $value) {
            if (in_array($key, $allowedKeys)) {
                static::set("hero_{$key}", $value ?? '', 'hero');
            }
        }

        return static::getHeroSettings();
    }
}
