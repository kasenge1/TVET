<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display the activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by resource type
        if ($request->filled('resource_type')) {
            $query->where('resource_type', 'like', '%' . $request->resource_type . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in metadata
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('action', 'like', "%{$search}%")
                ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(10)->withQueryString();

        // Get filter options
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        /** @var \Illuminate\Database\Eloquent\Collection<int, User> $users */
        $users = User::whereIn('id', ActivityLog::select('user_id')->distinct())
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = [
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'this_month' => ActivityLog::whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
            'total' => ActivityLog::count(),
        ];

        return view('admin.activity-logs.index', compact(
            'logs',
            'actions',
            'users',
            'stats'
        ));
    }

    /**
     * Export activity logs as CSV.
     */
    public function export(Request $request)
    {
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($request) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Timestamp', 'User', 'Email', 'Action', 'Resource', 'IP Address', 'User Agent']);

            $query = ActivityLog::with('user')->latest('created_at');

            // Apply same filters as index
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $query->chunk(500, function($logs) use ($file) {
                foreach ($logs as $log) {
                    $resource = $log->resource_type
                        ? class_basename($log->resource_type) . ' #' . $log->resource_id
                        : '';

                    fputcsv($file, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user->name ?? 'System',
                        $log->user->email ?? '',
                        $log->action,
                        $resource,
                        $log->ip_address,
                        $log->user_agent,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old activity logs.
     */
    public function clear(Request $request)
    {
        $days = $request->get('days', 90);

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "Deleted {$deleted} activity logs older than {$days} days.");
    }
}
