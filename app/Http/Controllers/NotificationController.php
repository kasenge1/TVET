<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications list.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }

        $notifications = $query->paginate(20);
        $unreadCount = Notification::where('user_id', $user->id)->unread()->count();

        // Determine which view to use based on user role and route
        if (request()->routeIs('admin.*') && $user->isAdmin()) {
            $view = 'admin.notifications.index';
        } elseif (request()->routeIs('learn.*')) {
            $view = 'learn.notifications';
        } else {
            $view = 'student.notifications.index';
        }

        return view($view, compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Get unread notifications count (for AJAX).
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown).
     */
    public function recent()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'icon' => $n->icon_class,
                    'icon_color' => $n->icon_color,
                    'action_url' => $n->action_url,
                    'time_ago' => $n->time_ago,
                    'is_read' => $n->isRead(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user owns this notification (use loose comparison to handle int/string mismatch)
        if ((int) $notification->user_id !== (int) Auth::id()) {
            abort(403, 'You do not have permission to access this notification.');
        }

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        // Redirect to action URL if exists
        if ($notification->action_url) {
            $actionUrl = $notification->action_url;

            // Detect if we're on the frontend (learn) routes and adjust URL accordingly
            $currentPrefix = request()->segment(1);
            if ($currentPrefix === 'learn' && str_contains($actionUrl, '/student/')) {
                $actionUrl = str_replace('/student/', '/learn/', $actionUrl);
                // Handle special route mappings (student routes that don't exist in learn)
                $actionUrl = preg_replace('#/learn/dashboard$#', '/learn', $actionUrl);
                $actionUrl = preg_replace('#/learn/bookmarks$#', '/learn/saved', $actionUrl);
                $actionUrl = preg_replace('#/learn/profile$#', '/learn/settings', $actionUrl);
            }

            return redirect($actionUrl);
        }

        return back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all read notifications.
     */
    public function destroyAllRead()
    {
        $deleted = Notification::where('user_id', Auth::id())
            ->read()
            ->delete();

        return back()->with('success', "Deleted {$deleted} read notifications.");
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        $deleted = Notification::where('user_id', Auth::id())->delete();

        return back()->with('success', "Deleted {$deleted} notifications.");
    }

    /**
     * Show notification preferences.
     */
    public function preferences()
    {
        $user = Auth::user();

        $types = [
            Notification::TYPE_NEW_QUESTION => 'New Questions',
            Notification::TYPE_NEW_UNIT => 'New Units',
            Notification::TYPE_SUBSCRIPTION_EXPIRING => 'Subscription Expiring',
            Notification::TYPE_SUBSCRIPTION_EXPIRED => 'Subscription Expired',
            Notification::TYPE_SYSTEM => 'System Notifications',
        ];

        // Add admin-specific types
        if ($user->isAdmin()) {
            $types[Notification::TYPE_NEW_USER] = 'New User Registrations';
            $types[Notification::TYPE_NEW_SUBSCRIPTION] = 'New Subscriptions';
        }

        $preferences = [];
        foreach ($types as $type => $label) {
            $pref = NotificationPreference::getPreference($user->id, $type);
            $preferences[$type] = [
                'label' => $label,
                'in_app' => $pref->in_app,
                'email' => $pref->email,
            ];
        }

        $view = request()->routeIs('admin.*') && $user->isAdmin()
            ? 'admin.notifications.preferences'
            : 'student.notifications.preferences';

        return view($view, compact('preferences'));
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $preferences = $request->input('preferences', []);

        foreach ($preferences as $type => $settings) {
            NotificationPreference::updateOrCreate(
                ['user_id' => $user->id, 'type' => $type],
                [
                    'in_app' => isset($settings['in_app']),
                    'email' => isset($settings['email']),
                ]
            );
        }

        return back()->with('success', 'Notification preferences updated.');
    }
}
