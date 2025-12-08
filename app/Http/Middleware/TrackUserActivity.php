<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * This middleware logs user activity for analytics and updates last_active_at.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Update last active timestamp and log activity after the request is processed
        if ($request->user() && $response->isSuccessful()) {
            $this->updateLastActive($request->user());
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Update user's last active timestamp.
     */
    protected function updateLastActive(User $user): void
    {
        // Only update once per minute to avoid excessive DB writes
        if (!$user->last_active_at || $user->last_active_at->diffInMinutes(now()) >= 1) {
            $user->update(['last_active_at' => now()]);
        }
    }

    /**
     * Log the user activity based on the route.
     */
    protected function logActivity(Request $request): void
    {
        $route = $request->route();

        if (!$route) {
            return;
        }

        $routeName = $route->getName();

        if (!$routeName) {
            return;
        }

        $activity = $this->parseActivity($routeName, $route, $request);

        if ($activity) {
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $activity['action'],
                'resource_type' => $activity['resource_type'] ?? null,
                'resource_id' => $activity['resource_id'] ?? null,
                'metadata' => $activity['metadata'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Parse activity from route name.
     */
    protected function parseActivity(string $routeName, $route, Request $request): ?array
    {
        // Student activities
        if (str_starts_with($routeName, 'student.')) {
            return match (true) {
                $routeName === 'student.questions.show' => [
                    'action' => 'viewed',
                    'resource_type' => 'App\\Models\\Question',
                    'resource_id' => $route->parameter('question')?->id ?? $route->parameter('question'),
                ],
                $routeName === 'student.course.enroll' => [
                    'action' => 'enrolled',
                    'resource_type' => 'App\\Models\\Course',
                    'resource_id' => $route->parameter('course')?->id ?? $route->parameter('course'),
                ],
                $routeName === 'student.course.unenroll' => [
                    'action' => 'unenrolled',
                    'resource_type' => 'App\\Models\\Course',
                    'resource_id' => $route->parameter('course')?->id ?? $route->parameter('course'),
                ],
                $routeName === 'student.bookmark.toggle' => [
                    'action' => $request->isMethod('post') ? 'bookmarked' : 'unbookmarked',
                    'resource_type' => 'App\\Models\\Question',
                    'resource_id' => $route->parameter('question')?->id ?? $route->parameter('question'),
                ],
                $routeName === 'student.search' => [
                    'action' => 'searched',
                    'metadata' => ['query' => $request->get('q')],
                ],
                $routeName === 'student.subscription.subscribe' => [
                    'action' => 'subscribed',
                    'resource_type' => 'App\\Models\\SubscriptionPackage',
                    'resource_id' => $route->parameter('package')?->id ?? $route->parameter('package'),
                ],
                $routeName === 'student.profile.update' => [
                    'action' => 'profile_updated',
                ],
                $routeName === 'student.profile.password' => [
                    'action' => 'password_changed',
                ],
                default => null,
            };
        }

        // Admin activities
        if (str_starts_with($routeName, 'admin.')) {
            // Only log significant admin actions
            return match (true) {
                str_contains($routeName, '.store') => [
                    'action' => 'created',
                    'resource_type' => $this->getResourceTypeFromRoute($routeName),
                ],
                str_contains($routeName, '.update') => [
                    'action' => 'updated',
                    'resource_type' => $this->getResourceTypeFromRoute($routeName),
                    'resource_id' => $this->getResourceIdFromRoute($route),
                ],
                str_contains($routeName, '.destroy') => [
                    'action' => 'deleted',
                    'resource_type' => $this->getResourceTypeFromRoute($routeName),
                    'resource_id' => $this->getResourceIdFromRoute($route),
                ],
                $routeName === 'admin.subscriptions.approve' => [
                    'action' => 'subscription_approved',
                    'resource_type' => 'App\\Models\\Subscription',
                    'resource_id' => $route->parameter('subscription')?->id ?? $route->parameter('subscription'),
                ],
                $routeName === 'admin.subscriptions.cancel' => [
                    'action' => 'subscription_cancelled',
                    'resource_type' => 'App\\Models\\Subscription',
                    'resource_id' => $route->parameter('subscription')?->id ?? $route->parameter('subscription'),
                ],
                default => null,
            };
        }

        return null;
    }

    /**
     * Get resource type from route name.
     */
    protected function getResourceTypeFromRoute(string $routeName): ?string
    {
        $map = [
            'courses' => 'App\\Models\\Course',
            'units' => 'App\\Models\\Unit',
            'questions' => 'App\\Models\\Question',
            'users' => 'App\\Models\\User',
            'levels' => 'App\\Models\\Level',
            'packages' => 'App\\Models\\SubscriptionPackage',
        ];

        foreach ($map as $key => $type) {
            if (str_contains($routeName, $key)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Get resource ID from route.
     */
    protected function getResourceIdFromRoute($route): ?int
    {
        $parameters = $route->parameters();

        foreach ($parameters as $param) {
            if (is_object($param) && method_exists($param, 'getKey')) {
                return $param->getKey();
            }
            if (is_numeric($param)) {
                return (int) $param;
            }
        }

        return null;
    }
}
