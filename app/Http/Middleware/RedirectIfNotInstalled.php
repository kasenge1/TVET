<?php

namespace App\Http\Middleware;

use App\Http\Controllers\InstallController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Handle an incoming request.
     * Redirects to installer if application is not installed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for install routes
        if ($request->is('install*')) {
            return $next($request);
        }

        if (!InstallController::isInstalled()) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }
}
