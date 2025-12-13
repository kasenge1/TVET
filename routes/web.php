<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\LevelController as AdminLevelController;
use App\Http\Controllers\Admin\SubscriptionPackageController as AdminSubscriptionPackageController;
use App\Http\Controllers\Admin\AdsSettingController as AdminAdsSettingController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Installation Routes
|--------------------------------------------------------------------------
*/
Route::prefix('install')->name('install.')->middleware('install.redirect')->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/database', [InstallController::class, 'databaseStore'])->name('database.store');
    Route::get('/application', [InstallController::class, 'application'])->name('application');
    Route::post('/application', [InstallController::class, 'applicationStore'])->name('application.store');
    Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallController::class, 'adminStore'])->name('admin.store');
    Route::get('/finalize', [InstallController::class, 'finalize'])->name('finalize');
    Route::post('/process', [InstallController::class, 'process'])->name('process');
    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/courses', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Course::where('is_published', true)
        ->withCount(['units'])
        ->with(['levelRelation', 'units' => function($q) {
            $q->withCount('questions');
        }]);

    // Filter by level if provided
    if ($request->filled('level')) {
        $query->where('level_id', $request->level);
    }

    // Search by title if provided
    if ($request->filled('search')) {
        $searchTerm = trim($request->search);
        if (strlen($searchTerm) > 0 && strlen($searchTerm) <= 100) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }
    }

    // Sorting
    $sortBy = $request->get('sort', 'name');
    if ($sortBy === 'units') {
        $query->orderByDesc('units_count');
    } elseif ($sortBy === 'questions') {
        $query->orderByDesc('units_count');
    } else {
        $query->orderBy('title');
    }

    $courses = $query->paginate(12)->withQueryString();

    // Calculate questions count from eager loaded data (no N+1)
    $courses->each(function($course) {
        $course->questions_count = $course->units->sum('questions_count');
    });

    $levels = \App\Models\Level::orderBy('name')->get();

    return view('courses.index', compact('courses', 'levels'));
})->name('courses.index');

Route::get('/courses/{course:slug}', function (\App\Models\Course $course) {
    if (!$course->is_published) {
        abort(404);
    }

    $course->load(['levelRelation', 'units' => function($query) {
        $query->withCount('questions')->orderBy('unit_number');
    }]);

    $totalQuestions = $course->units->sum('questions_count');

    return view('courses.show', compact('course', 'totalQuestions'));
})->name('courses.show');

Route::get('/about', function () {
    $contact = \App\Models\SiteSetting::getContactSettings();
    $social = \App\Models\SiteSetting::getSocialSettings();
    return view('pages.about', compact('contact', 'social'));
})->name('about');

Route::get('/contact', function () {
    $contact = \App\Models\SiteSetting::getContactSettings();
    $social = \App\Models\SiteSetting::getSocialSettings();
    return view('pages.contact', compact('contact', 'social'));
})->name('contact');

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy');

Route::get('/blocked', function () {
    return view('auth.blocked');
})->name('blocked')->withoutMiddleware([\App\Http\Middleware\CheckUserBlocked::class]);

Route::get('/terms-of-service', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

// Sitemap Routes (for SEO)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-courses.xml', [SitemapController::class, 'courses'])->name('sitemap.courses');
Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog'])->name('sitemap.blog');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Legacy route redirect for old notification URLs like /questions?unit=2
Route::middleware(['auth'])->get('/questions', function (\Illuminate\Http\Request $request) {
    if ($request->has('unit')) {
        $unit = \App\Models\Unit::find($request->get('unit'));
        if ($unit) {
            return redirect()->route('learn.unit', $unit->slug);
        }
    }
    return redirect()->route('learn.index');
});

/*
|--------------------------------------------------------------------------
| Impersonation Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/stop-impersonating', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonating'])->name('stop-impersonating');
});

/*
|--------------------------------------------------------------------------
| Learning Routes (Frontend Revision System)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('learn')->name('learn.')->group(function () {
    Route::get('/', [LearnController::class, 'index'])->name('index');
    Route::get('/saved', [LearnController::class, 'saved'])->name('saved');
    Route::get('/settings', [LearnController::class, 'settings'])->name('settings');
    Route::put('/settings/profile', [LearnController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [LearnController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/notifications', [LearnController::class, 'updateNotifications'])->name('settings.notifications');
    Route::delete('/settings', [LearnController::class, 'destroy'])->name('settings.destroy');

    // Search
    Route::get('/search', [LearnController::class, 'search'])->name('search');

    // Subscription routes
    Route::get('/subscription', [LearnController::class, 'subscription'])->name('subscription');
    Route::get('/subscription/history', [LearnController::class, 'paymentHistory'])->name('subscription.history');
    Route::post('/subscription/{package}/subscribe', [LearnController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/{subscription}/pay', [LearnController::class, 'subscriptionPay'])->name('subscription.pay');
    Route::post('/subscription/{subscription}/mpesa', [LearnController::class, 'initiateMpesa'])->name('subscription.mpesa');
    Route::get('/subscription/{subscription}/status', [LearnController::class, 'checkPaymentStatus'])->name('subscription.status');
    Route::delete('/subscription/{subscription}/cancel', [LearnController::class, 'cancelSubscription'])->name('subscription.cancel');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/{unit:slug}', [LearnController::class, 'unit'])->name('unit');
    Route::get('/{unit:slug}/{question:slug}', [LearnController::class, 'question'])->name('question');
    Route::post('/bookmark/{question}', [LearnController::class, 'toggleBookmark'])->name('bookmark');
});

// Redirect old student routes to new /learn routes (for backwards compatibility)
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('learn.index'))->name('dashboard');
    Route::get('/questions', function (\Illuminate\Http\Request $request) {
        // Handle old URLs like /student/questions?unit=2
        if ($request->has('unit')) {
            $unit = \App\Models\Unit::find($request->get('unit'));
            if ($unit) {
                return redirect()->route('learn.unit', $unit->slug);
            }
        }
        return redirect()->route('learn.index');
    })->name('questions.index');
    Route::get('/bookmarks', fn() => redirect()->route('learn.saved'))->name('bookmarks');
    Route::get('/search', fn() => redirect()->route('learn.search'))->name('search');
    Route::get('/subscription', fn() => redirect()->route('learn.subscription'))->name('subscription');
    Route::get('/profile', fn() => redirect()->route('learn.settings'))->name('profile');
    Route::get('/notifications', fn() => redirect()->route('learn.notifications'))->name('notifications.index');
    Route::get('/course', fn() => redirect()->route('learn.index'))->name('course');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.subscription'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard - accessible to all admin roles
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Courses Management - requires 'view courses', 'create courses', 'edit courses', 'delete courses'
    // IMPORTANT: Static routes (create, bulk-action) must come BEFORE parameter routes ({course})
    Route::middleware(['permission:create courses'])->group(function () {
        Route::get('courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
        Route::post('courses', [AdminCourseController::class, 'store'])->name('courses.store');
    });
    Route::middleware(['permission:delete courses'])->group(function () {
        Route::post('courses/bulk-action', [AdminCourseController::class, 'bulkAction'])->name('courses.bulk-action');
    });
    Route::middleware(['permission:view courses'])->group(function () {
        Route::get('courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');
    });
    Route::middleware(['permission:edit courses'])->group(function () {
        Route::get('courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::patch('courses/{course}', [AdminCourseController::class, 'update']);
        Route::post('courses/{course}/publish', [AdminCourseController::class, 'publish'])->name('courses.publish');
        Route::post('courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])->name('courses.unpublish');
    });
    Route::middleware(['permission:delete courses'])->group(function () {
        Route::delete('courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');
    });

    // Levels Management - requires 'view courses', 'edit courses'
    // IMPORTANT: Static routes must come BEFORE parameter routes
    Route::middleware(['permission:create courses'])->group(function () {
        Route::get('levels/create', [AdminLevelController::class, 'create'])->name('levels.create');
        Route::post('levels', [AdminLevelController::class, 'store'])->name('levels.store');
    });
    Route::middleware(['permission:view courses'])->group(function () {
        Route::get('levels', [AdminLevelController::class, 'index'])->name('levels.index');
        Route::get('levels/{level}', [AdminLevelController::class, 'show'])->name('levels.show');
    });
    Route::middleware(['permission:edit courses'])->group(function () {
        Route::get('levels/{level}/edit', [AdminLevelController::class, 'edit'])->name('levels.edit');
        Route::put('levels/{level}', [AdminLevelController::class, 'update'])->name('levels.update');
        Route::patch('levels/{level}', [AdminLevelController::class, 'update']);
    });
    Route::middleware(['permission:delete courses'])->group(function () {
        Route::delete('levels/{level}', [AdminLevelController::class, 'destroy'])->name('levels.destroy');
    });

    // Blog Management - requires blog permissions
    // IMPORTANT: Static routes must come BEFORE parameter routes
    Route::prefix('blog')->name('blog.')->group(function () {
        // Create posts (static routes first)
        Route::middleware(['permission:create blog'])->group(function () {
            Route::get('posts/create', [AdminBlogPostController::class, 'create'])->name('posts.create');
            Route::post('posts', [AdminBlogPostController::class, 'store'])->name('posts.store');
            Route::get('categories/create', [AdminBlogCategoryController::class, 'create'])->name('categories.create');
            Route::post('categories', [AdminBlogCategoryController::class, 'store'])->name('categories.store');
        });
        // Bulk action (static route)
        Route::middleware(['permission:delete blog'])->group(function () {
            Route::post('posts/bulk-action', [AdminBlogPostController::class, 'bulkAction'])->name('posts.bulk-action');
        });
        // View posts (parameter routes after static)
        Route::middleware(['permission:view blog'])->group(function () {
            Route::get('posts', [AdminBlogPostController::class, 'index'])->name('posts.index');
            Route::get('posts/{post}', [AdminBlogPostController::class, 'show'])->name('posts.show');
            Route::get('categories', [AdminBlogCategoryController::class, 'index'])->name('categories.index');
        });
        // Edit posts
        Route::middleware(['permission:edit blog'])->group(function () {
            Route::get('posts/{post}/edit', [AdminBlogPostController::class, 'edit'])->name('posts.edit');
            Route::put('posts/{post}', [AdminBlogPostController::class, 'update'])->name('posts.update');
            Route::post('posts/{post}/publish', [AdminBlogPostController::class, 'publish'])->name('posts.publish');
            Route::post('posts/{post}/unpublish', [AdminBlogPostController::class, 'unpublish'])->name('posts.unpublish');
            Route::get('categories/{category}/edit', [AdminBlogCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('categories/{category}', [AdminBlogCategoryController::class, 'update'])->name('categories.update');
        });
        // Delete posts
        Route::middleware(['permission:delete blog'])->group(function () {
            Route::delete('posts/{post}', [AdminBlogPostController::class, 'destroy'])->name('posts.destroy');
            Route::delete('categories/{category}', [AdminBlogCategoryController::class, 'destroy'])->name('categories.destroy');
        });
    });

    // Units Management - requires unit permissions
    // IMPORTANT: Static routes must come BEFORE parameter routes
    Route::middleware(['permission:create units'])->group(function () {
        Route::get('units/create', [AdminUnitController::class, 'create'])->name('units.create');
        Route::post('units', [AdminUnitController::class, 'store'])->name('units.store');
        Route::get('courses/{course}/units/create', [AdminUnitController::class, 'create'])->name('courses.units.create');
    });
    Route::middleware(['permission:view units'])->group(function () {
        Route::get('units', [AdminUnitController::class, 'index'])->name('units.index');
        Route::get('units/{unit}', [AdminUnitController::class, 'show'])->name('units.show');
    });
    Route::middleware(['permission:edit units'])->group(function () {
        Route::get('units/{unit}/edit', [AdminUnitController::class, 'edit'])->name('units.edit');
        Route::put('units/{unit}', [AdminUnitController::class, 'update'])->name('units.update');
        Route::patch('units/{unit}', [AdminUnitController::class, 'update']);
    });
    Route::middleware(['permission:delete units'])->group(function () {
        Route::delete('units/{unit}', [AdminUnitController::class, 'destroy'])->name('units.destroy');
    });

    // Questions Management - requires question permissions
    // IMPORTANT: Static routes must come BEFORE parameter routes
    Route::middleware(['permission:create questions'])->group(function () {
        Route::get('questions/create', [AdminQuestionController::class, 'create'])->name('questions.create');
        Route::post('questions', [AdminQuestionController::class, 'store'])->name('questions.store');
        Route::get('units/{unit}/questions/create', [AdminQuestionController::class, 'create'])->name('units.questions.create');
        Route::get('questions-import', [AdminQuestionController::class, 'showImport'])->name('questions.import');
        Route::get('questions-import/template', [AdminQuestionController::class, 'downloadTemplate'])->name('questions.import.template');
        Route::post('questions-import', [AdminQuestionController::class, 'processImport'])->name('questions.import.process');
    });
    Route::middleware(['permission:edit questions'])->group(function () {
        Route::post('questions/generate-answer-preview', [AdminQuestionController::class, 'generateAnswerPreview'])->name('questions.generate-answer-preview');
    });
    Route::middleware(['permission:delete questions'])->group(function () {
        Route::post('questions/bulk-action', [AdminQuestionController::class, 'bulkAction'])->name('questions.bulk-action');
    });
    Route::middleware(['permission:view questions'])->group(function () {
        Route::get('questions', [AdminQuestionController::class, 'index'])->name('questions.index');
        Route::get('questions-export', [AdminQuestionController::class, 'export'])->name('questions.export');
        Route::get('questions/{question}', [AdminQuestionController::class, 'show'])->name('questions.show');
    });
    Route::middleware(['permission:edit questions'])->group(function () {
        Route::get('questions/{question}/edit', [AdminQuestionController::class, 'edit'])->name('questions.edit');
        Route::put('questions/{question}', [AdminQuestionController::class, 'update'])->name('questions.update');
        Route::patch('questions/{question}', [AdminQuestionController::class, 'update']);
        Route::post('questions/{question}/generate-answer', [AdminQuestionController::class, 'generateAnswer'])->name('questions.generate-answer');
    });
    Route::middleware(['permission:delete questions'])->group(function () {
        Route::delete('questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');
    });

    // Users Management - requires user permissions
    // IMPORTANT: Static routes must come BEFORE parameter routes
    Route::middleware(['permission:create users'])->group(function () {
        Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    });
    Route::middleware(['permission:delete users'])->group(function () {
        Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    });
    Route::middleware(['permission:view users'])->group(function () {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    });
    Route::middleware(['permission:edit users'])->group(function () {
        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::patch('users/{user}', [AdminUserController::class, 'update']);
        Route::post('users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
        Route::post('users/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('users.impersonate');
    });
    Route::middleware(['permission:delete users'])->group(function () {
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

    // Roles & Permissions Management
    // IMPORTANT: Static routes must come BEFORE parameter routes
    // Manage roles - requires 'manage roles' permission for create/edit/delete
    Route::middleware(['permission:manage roles'])->group(function () {
        Route::get('roles/create', [AdminRoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [AdminRoleController::class, 'store'])->name('roles.store');
    });
    // View roles - available to users with 'view roles' permission
    Route::middleware(['permission:view roles'])->group(function () {
        Route::get('roles', [AdminRoleController::class, 'index'])->name('roles.index');
        Route::get('roles/{role}', [AdminRoleController::class, 'show'])->name('roles.show');
    });
    // Manage roles - parameter routes
    Route::middleware(['permission:manage roles'])->group(function () {
        Route::get('roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [AdminRoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Subscriptions Management
    // View subscriptions - available to users with 'view subscriptions' permission
    Route::middleware(['permission:view subscriptions'])->group(function () {
        Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    });
    // Manage subscriptions - requires 'manage subscriptions' permission for approval/cancellation
    Route::middleware(['permission:manage subscriptions'])->group(function () {
        Route::post('subscriptions/{subscription}/approve', [AdminSubscriptionController::class, 'approve'])->name('subscriptions.approve');
        Route::post('subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    });

    // Analytics - requires 'view analytics' permission
    Route::middleware(['permission:view analytics'])->group(function () {
        Route::get('analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/export', [AdminAnalyticsController::class, 'export'])->name('analytics.export');
    });

    // Activity Logs - requires 'view activity logs' permission
    Route::middleware(['permission:view activity logs'])->group(function () {
        Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/export', [AdminActivityLogController::class, 'export'])->name('activity-logs.export');
        Route::post('activity-logs/clear', [AdminActivityLogController::class, 'clear'])->name('activity-logs.clear');
    });

    // Profile & Settings
    Route::get('profile', function () {
        return view('admin.profile');
    })->name('profile');

    Route::put('profile/update', function (\Illuminate\Http\Request $request) {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    })->name('profile.update');

    Route::put('profile/password', function (\Illuminate\Http\Request $request) {
        if (!$request->filled('current_password')) {
            return back()->with('info', 'No password changes were made.');
        }

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully!');
    })->name('profile.password');

    // Smart redirect to first available settings page based on user permissions
    Route::get('settings', function () {
        /** @var \App\Models\User $user */
        $user = request()->user();

        // Check permissions in priority order and redirect to first available
        if ($user->can('manage settings')) {
            return redirect()->route('admin.settings.general');
        }
        if ($user->can('manage branding')) {
            return redirect()->route('admin.settings.branding');
        }
        if ($user->can('manage packages')) {
            return redirect()->route('admin.settings.packages.index');
        }
        if ($user->can('manage ads settings')) {
            return redirect()->route('admin.settings.ads.index');
        }
        if ($user->can('manage hero settings')) {
            return redirect()->route('admin.settings.hero');
        }
        if ($user->can('manage contact settings')) {
            return redirect()->route('admin.settings.contact');
        }
        if ($user->can('manage social settings')) {
            return redirect()->route('admin.settings.social');
        }
        if ($user->can('manage payment settings')) {
            return redirect()->route('admin.settings.payments');
        }
        if ($user->can('manage email settings')) {
            return redirect()->route('admin.settings.email');
        }
        if ($user->can('manage ai settings')) {
            return redirect()->route('admin.settings.ai');
        }
        if ($user->can('manage security settings')) {
            return redirect()->route('admin.settings.recaptcha');
        }
        if ($user->can('manage feature settings')) {
            return redirect()->route('admin.settings.features');
        }
        if ($user->can('manage maintenance')) {
            return redirect()->route('admin.settings.maintenance');
        }
        if ($user->can('view system info')) {
            return redirect()->route('admin.settings.system');
        }

        // No permissions - abort
        abort(403, 'You do not have permission to access settings.');
    })->name('settings');

    /*
    |--------------------------------------------------------------------------
    | Settings Routes - Granular Permissions
    |--------------------------------------------------------------------------
    | Each settings section has its own permission for fine-grained control.
    | Admin can access: general, branding, contact, social, hero, features, ads
    | Super Admin only: payment, email, AI, security, maintenance, system
    */

    // General Settings - manage settings
    Route::middleware(['permission:manage settings'])->group(function () {
        Route::get('settings/general', [AdminSettingsController::class, 'general'])->name('settings.general');
        Route::put('settings/general', [AdminSettingsController::class, 'updateGeneral'])->name('settings.general.update');
    });

    // Branding Settings - manage branding
    Route::middleware(['permission:manage branding'])->group(function () {
        Route::get('settings/branding', [AdminSettingsController::class, 'branding'])->name('settings.branding');
        Route::post('settings/branding', [AdminSettingsController::class, 'updateBranding'])->name('settings.branding.update');
    });

    // Contact Settings - manage contact settings
    Route::middleware(['permission:manage contact settings'])->group(function () {
        Route::get('settings/contact', [AdminSettingsController::class, 'contact'])->name('settings.contact');
        Route::put('settings/contact', [AdminSettingsController::class, 'updateContact'])->name('settings.contact.update');
    });

    // Social Settings - manage social settings
    Route::middleware(['permission:manage social settings'])->group(function () {
        Route::get('settings/social', [AdminSettingsController::class, 'social'])->name('settings.social');
        Route::put('settings/social', [AdminSettingsController::class, 'updateSocial'])->name('settings.social.update');
    });

    // Hero Settings - manage hero settings
    Route::middleware(['permission:manage hero settings'])->group(function () {
        Route::get('settings/hero', [AdminSettingsController::class, 'hero'])->name('settings.hero');
        Route::put('settings/hero', [AdminSettingsController::class, 'updateHero'])->name('settings.hero.update');
    });

    // Feature Settings - manage feature settings
    Route::middleware(['permission:manage feature settings'])->group(function () {
        Route::get('settings/features', [AdminSettingsController::class, 'features'])->name('settings.features');
        Route::put('settings/subscription', [AdminSettingsController::class, 'updateSubscription'])->name('settings.subscription.update');
        Route::put('settings/appearance', [AdminSettingsController::class, 'updateAppearance'])->name('settings.appearance.update');
        Route::put('settings/pwa', [AdminSettingsController::class, 'updatePwa'])->name('settings.pwa.update');
    });

    // Ads Settings - manage ads settings
    Route::middleware(['permission:manage ads settings'])->group(function () {
        Route::get('settings/ads', [AdminAdsSettingController::class, 'index'])->name('settings.ads.index');
        Route::put('settings/ads', [AdminAdsSettingController::class, 'update'])->name('settings.ads.update');
    });

    // Subscription Packages - manage packages
    Route::middleware(['permission:manage packages'])->group(function () {
        Route::get('settings/packages/create', [AdminSubscriptionPackageController::class, 'create'])->name('settings.packages.create');
        Route::post('settings/packages', [AdminSubscriptionPackageController::class, 'store'])->name('settings.packages.store');
        Route::get('settings/packages', [AdminSubscriptionPackageController::class, 'index'])->name('settings.packages.index');
        Route::get('settings/packages/{package}/edit', [AdminSubscriptionPackageController::class, 'edit'])->name('settings.packages.edit');
        Route::put('settings/packages/{package}', [AdminSubscriptionPackageController::class, 'update'])->name('settings.packages.update');
        Route::delete('settings/packages/{package}', [AdminSubscriptionPackageController::class, 'destroy'])->name('settings.packages.destroy');
    });

    // Payment Settings - manage payment settings (Super Admin only)
    Route::middleware(['permission:manage payment settings'])->group(function () {
        Route::get('settings/payments', [AdminSettingsController::class, 'payments'])->name('settings.payments');
        Route::put('settings/payments', [AdminSettingsController::class, 'updatePayments'])->name('settings.payments.update');
    });

    // Email Settings - manage email settings (Super Admin only)
    Route::middleware(['permission:manage email settings'])->group(function () {
        Route::get('settings/email', [AdminSettingsController::class, 'email'])->name('settings.email');
        Route::put('settings/email', [AdminSettingsController::class, 'updateEmail'])->name('settings.email.update');
        Route::post('settings/email/test', [AdminSettingsController::class, 'testEmail'])->name('settings.email.test');
    });

    // AI Settings - manage ai settings (Super Admin only)
    Route::middleware(['permission:manage ai settings'])->group(function () {
        Route::get('settings/ai', [AdminSettingsController::class, 'ai'])->name('settings.ai');
        Route::put('settings/ai', [AdminSettingsController::class, 'updateAi'])->name('settings.ai.update');
        Route::post('settings/ai/test', [AdminSettingsController::class, 'testAi'])->name('settings.ai.test');
        Route::delete('settings/ai/disconnect', [AdminSettingsController::class, 'disconnectAi'])->name('settings.ai.disconnect');
    });

    // Security Settings - manage security settings (Super Admin only)
    Route::middleware(['permission:manage security settings'])->group(function () {
        Route::get('settings/recaptcha', [AdminSettingsController::class, 'recaptcha'])->name('settings.recaptcha');
        Route::put('settings/recaptcha', [AdminSettingsController::class, 'updateRecaptcha'])->name('settings.recaptcha.update');
        Route::put('settings/security', [AdminSettingsController::class, 'updateSecurity'])->name('settings.security.update');
    });

    // Maintenance Settings - manage maintenance (Super Admin only)
    Route::middleware(['permission:manage maintenance'])->group(function () {
        Route::get('settings/maintenance', [AdminSettingsController::class, 'maintenance'])->name('settings.maintenance');
        Route::put('settings/maintenance', [AdminSettingsController::class, 'updateMaintenancePage'])->name('settings.maintenance.update');
        Route::post('settings/maintenance/enable', [AdminSettingsController::class, 'enableMaintenance'])->name('settings.maintenance.enable');
        Route::post('settings/maintenance/disable', [AdminSettingsController::class, 'disableMaintenance'])->name('settings.maintenance.disable');
    });

    // System Settings - view system info & clear cache (Super Admin only)
    Route::middleware(['permission:view system info'])->group(function () {
        Route::get('settings/system', [AdminSettingsController::class, 'system'])->name('settings.system');
    });
    Route::middleware(['permission:clear cache'])->group(function () {
        Route::post('settings/cache/clear', [AdminSettingsController::class, 'clearCache'])->name('settings.cache.clear');
        Route::post('settings/config/clear', [AdminSettingsController::class, 'clearConfig'])->name('settings.config.clear');
        Route::post('settings/routes/clear', [AdminSettingsController::class, 'clearRoutes'])->name('settings.routes.clear');
    });

    // Notifications - sending requires 'send notifications' permission
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::middleware(['permission:send notifications'])->group(function () {
        Route::get('notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
        Route::post('notifications/send', [AdminNotificationController::class, 'send'])->name('notifications.send');
        Route::get('notifications/course-students/{course}', [AdminNotificationController::class, 'getCourseStudentCount'])->name('notifications.course-students');
    });
    Route::get('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications-clear-read', [NotificationController::class, 'destroyAllRead'])->name('notifications.clear-read');
    Route::delete('notifications-clear-all', [NotificationController::class, 'destroyAll'])->name('notifications.clear-all');
    Route::get('notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences');
    Route::put('notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.preferences.update');
});

require __DIR__.'/auth.php';
