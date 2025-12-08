<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\EmailService;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire
                            {--dry-run : Run without actually updating subscriptions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired subscriptions as expired and notify users';

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
        $dryRun = $this->option('dry-run');

        $this->info("Looking for expired subscriptions...");

        // Find active subscriptions that have expired
        $subscriptions = Subscription::with(['user', 'package'])
            ->where('status', 'active')
            ->where('expires_at', '<', now())
            ->whereHas('user')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No expired subscriptions found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$subscriptions->count()} expired subscription(s) to process.");

        $successCount = 0;
        $failCount = 0;

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            $this->line("Processing: {$user->name} ({$user->email}) - Expired: {$subscription->expires_at->format('Y-m-d H:i')}");

            if ($dryRun) {
                $this->info("  [DRY RUN] Would mark as expired and notify user");
                $successCount++;
                continue;
            }

            try {
                // Update subscription status
                $subscription->update(['status' => 'expired']);

                // Send in-app notification
                $this->notificationService->notifySubscriptionExpired($user);

                // Send email notification
                $this->emailService->sendSubscriptionExpired($user);

                $this->info("  Subscription expired and user notified");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("  Failed: {$e->getMessage()}");
                Log::error('Failed to expire subscription', [
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
