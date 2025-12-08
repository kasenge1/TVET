<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        // Get key statistics
        $stats = [
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'total_students' => User::student()->count(),
            'premium_students' => User::premium()->count(),
            'total_questions' => Question::count(),
            'total_units' => DB::table('units')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'new_subscriptions' => Subscription::where('status', 'active')
                ->where('created_at', '>=', now()->subMonth())
                ->count(),
            'expired_subscriptions' => Subscription::where('status', 'expired')->count(),
            'monthly_revenue' => Subscription::where('status', 'active')
                ->where('created_at', '>=', now()->subMonth())
                ->sum('amount'),
            // Subscription breakdown by package type
            'weekly_subscriptions' => Subscription::where('status', 'active')
                ->whereHas('package', fn($q) => $q->where('slug', 'weekly-plan'))
                ->count(),
            'monthly_subscriptions' => Subscription::where('status', 'active')
                ->whereHas('package', fn($q) => $q->where('slug', 'monthly-plan'))
                ->count(),
            'yearly_subscriptions' => Subscription::where('status', 'active')
                ->whereHas('package', fn($q) => $q->where('slug', 'yearly-plan'))
                ->count(),
        ];

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Popular courses (by enrollment)
        $popularCourses = Course::withCount('enrollments')
            ->where('is_published', true)
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        // Recent students
        $recentStudents = User::student()
            ->latest()
            ->take(5)
            ->get();

        // Monthly user growth
        $monthlyGrowth = User::student()
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Questions statistics by course
        $questionsByUnit = Question::select('unit_id', DB::raw('COUNT(*) as count'))
            ->groupBy('unit_id')
            ->with('unit.course')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'popularCourses',
            'recentStudents',
            'monthlyGrowth',
            'questionsByUnit'
        ));
    }
}
