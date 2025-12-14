<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $contact = SiteSetting::getContactSettings();
        $social = SiteSetting::getSocialSettings();

        return view('pages.contact', compact('contact', 'social'));
    }

    /**
     * Handle contact form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Get admin email from settings
        $adminEmail = SiteSetting::get('contact_email', config('mail.from.address'));

        try {
            // Send email to admin
            Mail::send('emails.contact', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'messageContent' => $validated['message'],
            ], function ($mail) use ($validated, $adminEmail) {
                $mail->to($adminEmail)
                    ->replyTo($validated['email'], $validated['name'])
                    ->subject('Contact Form: ' . $validated['subject']);
            });

            return back()->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Contact form email failed: ' . $e->getMessage());

            return back()->with('error', 'Sorry, there was an error sending your message. Please try again later or contact us directly.');
        }
    }
}
