<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Key metrics
        $totalRevenue = Subscription::where('status', 'active')->sum('amount');
        $monthlyRevenue = Subscription::where('status', 'active')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');

        // Active users - use last_active_at if available, otherwise use activity logs or sessions
        $activeUsers = $this->getActiveUsersCount(7);
        $totalStudents = User::student()->count();

        // Revenue by month (last 12 months)
        $revenueByMonth = Subscription::where('status', 'active')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // User growth (last 12 months)
        $userGrowth = User::student()
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Popular courses by enrollment
        $popularCourses = Course::withCount('enrollments')
            ->where('is_published', true)
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();

        // Questions per course
        $questionsPerCourse = Course::withCount(['units' => function($query) {
                $query->withCount('questions');
            }])
            ->with(['units' => function($query) {
                $query->withCount('questions');
            }])
            ->where('is_published', true)
            ->get()
            ->map(function($course) {
                $questionCount = $course->units->sum('questions_count');
                return [
                    'name' => $course->name,
                    'questions' => $questionCount,
                ];
            })
            ->sortByDesc('questions')
            ->take(10)
            ->values();

        // Subscription status breakdown
        $subscriptionStats = Subscription::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Daily active users (last 30 days) - use activity logs as source
        $dailyActiveUsers = $this->getDailyActiveUsers(30);

        // Revenue by plan type
        $revenueByPlan = Subscription::where('status', 'active')
            ->select('plan', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('plan')
            ->get();

        // Recent subscriptions
        $recentSubscriptions = Subscription::with(['user', 'package'])
            ->latest()
            ->take(10)
            ->get();

        // Course completion rates (based on question views/bookmarks as proxy)
        $courseEngagement = Course::withCount(['enrollments', 'units'])
            ->where('is_published', true)
            ->having('enrollments_count', '>', 0)
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.analytics.index', compact(
            'totalRevenue',
            'monthlyRevenue',
            'activeUsers',
            'totalStudents',
            'revenueByMonth',
            'userGrowth',
            'popularCourses',
            'questionsPerCourse',
            'subscriptionStats',
            'dailyActiveUsers',
            'revenueByPlan',
            'recentSubscriptions',
            'courseEngagement'
        ));
    }

    /**
     * Export analytics data as CSV.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'revenue');
        $filename = "analytics_{$type}_" . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');

            switch ($type) {
                case 'revenue':
                    fputcsv($file, ['Month', 'Revenue (KES)', 'Subscriptions']);
                    $data = Subscription::where('status', 'active')
                        ->where('created_at', '>=', now()->subMonths(12))
                        ->select(
                            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                            DB::raw('SUM(amount) as total'),
                            DB::raw('COUNT(*) as count')
                        )
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($file, [$row->month, $row->total, $row->count]);
                    }
                    break;

                case 'users':
                    fputcsv($file, ['Month', 'New Users']);
                    $data = User::student()
                        ->where('created_at', '>=', now()->subMonths(12))
                        ->select(
                            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                            DB::raw('COUNT(*) as count')
                        )
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($file, [$row->month, $row->count]);
                    }
                    break;

                case 'subscriptions':
                    fputcsv($file, ['Date', 'User', 'Email', 'Plan', 'Amount', 'Status']);
                    $data = Subscription::with('user')
                        ->latest()
                        ->take(500)
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->created_at->format('Y-m-d'),
                            $row->user->name ?? 'N/A',
                            $row->user->email ?? 'N/A',
                            $row->plan,
                            $row->amount,
                            $row->status,
                        ]);
                    }
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get count of active users in the last N days.
     * Uses activity logs or sessions as fallback if last_active_at is not available.
     */
    protected function getActiveUsersCount(int $days): int
    {
        // Try using last_active_at column first
        if (Schema::hasColumn('users', 'last_active_at')) {
            $count = User::where('last_active_at', '>=', now()->subDays($days))->count();
            if ($count > 0) {
                return $count;
            }
        }

        // Fallback: use activity logs
        $activeFromLogs = ActivityLog::where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        if ($activeFromLogs > 0) {
            return $activeFromLogs;
        }

        // Fallback: use sessions table
        return DB::table('sessions')
            ->where('last_activity', '>=', now()->subDays($days)->timestamp)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Get daily active users for the last N days.
     * Uses activity logs as the data source.
     */
    protected function getDailyActiveUsers(int $days): \Illuminate\Support\Collection
    {
        // Try using activity logs first
        $fromLogs = ActivityLog::where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('user_id')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if ($fromLogs->isNotEmpty()) {
            return $fromLogs;
        }

        // Fallback: use sessions table
        return DB::table('sessions')
            ->where('last_activity', '>=', now()->subDays($days)->timestamp)
            ->whereNotNull('user_id')
            ->select(
                DB::raw('DATE(FROM_UNIXTIME(last_activity)) as date'),
                DB::raw('COUNT(DISTINCT user_id) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
