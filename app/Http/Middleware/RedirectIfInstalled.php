<?php

namespace App\Http\Middleware;

use App\Http\Controllers\InstallController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfInstalled
{
    /**
     * Handle an incoming request.
     * Redirects to home if application is already installed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (InstallController::isInstalled()) {
            return redirect('/');
        }

        return $next($request);
    }
}
