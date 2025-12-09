<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSettings;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * General settings page.
     */
    public function general()
    {
        return view('admin.settings.general');
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        // In a real application, you would store these in a settings table
        return back()->with('success', 'General settings updated successfully!');
    }

    /**
     * Contact information settings page.
     */
    public function contact()
    {
        $contactSettings = SiteSetting::getContactSettings();
        return view('admin.settings.contact', compact('contactSettings'));
    }

    /**
     * Update contact settings.
     */
    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string|max:255',
            'contact_address_line2' => 'nullable|string|max:255',
            'contact_working_hours' => 'nullable|string|max:100',
        ]);

        SiteSetting::setContactSettings([
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
            'address' => $validated['contact_address'],
            'address_line2' => $validated['contact_address_line2'] ?? '',
            'working_hours' => $validated['contact_working_hours'] ?? '',
        ]);

        return back()->with('success', 'Contact information updated successfully!');
    }

    /**
     * Social media settings page.
     */
    public function social()
    {
        $socialSettings = SiteSetting::getSocialSettings();
        return view('admin.settings.social', compact('socialSettings'));
    }

    /**
     * Update social media settings.
     */
    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_whatsapp' => 'nullable|string|max:50',
        ]);

        SiteSetting::setSocialSettings([
            'facebook' => $validated['social_facebook'] ?? '',
            'twitter' => $validated['social_twitter'] ?? '',
            'instagram' => $validated['social_instagram'] ?? '',
            'youtube' => $validated['social_youtube'] ?? '',
            'tiktok' => $validated['social_tiktok'] ?? '',
            'linkedin' => $validated['social_linkedin'] ?? '',
            'whatsapp' => $validated['social_whatsapp'] ?? '',
        ]);

        return back()->with('success', 'Social media links updated successfully!');
    }

    /**
     * Payment settings page.
     */
    public function payments()
    {
        $mpesaSettings = SiteSetting::getMpesaSettings();
        return view('admin.settings.payments', compact('mpesaSettings'));
    }

    /**
     * Update payment settings.
     */
    public function updatePayments(Request $request)
    {
        $validated = $request->validate([
            'mpesa_consumer_key' => 'nullable|string|max:255',
            'mpesa_consumer_secret' => 'nullable|string|max:255',
            'mpesa_shortcode' => 'nullable|string|max:50',
            'mpesa_passkey' => 'nullable|string|max:255',
            'mpesa_environment' => 'required|in:sandbox,production',
        ]);

        SiteSetting::setMpesaSettings([
            'consumer_key' => $validated['mpesa_consumer_key'] ?? '',
            'consumer_secret' => $validated['mpesa_consumer_secret'] ?? '',
            'shortcode' => $validated['mpesa_shortcode'] ?? '',
            'passkey' => $validated['mpesa_passkey'] ?? '',
            'environment' => $validated['mpesa_environment'],
            'callback_url' => url('/api/mpesa/callback'),
        ]);

        return back()->with('success', 'M-Pesa settings updated successfully!');
    }

    /**
     * Email settings page.
     */
    public function email()
    {
        $emailSettings = SiteSetting::getEmailSettings();
        return view('admin.settings.email', compact('emailSettings'));
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses,log',
            'mail_host' => 'required_if:mail_driver,smtp|nullable|string|max:255',
            'mail_port' => 'required_if:mail_driver,smtp|nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        SiteSetting::setEmailSettings([
            'driver' => $validated['mail_driver'],
            'host' => $validated['mail_host'] ?? '',
            'port' => $validated['mail_port'] ?? '587',
            'username' => $validated['mail_username'] ?? '',
            'password' => $validated['mail_password'] ?? '',
            'encryption' => $validated['mail_encryption'] ?? 'tls',
            'from_address' => $validated['mail_from_address'],
            'from_name' => $validated['mail_from_name'],
        ]);

        return back()->with('success', 'Email settings updated successfully!');
    }

    /**
     * Test email configuration.
     */
    public function testEmail(Request $request)
    {
        try {
            $emailSettings = SiteSetting::getEmailSettings();

            if (empty($emailSettings['host']) || empty($emailSettings['from_address'])) {
                return response()->json(['success' => false, 'message' => 'Email settings are not configured.'], 400);
            }

            config([
                'mail.default' => $emailSettings['driver'],
                'mail.mailers.smtp.host' => $emailSettings['host'],
                'mail.mailers.smtp.port' => $emailSettings['port'],
                'mail.mailers.smtp.username' => $emailSettings['username'],
                'mail.mailers.smtp.password' => $emailSettings['password'],
                'mail.mailers.smtp.encryption' => $emailSettings['encryption'] ?: null,
                'mail.from.address' => $emailSettings['from_address'],
                'mail.from.name' => $emailSettings['from_name'],
            ]);

            $adminEmail = Auth::user()->email;

            Mail::raw(
                "This is a test email from TVET Revision.\n\nIf you received this, your email settings are configured correctly!\n\nSent at: " . now()->format('F d, Y H:i:s'),
                function ($message) use ($adminEmail, $emailSettings) {
                    $message->to($adminEmail)
                            ->subject('TVET Revision - Test Email')
                            ->from($emailSettings['from_address'], $emailSettings['from_name']);
                }
            );

            return response()->json(['success' => true, 'message' => "Test email sent to {$adminEmail}. Please check your inbox."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send test email: ' . $e->getMessage()], 400);
        }
    }

    /**
     * AI settings page.
     */
    public function ai()
    {
        $aiSettings = SiteSetting::getAiSettings();
        return view('admin.settings.ai', compact('aiSettings'));
    }

    /**
     * Update AI settings.
     */
    public function updateAi(Request $request)
    {
        $validated = $request->validate([
            'ai_provider' => 'required|in:openai,anthropic',
            'ai_api_key' => 'nullable|string|max:255',
            'ai_model' => 'required|string|max:100',
            'ai_max_tokens' => 'required|integer|min:100|max:4000',
            'ai_temperature' => 'required|numeric|min:0|max:1',
            'ai_system_prompt' => 'nullable|string|max:2000',
        ]);

        SiteSetting::setAiSettings([
            'provider' => $validated['ai_provider'],
            'api_key' => $validated['ai_api_key'] ?? '',
            'model' => $validated['ai_model'],
            'max_tokens' => $validated['ai_max_tokens'],
            'temperature' => $validated['ai_temperature'],
            'system_prompt' => $validated['ai_system_prompt'] ?? '',
        ]);

        return back()->with('success', 'AI settings updated successfully!');
    }

    /**
     * Test AI connection.
     */
    public function testAi(Request $request)
    {
        $aiService = new \App\Services\AiService();
        $result = $aiService->testConnection();

        if ($result['success']) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        }

        return response()->json(['success' => false, 'message' => $result['message']], 400);
    }

    /**
     * Disconnect AI (remove API key and reset settings).
     */
    public function disconnectAi()
    {
        // Clear all AI-related settings
        SiteSetting::set('ai_api_key', '');
        SiteSetting::set('ai_provider', 'openai');
        SiteSetting::set('ai_model', 'gpt-3.5-turbo');
        SiteSetting::set('ai_max_tokens', '1000');
        SiteSetting::set('ai_temperature', '0.7');
        SiteSetting::set('ai_system_prompt', '');

        return redirect()->route('admin.settings.ai')
            ->with('success', 'AI has been disconnected. All AI features are now disabled.');
    }

    /**
     * Maintenance settings page.
     */
    public function maintenance()
    {
        $maintenanceSettings = MaintenanceSettings::getSettings();
        return view('admin.settings.maintenance', compact('maintenanceSettings'));
    }

    /**
     * Update maintenance page settings.
     */
    public function updateMaintenancePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'message' => 'required|string',
            'expected_duration' => 'required|string|max:100',
            'support_email' => 'nullable|email|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ]);

        $settings = MaintenanceSettings::getSettings();
        $settings->update($validated);

        return back()->with('success', 'Maintenance page settings updated successfully!');
    }

    /**
     * Enable maintenance mode.
     */
    public function enableMaintenance()
    {
        Artisan::call('down', ['--redirect' => '/']);
        return back()->with('success', 'Maintenance mode enabled! Admin panel remains accessible.');
    }

    /**
     * Disable maintenance mode.
     */
    public function disableMaintenance()
    {
        Artisan::call('up');
        return back()->with('success', 'Maintenance mode disabled!');
    }

    /**
     * System info and cache management page.
     */
    public function system()
    {
        return view('admin.settings.system');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return back()->with('success', 'Application cache cleared successfully!');
    }

    /**
     * Clear config cache.
     */
    public function clearConfig()
    {
        Artisan::call('config:clear');
        return back()->with('success', 'Configuration cache cleared successfully!');
    }

    /**
     * Clear route cache.
     */
    public function clearRoutes()
    {
        Artisan::call('route:clear');
        return back()->with('success', 'Route cache cleared successfully!');
    }

    /**
     * Branding settings page (logo, favicon).
     */
    public function branding()
    {
        $brandingSettings = SiteSetting::getBrandingSettings();
        return view('admin.settings.branding', compact('brandingSettings'));
    }

    /**
     * Update branding settings (logo, favicon).
     */
    public function updateBranding(Request $request)
    {
        // Removed SVG from allowed types due to XSS risk (SVG can contain JavaScript)
        $validated = $request->validate([
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon' => 'nullable|file|mimes:png,ico|max:512',
            'logo_alt' => 'nullable|string|max:255',
            'remove_logo' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
        ]);

        $settings = SiteSetting::getBrandingSettings();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if (!empty($settings['logo'])) {
                $oldPath = str_replace('/storage/', '', $settings['logo']);
                Storage::disk('public')->delete($oldPath);
            }

            $logoPath = $request->file('logo')->store('branding', 'public');
            SiteSetting::set('site_logo', '/storage/' . $logoPath, 'branding');
        } elseif ($request->boolean('remove_logo') && !empty($settings['logo'])) {
            // Remove logo
            $oldPath = str_replace('/storage/', '', $settings['logo']);
            Storage::disk('public')->delete($oldPath);
            SiteSetting::set('site_logo', '', 'branding');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if (!empty($settings['favicon'])) {
                $oldPath = str_replace('/storage/', '', $settings['favicon']);
                Storage::disk('public')->delete($oldPath);
            }

            $faviconPath = $request->file('favicon')->store('branding', 'public');
            SiteSetting::set('site_favicon', '/storage/' . $faviconPath, 'branding');
        } elseif ($request->boolean('remove_favicon') && !empty($settings['favicon'])) {
            // Remove favicon
            $oldPath = str_replace('/storage/', '', $settings['favicon']);
            Storage::disk('public')->delete($oldPath);
            SiteSetting::set('site_favicon', '', 'branding');
        }

        // Update logo alt text
        if ($request->filled('logo_alt')) {
            SiteSetting::set('site_logo_alt', $validated['logo_alt'], 'branding');
        }

        return back()->with('success', 'Branding settings updated successfully!');
    }

    /**
     * Security settings page (reCAPTCHA and Email Verification).
     */
    public function recaptcha()
    {
        $recaptchaSettings = SiteSetting::getRecaptchaSettings();
        $securitySettings = SiteSetting::getSecuritySettings();
        return view('admin.settings.recaptcha', compact('recaptchaSettings', 'securitySettings'));
    }

    /**
     * Update security settings (email verification).
     */
    public function updateSecurity(Request $request)
    {
        SiteSetting::setSecuritySettings([
            'email_verification_required' => $request->boolean('email_verification_required') ? '1' : '0',
        ]);

        return back()->with('success', 'Security settings updated successfully!');
    }

    /**
     * Update reCAPTCHA settings.
     */
    public function updateRecaptcha(Request $request)
    {
        $validated = $request->validate([
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'recaptcha_version' => 'required|in:v2,v3',
            'recaptcha_enabled' => 'nullable|boolean',
            'recaptcha_login_enabled' => 'nullable|boolean',
            'recaptcha_register_enabled' => 'nullable|boolean',
            'recaptcha_contact_enabled' => 'nullable|boolean',
            'recaptcha_password_reset_enabled' => 'nullable|boolean',
        ]);

        SiteSetting::setRecaptchaSettings([
            'enabled' => $request->boolean('recaptcha_enabled') ? '1' : '0',
            'site_key' => $validated['recaptcha_site_key'] ?? '',
            'secret_key' => $validated['recaptcha_secret_key'] ?? '',
            'version' => $validated['recaptcha_version'],
            'login_enabled' => $request->boolean('recaptcha_login_enabled') ? '1' : '0',
            'register_enabled' => $request->boolean('recaptcha_register_enabled') ? '1' : '0',
            'contact_enabled' => $request->boolean('recaptcha_contact_enabled') ? '1' : '0',
            'password_reset_enabled' => $request->boolean('recaptcha_password_reset_enabled') ? '1' : '0',
        ]);

        return back()->with('success', 'reCAPTCHA settings updated successfully!');
    }

    /**
     * Features settings page (Subscriptions, Appearance, PWA).
     */
    public function features()
    {
        $subscriptionSettings = SiteSetting::getSubscriptionSettings();
        $appearanceSettings = SiteSetting::getAppearanceSettings();
        $pwaSettings = SiteSetting::getPwaSettings();

        return view('admin.settings.features', compact('subscriptionSettings', 'appearanceSettings', 'pwaSettings'));
    }

    /**
     * Update subscription settings.
     */
    public function updateSubscription(Request $request)
    {
        $validated = $request->validate([
            'subscription_notice' => 'nullable|string|max:500',
        ]);

        SiteSetting::setSubscriptionSettings([
            'subscriptions_enabled' => $request->boolean('subscriptions_enabled') ? '1' : '0',
            'subscription_notice' => $validated['subscription_notice'] ?? '',
        ]);

        return back()->with('success', 'Subscription settings updated successfully!');
    }

    /**
     * Update appearance settings.
     */
    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'default_theme' => 'required|in:light,dark,system',
        ]);

        SiteSetting::setAppearanceSettings([
            'dark_mode_enabled' => $request->boolean('dark_mode_enabled') ? '1' : '0',
            'default_theme' => $validated['default_theme'],
        ]);

        return back()->with('success', 'Appearance settings updated successfully!');
    }

    /**
     * Update PWA settings.
     */
    public function updatePwa(Request $request)
    {
        SiteSetting::setPwaSettings([
            'pwa_enabled' => $request->boolean('pwa_enabled') ? '1' : '0',
            'pwa_requires_subscription' => $request->boolean('pwa_requires_subscription') ? '1' : '0',
        ]);

        return back()->with('success', 'PWA/Offline settings updated successfully!');
    }

    /**
     * Hero section settings page.
     */
    public function hero()
    {
        $heroSettings = SiteSetting::getHeroSettings();
        return view('admin.settings.hero', compact('heroSettings'));
    }

    /**
     * Update hero section settings.
     */
    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'hero_heading' => 'required|string|max:255',
            'hero_subheading' => 'required|string|max:500',
            'hero_primary_button_text' => 'required|string|max:50',
            'hero_secondary_button_text' => 'required|string|max:50',
            'hero_cta_heading' => 'required|string|max:255',
            'hero_cta_subheading' => 'required|string|max:500',
        ]);

        SiteSetting::setHeroSettings([
            'heading' => $validated['hero_heading'],
            'subheading' => $validated['hero_subheading'],
            'primary_button_text' => $validated['hero_primary_button_text'],
            'secondary_button_text' => $validated['hero_secondary_button_text'],
            'cta_heading' => $validated['hero_cta_heading'],
            'cta_subheading' => $validated['hero_cta_subheading'],
        ]);

        return back()->with('success', 'Hero section settings updated successfully!');
    }
}
