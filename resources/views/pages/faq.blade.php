@extends('layouts.frontend')

@section('title', 'Frequently Asked Questions - TVET Revision')
@section('description', 'Find answers to frequently asked questions about TVET Revision, accounts, subscriptions, and more.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 fw-bold mb-3">Frequently Asked Questions</h1>
                <p class="lead opacity-90">Find answers to common questions about TVET Revision.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Getting Started -->
                <h4 class="fw-bold mb-4"><i class="bi bi-rocket-takeoff text-primary me-2"></i>Getting Started</h4>
                <div class="accordion mb-5" id="gettingStartedAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#gs1">
                                What is TVET Revision?
                            </button>
                        </h2>
                        <div id="gs1" class="accordion-collapse collapse show" data-bs-parent="#gettingStartedAccordion">
                            <div class="accordion-body text-muted">
                                TVET Revision is a comprehensive online platform designed to help TVET (Technical and Vocational Education and Training) students prepare for their KNEC examinations. We provide past exam questions with detailed answers organized by course and unit, making it easy for you to study and revise effectively.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#gs2">
                                How do I create an account?
                            </button>
                        </h2>
                        <div id="gs2" class="accordion-collapse collapse" data-bs-parent="#gettingStartedAccordion">
                            <div class="accordion-body text-muted">
                                Creating an account is simple:
                                <ol class="mt-2 mb-0">
                                    <li>Click the "Get Started" or "Register" button on our homepage</li>
                                    <li>Enter your name, email address, and create a password</li>
                                    <li>Select your TVET course from the available options</li>
                                    <li>Verify your email address</li>
                                    <li>Start revising immediately!</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#gs3">
                                Can I change my course after registration?
                            </button>
                        </h2>
                        <div id="gs3" class="accordion-collapse collapse" data-bs-parent="#gettingStartedAccordion">
                            <div class="accordion-body text-muted">
                                <strong>No, course selection is permanent.</strong> We follow a "one student, one course" model to ensure focused learning. Please choose your course carefully during registration as this cannot be changed later. If you need access to a different course, you would need to create a new account with a different email address.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#gs4">
                                Is TVET Revision free to use?
                            </button>
                        </h2>
                        <div id="gs4" class="accordion-collapse collapse" data-bs-parent="#gettingStartedAccordion">
                            <div class="accordion-body text-muted">
                                Yes! You can access all our questions and answers completely free. We display advertisements to support our free tier. If you prefer an ad-free, distraction-free learning experience, you can upgrade to our premium subscription.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#gs5">
                                Which courses are available?
                            </button>
                        </h2>
                        <div id="gs5" class="accordion-collapse collapse" data-bs-parent="#gettingStartedAccordion">
                            <div class="accordion-body text-muted">
                                We offer revision materials for various TVET courses across different levels including Certificate, Diploma, and Higher Diploma programs. Visit our Courses page to see the full list of available courses. We're constantly adding new courses and updating our question bank.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Using the Platform -->
                <h4 class="fw-bold mb-4"><i class="bi bi-laptop text-primary me-2"></i>Using the Platform</h4>
                <div class="accordion mb-5" id="usingPlatformAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#up1">
                                How do I start revising?
                            </button>
                        </h2>
                        <div id="up1" class="accordion-collapse collapse" data-bs-parent="#usingPlatformAccordion">
                            <div class="accordion-body text-muted">
                                After logging in, you'll land on your course dashboard showing all available units. Simply click on any unit to view its questions. Each question displays the question text followed by the answer. Use the navigation buttons to move between questions or select specific questions from the list.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#up2">
                                How do I save questions for later?
                            </button>
                        </h2>
                        <div id="up2" class="accordion-collapse collapse" data-bs-parent="#usingPlatformAccordion">
                            <div class="accordion-body text-muted">
                                You can bookmark any question by clicking the bookmark icon on the question page. All your saved questions can be found in the "Saved" section, accessible from the navigation menu. This is great for creating a list of questions you want to revisit or find challenging.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#up3">
                                Can I track my learning progress?
                            </button>
                        </h2>
                        <div id="up3" class="accordion-collapse collapse" data-bs-parent="#usingPlatformAccordion">
                            <div class="accordion-body text-muted">
                                Yes! We automatically track your progress as you study. Your dashboard shows your overall course completion percentage and progress for each unit. Questions you've viewed are marked, helping you identify topics you haven't covered yet.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#up4">
                                Can I search for specific topics?
                            </button>
                        </h2>
                        <div id="up4" class="accordion-collapse collapse" data-bs-parent="#usingPlatformAccordion">
                            <div class="accordion-body text-muted">
                                Yes! Use our search feature to find questions on specific topics. You can search by keywords, and the results will show relevant questions from your enrolled course. This is particularly useful when revising specific topics for your exams.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#up5">
                                Can I access TVET Revision on my phone?
                            </button>
                        </h2>
                        <div id="up5" class="accordion-collapse collapse" data-bs-parent="#usingPlatformAccordion">
                            <div class="accordion-body text-muted">
                                Absolutely! Our platform is fully mobile-responsive and works great on phones, tablets, and computers. Premium subscribers can even install our app for quick access directly from their home screen. Study anywhere, anytime - whether you're at home, in class, or on the go.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription & Payment -->
                <h4 class="fw-bold mb-4"><i class="bi bi-credit-card text-primary me-2"></i>Subscription & Payment</h4>
                <div class="accordion mb-5" id="subscriptionAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sp1">
                                What are the benefits of premium subscription?
                            </button>
                        </h2>
                        <div id="sp1" class="accordion-collapse collapse" data-bs-parent="#subscriptionAccordion">
                            <div class="accordion-body text-muted">
                                Premium subscribers enjoy:
                                <ul class="mt-2 mb-0">
                                    <li><strong>Ad-free experience</strong> - Study without any distractions</li>
                                    <li><strong>Install as App</strong> - Add TVET Revision to your home screen for quick access</li>
                                    <li><strong>Support development</strong> - Help us add more courses and features</li>
                                </ul>
                                Both free and premium users have access to the same questions and answers.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sp2">
                                How do I subscribe to premium?
                            </button>
                        </h2>
                        <div id="sp2" class="accordion-collapse collapse" data-bs-parent="#subscriptionAccordion">
                            <div class="accordion-body text-muted">
                                To subscribe:
                                <ol class="mt-2 mb-0">
                                    <li>Log in to your account</li>
                                    <li>Go to "Premium" from the navigation menu</li>
                                    <li>Choose your preferred plan (weekly, monthly, or yearly)</li>
                                    <li>Enter your M-Pesa phone number</li>
                                    <li>You'll receive an STK push notification - enter your M-Pesa PIN to complete payment</li>
                                    <li>Your premium access activates immediately!</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sp3">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="sp3" class="accordion-collapse collapse" data-bs-parent="#subscriptionAccordion">
                            <div class="accordion-body text-muted">
                                We currently accept payments via <strong>M-Pesa (Lipa Na M-Pesa)</strong>. This is the most convenient payment method for our Kenyan users. Simply enter your M-Pesa registered phone number during checkout, and you'll receive a prompt on your phone to enter your M-Pesa PIN to authorize the payment.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sp4">
                                Do subscriptions auto-renew?
                            </button>
                        </h2>
                        <div id="sp4" class="accordion-collapse collapse" data-bs-parent="#subscriptionAccordion">
                            <div class="accordion-body text-muted">
                                <strong>No, subscriptions do not auto-renew.</strong> When your subscription period ends, your account simply reverts to the free tier. You won't be charged automatically. If you wish to continue with premium, you'll need to manually subscribe again.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sp5">
                                Can I get a refund?
                            </button>
                        </h2>
                        <div id="sp5" class="accordion-collapse collapse" data-bs-parent="#subscriptionAccordion">
                            <div class="accordion-body text-muted">
                                Subscription fees are generally non-refundable once the subscription period has begun. However, if you experience technical issues that prevent you from using the service, please contact our support team within 24 hours of your payment, and we'll work with you to find a solution.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sp6">
                                I paid but didn't receive premium access. What should I do?
                            </button>
                        </h2>
                        <div id="sp6" class="accordion-collapse collapse" data-bs-parent="#subscriptionAccordion">
                            <div class="accordion-body text-muted">
                                If your payment was successful but premium access wasn't activated:
                                <ol class="mt-2 mb-0">
                                    <li>Wait a few minutes and refresh the page</li>
                                    <li>Check your M-Pesa message to confirm the payment went through</li>
                                    <li>Log out and log back in</li>
                                    <li>If the issue persists, contact us with your M-Pesa transaction code</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account & Security -->
                <h4 class="fw-bold mb-4"><i class="bi bi-shield-check text-primary me-2"></i>Account & Security</h4>
                <div class="accordion mb-5" id="accountAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#as1">
                                How do I change my password?
                            </button>
                        </h2>
                        <div id="as1" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                            <div class="accordion-body text-muted">
                                To change your password:
                                <ol class="mt-2 mb-0">
                                    <li>Log in to your account</li>
                                    <li>Click on "Account" or "Settings"</li>
                                    <li>Find the "Change Password" or "Security" section</li>
                                    <li>Enter your current password, then your new password twice</li>
                                    <li>Click "Update Password" to save</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#as2">
                                I forgot my password. What should I do?
                            </button>
                        </h2>
                        <div id="as2" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                            <div class="accordion-body text-muted">
                                No problem! Click on "Forgot Password?" on the login page. Enter your registered email address, and we'll send you a password reset link. Click the link in the email to create a new password. If you don't see the email, check your spam/junk folder.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#as3">
                                Can I update my profile information?
                            </button>
                        </h2>
                        <div id="as3" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                            <div class="accordion-body text-muted">
                                Yes, you can update your name and other profile details from the Settings page. However, your email address and enrolled course cannot be changed after registration to maintain account security and integrity.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#as4">
                                How do I delete my account?
                            </button>
                        </h2>
                        <div id="as4" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                            <div class="accordion-body text-muted">
                                You can delete your account from the Settings page. Look for the "Delete Account" option at the bottom. You'll need to confirm by entering your password. <strong>Please note:</strong> This action is permanent and irreversible. All your data including progress, bookmarks, and subscription history will be permanently deleted.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#as5">
                                Is my data safe?
                            </button>
                        </h2>
                        <div id="as5" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                            <div class="accordion-body text-muted">
                                Yes, we take data security seriously. Your password is encrypted and never stored in plain text. We use secure connections (HTTPS) for all data transmission. We do not share your personal information with third parties for marketing purposes.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Issues -->
                <h4 class="fw-bold mb-4"><i class="bi bi-wrench text-primary me-2"></i>Technical Issues</h4>
                <div class="accordion mb-5" id="technicalAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#ti1">
                                The website is loading slowly. What can I do?
                            </button>
                        </h2>
                        <div id="ti1" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                            <div class="accordion-body text-muted">
                                Try these steps:
                                <ul class="mt-2 mb-0">
                                    <li>Check your internet connection</li>
                                    <li>Clear your browser cache and cookies</li>
                                    <li>Try using a different browser</li>
                                    <li>If on mobile data, try switching to WiFi or vice versa</li>
                                    <li>If the issue persists, please contact us</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#ti2">
                                I can't log in to my account. What should I do?
                            </button>
                        </h2>
                        <div id="ti2" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                            <div class="accordion-body text-muted">
                                If you're having trouble logging in:
                                <ul class="mt-2 mb-0">
                                    <li>Make sure you're using the correct email address</li>
                                    <li>Check that Caps Lock is not on when entering your password</li>
                                    <li>Try resetting your password using "Forgot Password?"</li>
                                    <li>Clear your browser cookies and try again</li>
                                    <li>If your account was blocked, contact support for assistance</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#ti3">
                                Images or content are not loading properly
                            </button>
                        </h2>
                        <div id="ti3" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                            <div class="accordion-body text-muted">
                                This could be due to a slow internet connection or browser caching issues. Try refreshing the page (Ctrl+F5 or Cmd+Shift+R), clearing your browser cache, or switching to a different browser. If using a VPN, try disabling it temporarily.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Still have questions?</h5>
                        <p class="mb-4 opacity-90">Can't find what you're looking for? Our support team is here to help!</p>
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-envelope me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
