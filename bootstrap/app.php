<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Replace default maintenance mode middleware with custom one
        $middleware->remove(\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class);
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\CheckForMaintenanceMode::class,
            \App\Http\Middleware\CheckUserBlocked::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
            'check.subscription' => \App\Http\Middleware\CheckSubscriptionStatus::class,
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
            'check.blocked' => \App\Http\Middleware\CheckUserBlocked::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'install.redirect' => \App\Http\Middleware\RedirectIfInstalled::class,
            'installed' => \App\Http\Middleware\RedirectIfNotInstalled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
