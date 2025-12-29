<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a user.
     */
    public function send(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        string $iconColor = 'primary',
        ?array $data = null,
        bool $sendEmail = true
    ): ?Notification {
        // Check user preferences
        $preference = NotificationPreference::getPreference($user->id, $type);

        // Create in-app notification if enabled
        $notification = null;
        if ($preference->in_app) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'icon_color' => $iconColor,
                'action_url' => $actionUrl,
                'data' => $data,
            ]);
        }

        // Send email if enabled
        if ($sendEmail && $preference->email && $user->email) {
            $this->sendEmail($user, $notification ?? $this->createTemporaryNotification(
                $type, $title, $message, $actionUrl, $icon, $iconColor
            ));

            if ($notification) {
                $notification->update(['email_sent' => true]);
            }
        }

        return $notification;
    }

    /**
     * Send notification to multiple users.
     */
    public function sendToMany(
        array $userIds,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        string $iconColor = 'primary',
        ?array $data = null,
        bool $sendEmail = true
    ): int {
        $count = 0;
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $notification = $this->send(
                $user, $type, $title, $message,
                $actionUrl, $icon, $iconColor, $data, $sendEmail
            );
            if ($notification) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Send notification to all admins.
     */
    public function sendToAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        string $iconColor = 'primary',
        ?array $data = null,
        bool $sendEmail = true
    ): int {
        // Get admins using Spatie roles (super-admin and admin)
        $adminIds = User::role(['super-admin', 'admin'])->pluck('id')->toArray();
        return $this->sendToMany(
            $adminIds, $type, $title, $message,
            $actionUrl, $icon, $iconColor, $data, $sendEmail
        );
    }

    /**
     * Send notification to students enrolled in a course.
     */
    public function sendToCourseStudents(
        int $courseId,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        string $iconColor = 'primary',
        ?array $data = null,
        bool $sendEmail = true
    ): int {
        // Get students enrolled in the course via enrollments table
        $studentIds = User::where('role', 'student')
            ->whereHas('enrollment', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->pluck('id')
            ->toArray();

        return $this->sendToMany(
            $studentIds, $type, $title, $message,
            $actionUrl, $icon, $iconColor, $data, $sendEmail
        );
    }

    /**
     * Notify admins of new user registration.
     */
    public function notifyNewUser(User $newUser): void
    {
        $this->sendToAdmins(
            Notification::TYPE_NEW_USER,
            'New User Registration',
            "{$newUser->name} has registered as a new {$newUser->role}.",
            route('admin.users.index'),
            'person-plus-fill',
            'success',
            ['user_id' => $newUser->id, 'user_name' => $newUser->name],
            true
        );
    }

    /**
     * Notify admins of new subscription.
     */
    public function notifyNewSubscription(User $user, $subscription): void
    {
        $packageName = $subscription->package->name ?? 'Premium';

        $this->sendToAdmins(
            Notification::TYPE_NEW_SUBSCRIPTION,
            'New Subscription',
            "{$user->name} has subscribed to {$packageName}.",
            route('admin.subscriptions.index'),
            'credit-card-fill',
            'success',
            ['user_id' => $user->id, 'subscription_id' => $subscription->id],
            true
        );
    }

    /**
     * Notify students of new question in their course.
     */
    public function notifyNewQuestion($question): void
    {
        $unit = $question->unit;
        if (!$unit || !$unit->course_id) {
            return;
        }

        // Use the learn route for frontend navigation
        $actionUrl = route('learn.question', [$unit->slug, $question->slug]);

        $this->sendToCourseStudents(
            $unit->course_id,
            Notification::TYPE_NEW_QUESTION,
            'New Question Added',
            "A new question has been added to {$unit->title}.",
            $actionUrl,
            'question-circle-fill',
            'primary',
            ['question_id' => $question->id, 'unit_id' => $unit->id, 'unit_slug' => $unit->slug, 'question_slug' => $question->slug],
            false // Don't send email for each question
        );
    }

    /**
     * Notify students of new unit in their course.
     */
    public function notifyNewUnit($unit): void
    {
        if (!$unit->course_id) {
            return;
        }

        // Use the learn route for frontend navigation
        $actionUrl = route('learn.unit', $unit->slug);

        $this->sendToCourseStudents(
            $unit->course_id,
            Notification::TYPE_NEW_UNIT,
            'New Unit Added',
            "A new unit '{$unit->title}' has been added to your course.",
            $actionUrl,
            'collection-fill',
            'info',
            ['unit_id' => $unit->id, 'unit_slug' => $unit->slug],
            true
        );
    }

    /**
     * Send welcome notification to new user.
     */
    public function sendWelcome(User $user): void
    {
        // Use appropriate route based on role
        $actionUrl = $user->hasAnyRole(['super-admin', 'admin', 'content-manager', 'question-editor'])
            ? route('admin.dashboard')
            : route('learn.index');

        $this->send(
            $user,
            Notification::TYPE_WELCOME,
            'Welcome to TVET Revision!',
            "We're excited to have you on board. Start exploring and boost your exam preparation!",
            $actionUrl,
            'hand-thumbs-up-fill',
            'success',
            null,
            true
        );
    }

    /**
     * Notify user of expiring subscription.
     */
    public function notifySubscriptionExpiring(User $user, int $daysLeft): void
    {
        $this->send(
            $user,
            Notification::TYPE_SUBSCRIPTION_EXPIRING,
            'Subscription Expiring Soon',
            "Your premium subscription will expire in {$daysLeft} days. Renew now to continue enjoying premium features.",
            route('learn.subscription'),
            'exclamation-triangle-fill',
            'warning',
            ['days_left' => $daysLeft],
            true
        );
    }

    /**
     * Notify user of expired subscription.
     */
    public function notifySubscriptionExpired(User $user): void
    {
        $this->send(
            $user,
            Notification::TYPE_SUBSCRIPTION_EXPIRED,
            'Subscription Expired',
            "Your premium subscription has expired. Renew now to regain access to premium features.",
            route('learn.subscription'),
            'x-circle-fill',
            'danger',
            null,
            true
        );
    }

    /**
     * Send email notification.
     */
    protected function sendEmail(User $user, Notification $notification): void
    {
        try {
            Mail::to($user->email)->queue(new NotificationMail($notification, $user));
        } catch (\Exception $e) {
            Log::error('Failed to send notification email: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'notification_type' => $notification->type,
            ]);
        }
    }

    /**
     * Create a temporary notification object for email.
     */
    protected function createTemporaryNotification(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl,
        ?string $icon,
        string $iconColor
    ): Notification {
        $notification = new Notification();
        $notification->type = $type;
        $notification->title = $title;
        $notification->message = $message;
        $notification->action_url = $actionUrl;
        $notification->icon = $icon;
        $notification->icon_color = $iconColor;
        return $notification;
    }
}
