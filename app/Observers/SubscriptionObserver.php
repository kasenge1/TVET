<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Services\EmailService;
use App\Services\NotificationService;

class SubscriptionObserver
{
    protected NotificationService $notificationService;
    protected EmailService $emailService;

    public function __construct(NotificationService $notificationService, EmailService $emailService)
    {
        $this->notificationService = $notificationService;
        $this->emailService = $emailService;
    }

    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        // Only notify for active subscriptions
        if ($subscription->status === 'active' && $subscription->user) {
            // In-app notification to admins
            $this->notificationService->notifyNewSubscription(
                $subscription->user,
                $subscription
            );

            // Send confirmation email to user
            $this->sendConfirmationEmail($subscription);
        }
    }

    /**
     * Handle the Subscription "updated" event.
     */
    public function updated(Subscription $subscription): void
    {
        // Check if subscription just became active
        if ($subscription->isDirty('status') && $subscription->status === 'active') {
            // In-app notification to admins
            $this->notificationService->notifyNewSubscription(
                $subscription->user,
                $subscription
            );

            // Send confirmation email to user
            $this->sendConfirmationEmail($subscription);
        }

        // Check if subscription just expired
        if ($subscription->isDirty('status') && $subscription->status === 'expired' && $subscription->user) {
            $this->emailService->sendSubscriptionExpired($subscription->user);
        }
    }

    /**
     * Send subscription confirmation email.
     */
    protected function sendConfirmationEmail(Subscription $subscription): void
    {
        if (!$subscription->user) {
            return;
        }

        $this->emailService->sendSubscriptionConfirmation($subscription->user, [
            'package_name' => $subscription->package->name ?? 'Premium',
            'amount' => $subscription->amount,
            'expires_at' => $subscription->expires_at,
            'transaction_id' => $subscription->transaction_id,
        ]);
    }
}
