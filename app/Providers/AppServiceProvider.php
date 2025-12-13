<?php

namespace App\Providers;

use App\Models\Question;
use App\Models\Subscription;
use App\Models\Unit;
use App\Models\User;
use App\Observers\QuestionObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\UnitObserver;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 pagination
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.bootstrap-5');

        // Register model observers for notifications
        // Note: LogAuthenticationActivity subscriber is auto-discovered by Laravel 11
        User::observe(UserObserver::class);
        Question::observe(QuestionObserver::class);
        Unit::observe(UnitObserver::class);
        Subscription::observe(SubscriptionObserver::class);
    }
}
