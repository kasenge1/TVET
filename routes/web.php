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
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Courses Management
    Route::resource('courses', AdminCourseController::class);
    Route::post('courses/{course}/publish', [AdminCourseController::class, 'publish'])->name('courses.publish');
    Route::post('courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])->name('courses.unpublish');
    Route::post('courses/bulk-action', [AdminCourseController::class, 'bulkAction'])->name('courses.bulk-action');

    // Levels Management
    Route::resource('levels', AdminLevelController::class);

    // Blog Management
    Route::prefix('blog')->name('blog.')->group(function () {
        // Posts
        Route::get('posts', [AdminBlogPostController::class, 'index'])->name('posts.index');
        Route::get('posts/create', [AdminBlogPostController::class, 'create'])->name('posts.create');
        Route::post('posts', [AdminBlogPostController::class, 'store'])->name('posts.store');
        Route::get('posts/{post}', [AdminBlogPostController::class, 'show'])->name('posts.show');
        Route::get('posts/{post}/edit', [AdminBlogPostController::class, 'edit'])->name('posts.edit');
        Route::put('posts/{post}', [AdminBlogPostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{post}', [AdminBlogPostController::class, 'destroy'])->name('posts.destroy');
        Route::post('posts/{post}/publish', [AdminBlogPostController::class, 'publish'])->name('posts.publish');
        Route::post('posts/{post}/unpublish', [AdminBlogPostController::class, 'unpublish'])->name('posts.unpublish');
        Route::post('posts/bulk-action', [AdminBlogPostController::class, 'bulkAction'])->name('posts.bulk-action');

        // Categories
        Route::get('categories', [AdminBlogCategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [AdminBlogCategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [AdminBlogCategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [AdminBlogCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [AdminBlogCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminBlogCategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Units Management
    Route::resource('units', AdminUnitController::class);
    Route::get('courses/{course}/units/create', [AdminUnitController::class, 'create'])->name('courses.units.create');

    // Questions Management
    Route::resource('questions', AdminQuestionController::class);
    Route::get('units/{unit}/questions/create', [AdminQuestionController::class, 'create'])->name('units.questions.create');
    Route::post('questions/{question}/generate-answer', [AdminQuestionController::class, 'generateAnswer'])->name('questions.generate-answer');
    Route::post('questions/generate-answer-preview', [AdminQuestionController::class, 'generateAnswerPreview'])->name('questions.generate-answer-preview');
    Route::post('questions/bulk-action', [AdminQuestionController::class, 'bulkAction'])->name('questions.bulk-action');
    Route::get('questions-import', [AdminQuestionController::class, 'showImport'])->name('questions.import');
    Route::get('questions-import/template', [AdminQuestionController::class, 'downloadTemplate'])->name('questions.import.template');
    Route::post('questions-import', [AdminQuestionController::class, 'processImport'])->name('questions.import.process');
    Route::get('questions-export', [AdminQuestionController::class, 'export'])->name('questions.export');

    // Users Management
    Route::resource('users', AdminUserController::class);
    Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::post('users/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
    Route::post('users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');

    // Roles & Permissions Management
    Route::resource('roles', AdminRoleController::class);

    // Subscriptions Management
    Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions/{subscription}/approve', [AdminSubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::post('subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    // Analytics
    Route::get('analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/export', [AdminAnalyticsController::class, 'export'])->name('analytics.export');

    // Activity Logs
    Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/export', [AdminActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::post('activity-logs/clear', [AdminActivityLogController::class, 'clear'])->name('activity-logs.clear');

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

    // Redirect old settings route to new general settings page
    Route::get('settings', function () {
        return redirect()->route('admin.settings.general');
    })->name('settings');

    // Settings Update Routes
    Route::put('settings/update', function (\Illuminate\Http\Request $request) {
        // In a real application, you would store these in a settings table
        return back()->with('success', 'General settings updated successfully!');
    })->name('settings.update');

    Route::put('settings/mpesa', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'mpesa_consumer_key' => 'nullable|string|max:255',
            'mpesa_consumer_secret' => 'nullable|string|max:255',
            'mpesa_shortcode' => 'nullable|string|max:50',
            'mpesa_passkey' => 'nullable|string|max:255',
            'mpesa_environment' => 'required|in:sandbox,production',
        ]);

        \App\Models\SiteSetting::setMpesaSettings([
            'consumer_key' => $validated['mpesa_consumer_key'] ?? '',
            'consumer_secret' => $validated['mpesa_consumer_secret'] ?? '',
            'shortcode' => $validated['mpesa_shortcode'] ?? '',
            'passkey' => $validated['mpesa_passkey'] ?? '',
            'environment' => $validated['mpesa_environment'],
            'callback_url' => url('/api/mpesa/callback'),
        ]);

        return back()->with('success', 'M-Pesa settings updated successfully!');
    })->name('settings.mpesa');

    Route::put('settings/ai', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'ai_provider' => 'required|in:openai,anthropic',
            'ai_api_key' => 'nullable|string|max:255',
            'ai_model' => 'required|string|max:100',
            'ai_max_tokens' => 'required|integer|min:100|max:4000',
            'ai_temperature' => 'required|numeric|min:0|max:1',
            'ai_system_prompt' => 'nullable|string|max:2000',
        ]);

        \App\Models\SiteSetting::setAiSettings([
            'provider' => $validated['ai_provider'],
            'api_key' => $validated['ai_api_key'] ?? '',
            'model' => $validated['ai_model'],
            'max_tokens' => $validated['ai_max_tokens'],
            'temperature' => $validated['ai_temperature'],
            'system_prompt' => $validated['ai_system_prompt'] ?? '',
        ]);

        return back()->with('success', 'AI settings updated successfully!');
    })->name('settings.ai');

    Route::post('settings/ai/test', function (\Illuminate\Http\Request $request) {
        $aiService = new \App\Services\AiService();
        $result = $aiService->testConnection();

        if ($result['success']) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        }

        return response()->json(['success' => false, 'message' => $result['message']], 400);
    })->name('settings.ai.test');

    Route::put('settings/email', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses,log',
            'mail_host' => 'required_if:mail_driver,smtp|nullable|string|max:255',
            'mail_port' => 'required_if:mail_driver,smtp|nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        \App\Models\SiteSetting::setEmailSettings([
            'driver' => $validated['mail_driver'],
            'host' => $validated['mail_host'] ?? '',
            'port' => $validated['mail_port'] ?? '587',
            'username' => $validated['mail_username'] ?? '',
            'password' => $validated['mail_password'] ?? '',
            'encryption' => $validated['mail_encryption'] ?? 'tls',
            'from_address' => $validated['mail_from_address'],
            'from_name' => $validated['mail_from_name'],
        ]);

        return back()->with('success', 'Email settings updated successfully!');
    })->name('settings.email');

    Route::post('settings/email/test', function (\Illuminate\Http\Request $request) {
        try {
            $emailSettings = \App\Models\SiteSetting::getEmailSettings();

            if (empty($emailSettings['host']) || empty($emailSettings['from_address'])) {
                return response()->json(['success' => false, 'message' => 'Email settings are not configured.'], 400);
            }

            // Configure mail on the fly
            config([
                'mail.default' => $emailSettings['driver'],
                'mail.mailers.smtp.host' => $emailSettings['host'],
                'mail.mailers.smtp.port' => $emailSettings['port'],
                'mail.mailers.smtp.username' => $emailSettings['username'],
                'mail.mailers.smtp.password' => $emailSettings['password'],
                'mail.mailers.smtp.encryption' => $emailSettings['encryption'] ?: null,
                'mail.from.address' => $emailSettings['from_address'],
                'mail.from.name' => $emailSettings['from_name'],
            ]);

            $adminEmail = \Illuminate\Support\Facades\Auth::user()->email;

            \Illuminate\Support\Facades\Mail::raw(
                "This is a test email from TVET Revision.\n\nIf you received this, your email settings are configured correctly!\n\nSent at: " . now()->format('F d, Y H:i:s'),
                function ($message) use ($adminEmail, $emailSettings) {
                    $message->to($adminEmail)
                            ->subject('TVET Revision - Test Email')
                            ->from($emailSettings['from_address'], $emailSettings['from_name']);
                }
            );

            return response()->json(['success' => true, 'message' => "Test email sent to {$adminEmail}. Please check your inbox."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send test email: ' . $e->getMessage()], 400);
        }
    })->name('settings.email.test');

    Route::put('settings/contact', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string|max:255',
            'contact_address_line2' => 'nullable|string|max:255',
            'contact_working_hours' => 'nullable|string|max:100',
        ]);

        \App\Models\SiteSetting::setContactSettings([
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
            'address' => $validated['contact_address'],
            'address_line2' => $validated['contact_address_line2'] ?? '',
            'working_hours' => $validated['contact_working_hours'] ?? '',
        ]);

        return back()->with('success', 'Contact information updated successfully!');
    })->name('settings.contact');

    Route::put('settings/social', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_whatsapp' => 'nullable|string|max:50',
        ]);

        \App\Models\SiteSetting::setSocialSettings([
            'facebook' => $validated['social_facebook'] ?? '',
            'twitter' => $validated['social_twitter'] ?? '',
            'instagram' => $validated['social_instagram'] ?? '',
            'youtube' => $validated['social_youtube'] ?? '',
            'tiktok' => $validated['social_tiktok'] ?? '',
            'linkedin' => $validated['social_linkedin'] ?? '',
            'whatsapp' => $validated['social_whatsapp'] ?? '',
        ]);

        return back()->with('success', 'Social media links updated successfully!');
    })->name('settings.social');

    // Maintenance Page Settings Route
    Route::put('settings/maintenance/page', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'message' => 'required|string',
            'expected_duration' => 'required|string|max:100',
            'support_email' => 'nullable|email|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ]);

        $settings = \App\Models\MaintenanceSettings::getSettings();
        $settings->update($validated);

        return back()->with('success', 'Maintenance page settings updated successfully!');
    })->name('settings.maintenance.page');

    // Maintenance Mode Routes
    Route::post('settings/maintenance/down', function () {
        \Illuminate\Support\Facades\Artisan::call('down', [
            '--redirect' => '/',
        ]);
        return back()->with('success', 'Maintenance mode enabled! Admin panel remains accessible.');
    })->name('settings.maintenance.down');

    Route::post('settings/maintenance/up', function () {
        \Illuminate\Support\Facades\Artisan::call('up');
        return back()->with('success', 'Maintenance mode disabled!');
    })->name('settings.maintenance.up');

    // Cache Management Routes
    Route::post('settings/cache/clear', function () {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return back()->with('success', 'Application cache cleared successfully!');
    })->name('settings.cache.clear');

    Route::post('settings/config/clear', function () {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        return back()->with('success', 'Configuration cache cleared successfully!');
    })->name('settings.config.clear');

    Route::post('settings/route/clear', function () {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return back()->with('success', 'Route cache cleared successfully!');
    })->name('settings.route.clear');

    // Dedicated Settings Pages (separate routes for each section)
    Route::get('settings/general', [AdminSettingsController::class, 'general'])->name('settings.general');
    Route::put('settings/general', [AdminSettingsController::class, 'updateGeneral'])->name('settings.general.update');

    Route::get('settings/branding', [AdminSettingsController::class, 'branding'])->name('settings.branding');
    Route::post('settings/branding', [AdminSettingsController::class, 'updateBranding'])->name('settings.branding.update');

    Route::get('settings/contact', [AdminSettingsController::class, 'contact'])->name('settings.contact');
    Route::put('settings/contact', [AdminSettingsController::class, 'updateContact'])->name('settings.contact.update');

    Route::get('settings/social', [AdminSettingsController::class, 'social'])->name('settings.social');
    Route::put('settings/social', [AdminSettingsController::class, 'updateSocial'])->name('settings.social.update');

    Route::get('settings/payments', [AdminSettingsController::class, 'payments'])->name('settings.payments');
    Route::put('settings/payments', [AdminSettingsController::class, 'updatePayments'])->name('settings.payments.update');

    Route::get('settings/email', [AdminSettingsController::class, 'email'])->name('settings.email');
    Route::put('settings/email', [AdminSettingsController::class, 'updateEmail'])->name('settings.email.update');
    Route::post('settings/email/test', [AdminSettingsController::class, 'testEmail'])->name('settings.email.test');

    Route::get('settings/ai', [AdminSettingsController::class, 'ai'])->name('settings.ai');
    Route::put('settings/ai', [AdminSettingsController::class, 'updateAi'])->name('settings.ai.update');
    Route::post('settings/ai/test', [AdminSettingsController::class, 'testAi'])->name('settings.ai.test');
    Route::delete('settings/ai/disconnect', [AdminSettingsController::class, 'disconnectAi'])->name('settings.ai.disconnect');

    Route::get('settings/maintenance', [AdminSettingsController::class, 'maintenance'])->name('settings.maintenance');
    Route::put('settings/maintenance', [AdminSettingsController::class, 'updateMaintenancePage'])->name('settings.maintenance.update');
    Route::post('settings/maintenance/enable', [AdminSettingsController::class, 'enableMaintenance'])->name('settings.maintenance.enable');
    Route::post('settings/maintenance/disable', [AdminSettingsController::class, 'disableMaintenance'])->name('settings.maintenance.disable');

    Route::get('settings/system', [AdminSettingsController::class, 'system'])->name('settings.system');
    Route::post('settings/cache/clear', [AdminSettingsController::class, 'clearCache'])->name('settings.cache.clear');
    Route::post('settings/config/clear', [AdminSettingsController::class, 'clearConfig'])->name('settings.config.clear');
    Route::post('settings/routes/clear', [AdminSettingsController::class, 'clearRoutes'])->name('settings.routes.clear');

    // Subscription Packages Routes
    Route::get('settings/packages', [AdminSubscriptionPackageController::class, 'index'])->name('settings.packages.index');
    Route::get('settings/packages/create', [AdminSubscriptionPackageController::class, 'create'])->name('settings.packages.create');
    Route::post('settings/packages', [AdminSubscriptionPackageController::class, 'store'])->name('settings.packages.store');
    Route::get('settings/packages/{package}/edit', [AdminSubscriptionPackageController::class, 'edit'])->name('settings.packages.edit');
    Route::put('settings/packages/{package}', [AdminSubscriptionPackageController::class, 'update'])->name('settings.packages.update');
    Route::delete('settings/packages/{package}', [AdminSubscriptionPackageController::class, 'destroy'])->name('settings.packages.destroy');

    // Google Ads Settings Routes
    Route::get('settings/ads', [AdminAdsSettingController::class, 'index'])->name('settings.ads.index');
    Route::put('settings/ads', [AdminAdsSettingController::class, 'update'])->name('settings.ads.update');

    // Security Settings Routes (reCAPTCHA + Email Verification)
    Route::get('settings/recaptcha', [AdminSettingsController::class, 'recaptcha'])->name('settings.recaptcha');
    Route::put('settings/recaptcha', [AdminSettingsController::class, 'updateRecaptcha'])->name('settings.recaptcha.update');
    Route::put('settings/security', [AdminSettingsController::class, 'updateSecurity'])->name('settings.security.update');

    // Feature Settings Routes (Subscriptions, Appearance, PWA)
    Route::get('settings/features', [AdminSettingsController::class, 'features'])->name('settings.features');
    Route::put('settings/subscription', [AdminSettingsController::class, 'updateSubscription'])->name('settings.subscription.update');
    Route::put('settings/appearance', [AdminSettingsController::class, 'updateAppearance'])->name('settings.appearance.update');
    Route::put('settings/pwa', [AdminSettingsController::class, 'updatePwa'])->name('settings.pwa.update');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications/send', [AdminNotificationController::class, 'send'])->name('notifications.send');
    Route::get('notifications/course-students/{course}', [AdminNotificationController::class, 'getCourseStudentCount'])->name('notifications.course-students');
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
