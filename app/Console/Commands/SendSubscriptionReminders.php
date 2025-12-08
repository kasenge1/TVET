<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\EmailService;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-reminders
                            {--days=3 : Days before expiry to send reminder}
                            {--dry-run : Run without actually sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send subscription expiry reminders to users';

    protected EmailService $emailService;
    protected NotificationService $notificationService;

    public function __construct(EmailService $emailService, NotificationService $notificationService)
    {
        parent::__construct();
        $this->emailService = $emailService;
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $this->info("Looking for subscriptions expiring in {$days} days...");

        // Find subscriptions expiring in exactly X days
        $targetDate = now()->addDays($days)->startOfDay();
        $nextDay = $targetDate->copy()->addDay();

        $subscriptions = Subscription::with(['user', 'package'])
            ->where('status', 'active')
            ->whereBetween('expires_at', [$targetDate, $nextDay])
            ->whereHas('user')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions found expiring in ' . $days . ' days.');
            return Command::SUCCESS;
        }

        $this->info("Found {$subscriptions->count()} subscription(s) to notify.");

        $successCount = 0;
        $failCount = 0;

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            $this->line("Processing: {$user->name} ({$user->email})");

            if ($dryRun) {
                $this->info("  [DRY RUN] Would send reminder email");
                $successCount++;
                continue;
            }

            try {
                // Send in-app notification
                $this->notificationService->notifySubscriptionExpiring($user, $days);

                // Send email reminder
                $result = $this->emailService->sendSubscriptionExpiryReminder($user, [
                    'package_name' => $subscription->package->name ?? 'Premium',
                    'expires_at' => $subscription->expires_at,
                    'days_left' => $days,
                ]);

                if ($result) {
                    $this->info("  Email sent successfully");
                    $successCount++;
                } else {
                    $this->warn("  Email not sent (email not configured)");
                    $successCount++; // Still count as success since notification was sent
                }
            } catch (\Exception $e) {
                $this->error("  Failed: {$e->getMessage()}");
                Log::error('Failed to send subscription reminder', [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                $failCount++;
            }
        }

        $this->newLine();
        $this->info("Completed: {$successCount} succeeded, {$failCount} failed");

        return $failCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
