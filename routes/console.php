<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule subscription management tasks
Schedule::command('subscriptions:expire')
    ->daily()
    ->at('00:05')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/subscriptions.log'));

// Send reminders 3 days before expiry
Schedule::command('subscriptions:send-reminders --days=3')
    ->daily()
    ->at('09:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/subscriptions.log'));

// Send reminders 1 day before expiry
Schedule::command('subscriptions:send-reminders --days=1')
    ->daily()
    ->at('09:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/subscriptions.log'));
