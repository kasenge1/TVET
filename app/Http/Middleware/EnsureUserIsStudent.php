<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isStudent()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Student access required.'], 403);
            }

            // Redirect admins to admin dashboard
            if ($request->user() && $request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            abort(403, 'Unauthorized. Student access required.');
        }

        return $next($request);
    }
}
