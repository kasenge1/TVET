<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\SiteSetting;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Get published courses with unit and question counts (optimized - no N+1)
        $courses = Course::where('is_published', true)
            ->withCount(['units'])
            ->with(['units' => function($q) {
                $q->withCount('questions');
            }])
            ->orderBy('title')
            ->get();

        // Calculate questions count from eager loaded data
        $courses->each(function ($course) {
            $course->questions_count = $course->units->sum('questions_count');
        });

        return view('auth.register', compact('courses'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'course_id' => ['required', 'exists:courses,id'],
        ];

        // Add reCAPTCHA validation if enabled
        if (SiteSetting::recaptchaEnabledFor('register')) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha('register')];
        }

        $request->validate($rules, [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        // Verify the course is published
        $course = Course::where('id', $request->course_id)
            ->where('is_published', true)
            ->firstOrFail();

        $user = null;

        DB::transaction(function () use ($request, $course, &$user) {
            // Create user with student role
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            // Assign Spatie student role
            $user->assignRole('student');

            // Create enrollment
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrolled_at' => now(),
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        // Redirect to the learning page
        return redirect()->route('learn.index');
    }
}
