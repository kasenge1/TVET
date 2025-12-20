<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\Question;
use App\Models\QuestionView;
use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\Unit;
use App\Models\User;
use App\Models\ExamPeriod;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class LearnController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Display the student's enrolled course overview.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access your course.');
        }

        $course = $enrollment->course->load(['units' => function ($q) {
            $q->withCount(['questions' => function ($query) {
                $query->whereNull('parent_question_id'); // Only main questions
            }])->orderBy('unit_number');
        }]);

        $totalQuestions = $course->units->sum('questions_count');
        $savedCount = $user->bookmarks()->count();

        // Get course progress
        $progress = QuestionView::getCourseProgress($user->id, $course->id);

        // Get next unread question to enable "Continue" feature (or last viewed if all read)
        $lastViewed = QuestionView::getNextUnreadInCourse($user->id, $course->id);

        // Get progress per unit
        $unitProgress = [];
        foreach ($course->units as $unit) {
            $viewedInUnit = QuestionView::getViewedCountInUnit($user->id, $unit->id);
            $unitProgress[$unit->id] = [
                'viewed' => $viewedInUnit,
                'total' => $unit->questions_count,
                'percentage' => $unit->questions_count > 0 ? round(($viewedInUnit / $unit->questions_count) * 100) : 0,
            ];
        }

        return view('learn.index', compact('course', 'totalQuestions', 'savedCount', 'progress', 'lastViewed', 'unitProgress'));
    }

    /**
     * Display a unit with its questions.
     */
    public function unit(Request $request, Unit $unit)
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        // Verify unit belongs to student's enrolled course
        if (!$enrollment || $unit->course_id !== $enrollment->course_id) {
            abort(403, 'You do not have access to this unit.');
        }

        // Get available exam periods for this unit (from ExamPeriod model)
        $examPeriodIds = Question::where('unit_id', $unit->id)
            ->mainQuestions()
            ->whereNotNull('exam_period_id')
            ->distinct()
            ->pluck('exam_period_id');

        $examPeriods = ExamPeriod::whereIn('id', $examPeriodIds)
            ->ordered()
            ->get()
            ->map(function ($period) {
                return [
                    'id' => $period->id,
                    'label' => $period->name,
                    'key' => $period->period_key,
                ];
            });

        // Filter by exam period if specified
        $selectedPeriod = $request->get('period');
        $questionsQuery = $unit->questions()->mainQuestions();

        if ($selectedPeriod) {
            // Try to find by period key (e.g., "2025-07")
            $examPeriod = ExamPeriod::where(function($q) use ($selectedPeriod) {
                // Try to match by period_key format
                if (preg_match('/^(\d{4})-(\d{2})$/', $selectedPeriod, $matches)) {
                    $q->where('year', $matches[1])->where('month', (int) $matches[2]);
                }
            })->first();

            if ($examPeriod) {
                $questionsQuery->where('exam_period_id', $examPeriod->id);
            }
        }

        $questions = $questionsQuery->with('examPeriod')->orderBy('order')->paginate(10);

        $savedIds = $user->bookmarks()->pluck('question_id')->toArray();

        // Get viewed question IDs for this unit
        $viewedIds = QuestionView::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($unit) {
                $query->where('unit_id', $unit->id);
            })
            ->pluck('question_id')
            ->toArray();

        // Get unit progress
        $totalInUnit = $unit->questions()->mainQuestions()->count();
        $viewedInUnit = count($viewedIds);
        $unitProgress = [
            'viewed' => $viewedInUnit,
            'total' => $totalInUnit,
            'percentage' => $totalInUnit > 0 ? round(($viewedInUnit / $totalInUnit) * 100) : 0,
        ];

        // Get next unread question to enable "Continue" feature (or last viewed if all read)
        $lastViewed = QuestionView::getNextUnreadInUnit($user->id, $unit->id);

        $course = $enrollment->course;

        return view('learn.unit', compact('unit', 'questions', 'savedIds', 'viewedIds', 'unitProgress', 'lastViewed', 'course', 'examPeriods', 'selectedPeriod'));
    }

    /**
     * Display a question with its answer.
     */
    public function question(Unit $unit, string $questionSlug)
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        // Verify user is enrolled in this course
        if (!$enrollment || $unit->course_id !== $enrollment->course_id) {
            abort(403, 'You do not have access to this question.');
        }

        // Find question by slug within this unit
        $question = Question::where('unit_id', $unit->id)
            ->where('slug', $questionSlug)
            ->with(['subQuestions' => function($q) {
                $q->orderBy('order');
            }])
            ->first();

        if (!$question) {
            abort(404);
        }

        // Record view for this user (only counts once per user per question)
        $question->recordUserView($user->id);

        // Get prev/next for navigation
        $allQuestions = $unit->questions()
            ->mainQuestions()
            ->orderBy('order')
            ->get(['id', 'slug']);

        $currentIndex = $allQuestions->search(fn($q) => $q->id === $question->id);

        $prev = $currentIndex > 0 ? $allQuestions[$currentIndex - 1] : null;
        $next = $currentIndex < $allQuestions->count() - 1 ? $allQuestions[$currentIndex + 1] : null;

        $isSaved = $user->bookmarks()->where('question_id', $question->id)->exists();

        $course = $enrollment->course;

        return view('learn.question', compact('unit', 'question', 'prev', 'next', 'isSaved', 'currentIndex', 'allQuestions', 'course'));
    }

    /**
     * Display saved/bookmarked questions.
     */
    public function saved()
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access your saved questions.');
        }

        $bookmarks = $user->bookmarks()
            ->with(['question.unit'])
            ->whereHas('question.unit', function ($q) use ($enrollment) {
                $q->where('course_id', $enrollment->course_id);
            })
            ->latest()
            ->paginate(10);

        $course = $enrollment->course;

        return view('learn.saved', compact('bookmarks', 'course'));
    }

    /**
     * Search questions within enrolled course.
     */
    public function search(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access search.');
        }

        $searchQuery = $request->get('q', '');
        $course = $enrollment->course;
        $questions = collect();
        $savedIds = [];

        if ($searchQuery) {
            $questions = Question::whereHas('unit', function ($q) use ($enrollment) {
                    $q->where('course_id', $enrollment->course_id);
                })
                ->mainQuestions()
                ->where(function ($q) use ($searchQuery) {
                    $q->where('question_text', 'like', "%{$searchQuery}%")
                      ->orWhere('answer_text', 'like', "%{$searchQuery}%");
                })
                ->with('unit')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $savedIds = $user->bookmarks()->pluck('question_id')->toArray();
        }

        return view('learn.search', compact('questions', 'searchQuery', 'course', 'savedIds'));
    }

    /**
     * Toggle bookmark/save status for a question.
     */
    public function toggleBookmark(Question $question)
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        // Verify question belongs to student's enrolled course
        if (!$enrollment || $question->unit->course_id !== $enrollment->course_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $bookmark = $user->bookmarks()->where('question_id', $question->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['saved' => false]);
        }

        Bookmark::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
        ]);

        return response()->json(['saved' => true]);
    }

    /**
     * Display account settings page.
     */
    public function settings()
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access settings.');
        }

        $savedCount = $user->bookmarks()->count();

        // Get notification preferences
        $types = [
            Notification::TYPE_NEW_QUESTION => 'New Questions',
            Notification::TYPE_NEW_UNIT => 'New Units',
            Notification::TYPE_SUBSCRIPTION_EXPIRING => 'Subscription Expiring',
            Notification::TYPE_SUBSCRIPTION_EXPIRED => 'Subscription Expired',
            Notification::TYPE_SYSTEM => 'System Notifications',
        ];

        $notificationPreferences = [];
        foreach ($types as $type => $label) {
            $pref = NotificationPreference::getPreference($user->id, $type);
            $notificationPreferences[$type] = [
                'label' => $label,
                'in_app' => $pref->in_app,
                'email' => $pref->email,
            ];
        }

        return view('learn.settings', compact('user', 'enrollment', 'savedCount', 'notificationPreferences'));
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:15'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
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

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete user account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    /**
     * Display subscription packages and current status.
     */
    public function subscription()
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access subscription.');
        }

        // Check if subscriptions are enabled by admin
        $subscriptionsEnabled = SiteSetting::subscriptionsEnabled();
        $subscriptionNotice = SiteSetting::getSubscriptionNotice();

        // Get active subscription packages
        $packages = SubscriptionPackage::active()->ordered()->get();

        // Get user's current subscription
        $currentSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        // Get subscription history
        $subscriptionHistory = Subscription::where('user_id', $user->id)
            ->with('package')
            ->latest()
            ->take(5)
            ->get();

        // Check if M-Pesa is configured
        $mpesaConfigured = $this->mpesaService->isConfigured();

        return view('learn.subscription', compact(
            'packages',
            'currentSubscription',
            'subscriptionHistory',
            'mpesaConfigured',
            'subscriptionsEnabled',
            'subscriptionNotice'
        ));
    }

    /**
     * Display full payment history with pagination.
     */
    public function paymentHistory()
    {
        /** @var User $user */
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access payment history.');
        }

        $payments = Subscription::where('user_id', $user->id)
            ->with('package')
            ->latest()
            ->paginate(10);

        return view('learn.payment-history', compact('payments'));
    }

    /**
     * Initiate subscription with selected package.
     */
    public function subscribe(Request $request, SubscriptionPackage $package)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user already has an active subscription
        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if ($activeSubscription) {
            return back()->with('error', 'You already have an active subscription until ' . $activeSubscription->expires_at->format('M d, Y') . '. You can subscribe again after it expires.');
        }

        // Check if package is active
        if (!$package->is_active) {
            return back()->with('error', 'This package is not available.');
        }

        // Check if user has a pending subscription for this package
        $pendingSubscription = Subscription::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingSubscription) {
            return redirect()->route('learn.subscription.pay', $pendingSubscription);
        }

        // Create a pending subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $package->price,
            'status' => 'pending',
            'starts_at' => now(),
            'expires_at' => now()->addDays($package->duration_days),
        ]);

        return redirect()->route('learn.subscription.pay', $subscription);
    }

    /**
     * Show payment page for a subscription.
     */
    public function subscriptionPay(Subscription $subscription)
    {
        // Ensure the subscription belongs to the current user
        if ($subscription->user_id !== Auth::id()) {
            return redirect()->route('learn.subscription')
                ->with('error', 'Unauthorized access.');
        }

        // Check if already paid
        if ($subscription->status === 'active') {
            return redirect()->route('learn.subscription')
                ->with('info', 'This subscription is already active.');
        }

        $package = $subscription->package;
        $mpesaConfigured = $this->mpesaService->isConfigured();

        return view('learn.subscription-pay', compact('subscription', 'package', 'mpesaConfigured'));
    }

    /**
     * Initiate M-Pesa STK Push payment.
     */
    public function initiateMpesa(Request $request, Subscription $subscription)
    {
        // Ensure the subscription belongs to the current user
        if ($subscription->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'phone_number' => 'required|string|min:9|max:15',
        ]);

        // Clean the phone number
        $phoneNumber = preg_replace('/[^0-9]/', '', $request->input('phone_number'));

        // Convert 0712... to 254712...
        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '254' . substr($phoneNumber, 1);
        } elseif (strlen($phoneNumber) === 9) {
            $phoneNumber = '254' . $phoneNumber;
        }

        // Check if M-Pesa is configured
        if (!$this->mpesaService->isConfigured()) {
            return back()->with('error', 'M-Pesa is not configured. Please contact support.');
        }

        // Initiate STK Push
        $result = $this->mpesaService->stkPush(
            $phoneNumber,
            $subscription->amount,
            'SUB' . $subscription->id,
            'Premium Subscription - ' . ($subscription->package->name ?? 'Package')
        );

        if ($result['success']) {
            $subscription->update([
                'mpesa_checkout_id' => $result['checkout_request_id'],
                'mpesa_merchant_id' => $result['merchant_request_id'],
                'phone_number' => $phoneNumber,
            ]);

            return back()->with('success', 'Payment request sent! Please check your phone and enter your M-Pesa PIN to complete the payment.');
        }

        return back()->with('error', 'Payment initiation failed: ' . $result['message']);
    }

    /**
     * Check payment status.
     */
    public function checkPaymentStatus(Subscription $subscription)
    {
        // Ensure the subscription belongs to the current user
        if ($subscription->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If already active, return success
        if ($subscription->status === 'active') {
            return response()->json([
                'status' => 'active',
                'message' => 'Payment successful! Your subscription is now active.',
                'redirect' => route('learn.subscription'),
            ]);
        }

        // If payment failed or cancelled
        if (in_array($subscription->status, ['failed', 'cancelled', 'timeout'])) {
            $message = $this->getPaymentFailureMessage($subscription->status, $subscription->mpesa_result_desc);
            return response()->json([
                'status' => $subscription->status,
                'message' => $message,
                'can_retry' => true,
            ]);
        }

        // If no checkout ID, payment hasn't been initiated
        if (!$subscription->mpesa_checkout_id) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Please initiate payment first.',
            ]);
        }

        // Check for timeout (STK push expires after ~60 seconds)
        $checkoutInitiatedAt = $subscription->updated_at;
        if ($checkoutInitiatedAt && $checkoutInitiatedAt->diffInMinutes(now()) > 2) {
            // Payment request likely timed out, query M-Pesa to confirm
            $result = $this->mpesaService->queryStatus($subscription->mpesa_checkout_id);

            if (!$result['success'] || ($result['result_code'] ?? null) === '1037') {
                $subscription->update([
                    'status' => 'timeout',
                    'mpesa_result_code' => $result['result_code'] ?? '1037',
                    'mpesa_result_desc' => $result['message'] ?? 'Request timed out',
                ]);

                return response()->json([
                    'status' => 'timeout',
                    'message' => 'Payment request timed out. Please try again.',
                    'can_retry' => true,
                ]);
            }
        }

        // Query M-Pesa for status
        $result = $this->mpesaService->queryStatus($subscription->mpesa_checkout_id);

        if ($result['success']) {
            $subscription->refresh();
            return response()->json([
                'status' => $subscription->status,
                'message' => $subscription->status === 'active'
                    ? 'Payment successful!'
                    : 'Payment is being processed...',
                'redirect' => $subscription->status === 'active' ? route('learn.subscription') : null,
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => $result['message'] ?? 'Payment is being processed...',
        ]);
    }

    /**
     * Get human-readable payment failure message.
     */
    protected function getPaymentFailureMessage(string $status, ?string $resultDesc): string
    {
        return match ($status) {
            'cancelled' => 'Payment was cancelled. You can try again when ready.',
            'timeout' => 'Payment request timed out. Please try again.',
            'failed' => $resultDesc
                ? 'Payment failed: ' . $resultDesc
                : 'Payment could not be processed. Please try again.',
            default => 'Payment could not be completed. Please try again.',
        };
    }

    /**
     * Cancel a pending subscription.
     */
    public function cancelSubscription(Subscription $subscription)
    {
        /** @var User $user */
        $user = Auth::user();

        // Ensure the subscription belongs to the current user
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Only allow canceling pending subscriptions
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Only pending subscriptions can be cancelled.');
        }

        // Delete the subscription
        $subscription->delete();

        return back()->with('success', 'Pending subscription has been cancelled.');
    }
}
