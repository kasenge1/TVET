<?php

namespace App\Observers;

use App\Models\User;
use App\Services\EmailService;
use App\Services\NotificationService;

class UserObserver
{
    protected NotificationService $notificationService;
    protected EmailService $emailService;

    public function __construct(NotificationService $notificationService, EmailService $emailService)
    {
        $this->notificationService = $notificationService;
        $this->emailService = $emailService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Send welcome notification to new user (in-app)
        $this->notificationService->sendWelcome($user);

        // Send welcome email with rich HTML template
        $this->emailService->sendWelcomeEmail($user);

        // Notify admins of new registration (only for students)
        if ($user->role === 'student') {
            $this->notificationService->notifyNewUser($user);
        }
    }
}
