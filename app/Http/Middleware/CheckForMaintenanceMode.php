<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance mode check for admin routes
        if ($request->is('admin/*') || $request->is('admin')) {
            return $next($request);
        }

        // Check if app is in maintenance mode
        if (app()->isDownForMaintenance()) {
            // Allow access with secret token
            $secret = config('app.maintenance_secret');
            if ($secret && $request->input('secret') === $secret) {
                return $next($request);
            }

            // Show custom maintenance page for non-admin routes
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
