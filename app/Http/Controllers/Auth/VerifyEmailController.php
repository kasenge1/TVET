<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectUser($user, true);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->redirectUser($user, true);
    }

    /**
     * Redirect user to appropriate page based on role.
     */
    protected function redirectUser($user, bool $verified = false): RedirectResponse
    {
        $suffix = $verified ? '?verified=1' : '';

        // Redirect admins to admin dashboard
        if ($user->hasAnyRole(['super-admin', 'admin', 'content-manager', 'question-editor'])) {
            return redirect()->intended(route('admin.dashboard') . $suffix);
        }

        // Redirect students to learn page
        return redirect()->intended(route('learn.index') . $suffix);
    }
}
