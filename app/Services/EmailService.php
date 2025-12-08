<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Configure mail settings from database
     */
    protected function configureMailer(): void
    {
        $settings = SiteSetting::getEmailSettings();

        if (!empty($settings['host'])) {
            config([
                'mail.default' => $settings['driver'],
                'mail.mailers.smtp.host' => $settings['host'],
                'mail.mailers.smtp.port' => $settings['port'],
                'mail.mailers.smtp.username' => $settings['username'],
                'mail.mailers.smtp.password' => $settings['password'],
                'mail.mailers.smtp.encryption' => $settings['encryption'] ?: null,
                'mail.from.address' => $settings['from_address'],
                'mail.from.name' => $settings['from_name'],
            ]);
        }
    }

    /**
     * Check if email is configured
     */
    public function isConfigured(): bool
    {
        return SiteSetting::emailConfigured();
    }

    /**
     * Send subscription confirmation email
     */
    public function sendSubscriptionConfirmation(User $user, array $subscriptionData): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Email not configured - skipping subscription confirmation email');
            return false;
        }

        try {
            $this->configureMailer();
            $settings = SiteSetting::getEmailSettings();

            Mail::send('emails.subscription-confirmed', [
                'user' => $user,
                'subscription' => $subscriptionData,
            ], function ($message) use ($user, $settings) {
                $message->to($user->email, $user->name)
                    ->from($settings['from_address'], $settings['from_name'])
                    ->subject('Subscription Confirmed - TVET Revision');
            });

            Log::info('Subscription confirmation email sent', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send subscription confirmation email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send subscription expiry reminder email
     */
    public function sendSubscriptionExpiryReminder(User $user, array $subscriptionData): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $this->configureMailer();
            $settings = SiteSetting::getEmailSettings();

            Mail::send('emails.subscription-expiring', [
                'user' => $user,
                'subscription' => $subscriptionData,
            ], function ($message) use ($user, $settings) {
                $message->to($user->email, $user->name)
                    ->from($settings['from_address'], $settings['from_name'])
                    ->subject('Your Subscription is Expiring Soon - TVET Revision');
            });

            Log::info('Subscription expiry reminder email sent', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send subscription expiry reminder email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send subscription expired email
     */
    public function sendSubscriptionExpired(User $user): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $this->configureMailer();
            $settings = SiteSetting::getEmailSettings();

            Mail::send('emails.subscription-expired', [
                'user' => $user,
            ], function ($message) use ($user, $settings) {
                $message->to($user->email, $user->name)
                    ->from($settings['from_address'], $settings['from_name'])
                    ->subject('Your Subscription Has Expired - TVET Revision');
            });

            Log::info('Subscription expired email sent', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send subscription expired email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $this->configureMailer();
            $settings = SiteSetting::getEmailSettings();

            Mail::send('emails.welcome', [
                'user' => $user,
            ], function ($message) use ($user, $settings) {
                $message->to($user->email, $user->name)
                    ->from($settings['from_address'], $settings['from_name'])
                    ->subject('Welcome to TVET Revision!');
            });

            Log::info('Welcome email sent', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send payment failed email
     */
    public function sendPaymentFailed(User $user, string $reason = ''): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $this->configureMailer();
            $settings = SiteSetting::getEmailSettings();

            Mail::send('emails.payment-failed', [
                'user' => $user,
                'reason' => $reason,
            ], function ($message) use ($user, $settings) {
                $message->to($user->email, $user->name)
                    ->from($settings['from_address'], $settings['from_name'])
                    ->subject('Payment Failed - TVET Revision');
            });

            Log::info('Payment failed email sent', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment failed email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
