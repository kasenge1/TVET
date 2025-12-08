@extends('layouts.frontend')

@section('title', 'Terms of Service - TVET Revision')
@section('description', 'Read our Terms of Service to understand the rules and guidelines for using TVET Revision.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 fw-bold mb-3">Terms of Service</h1>
                <p class="lead opacity-90">Please read these terms carefully before using our platform.</p>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-muted mb-4">Last updated: {{ date('F d, Y') }}</p>

                        <h4 class="fw-bold mb-3">1. Acceptance of Terms</h4>
                        <p class="text-muted mb-4">
                            By accessing or using TVET Revision ("Service"), you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, please do not use our Service. We reserve the right to modify these Terms at any time, and your continued use of the Service after such modifications constitutes acceptance of the updated Terms.
                        </p>

                        <h4 class="fw-bold mb-3">2. Description of Service</h4>
                        <p class="text-muted mb-4">
                            TVET Revision is an online learning platform that provides access to past examination questions and answers for TVET (Technical and Vocational Education and Training) courses in Kenya. Our Service is designed to help students prepare for their KNEC examinations.
                        </p>

                        <h4 class="fw-bold mb-3">3. User Registration</h4>
                        <p class="text-muted mb-3">To use our Service, you must:</p>
                        <ul class="text-muted mb-4">
                            <li>Be at least 16 years of age</li>
                            <li>Register for an account with accurate and complete information</li>
                            <li>Maintain the security of your account credentials</li>
                            <li>Accept responsibility for all activities that occur under your account</li>
                            <li>Notify us immediately of any unauthorized use of your account</li>
                        </ul>

                        <h4 class="fw-bold mb-3">4. Course Enrollment</h4>
                        <p class="text-muted mb-4">
                            <strong>Important:</strong> When you register, you will select one course to enroll in. This selection is permanent and cannot be changed. Please choose your course carefully during registration. Each student account is limited to one enrolled course.
                        </p>

                        <h4 class="fw-bold mb-3">5. Subscription and Payments</h4>
                        <p class="text-muted mb-3">Our Service offers both free and premium tiers:</p>
                        <ul class="text-muted mb-4">
                            <li><strong>Free Tier:</strong> Access to all questions and answers with advertisements displayed</li>
                            <li><strong>Premium Tier:</strong> Ad-free experience with additional features</li>
                            <li>Payments are processed through M-Pesa</li>
                            <li>Subscription fees are non-refundable once the subscription period has begun</li>
                            <li>We reserve the right to change subscription prices with reasonable notice</li>
                        </ul>

                        <h4 class="fw-bold mb-3">6. Acceptable Use</h4>
                        <p class="text-muted mb-3">You agree not to:</p>
                        <ul class="text-muted mb-4">
                            <li>Share your account credentials with others</li>
                            <li>Copy, distribute, or reproduce our content without permission</li>
                            <li>Use automated tools to access or scrape our Service</li>
                            <li>Attempt to bypass any security measures</li>
                            <li>Use the Service for any illegal or unauthorized purpose</li>
                            <li>Interfere with or disrupt the Service or its servers</li>
                            <li>Impersonate any person or entity</li>
                        </ul>

                        <h4 class="fw-bold mb-3">7. Intellectual Property</h4>
                        <p class="text-muted mb-4">
                            All content on TVET Revision, including but not limited to questions, answers, text, graphics, logos, and software, is the property of TVET Revision or its content providers and is protected by intellectual property laws. You may not copy, modify, distribute, or create derivative works based on our content without explicit written permission.
                        </p>

                        <h4 class="fw-bold mb-3">8. Content Disclaimer</h4>
                        <p class="text-muted mb-4">
                            While we strive to provide accurate and up-to-date content, TVET Revision does not guarantee the accuracy, completeness, or reliability of any information on our platform. Our content is intended for educational purposes only and should be used as a supplement to, not a replacement for, official study materials and coursework.
                        </p>

                        <h4 class="fw-bold mb-3">9. Limitation of Liability</h4>
                        <p class="text-muted mb-4">
                            To the maximum extent permitted by law, TVET Revision shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or other intangible losses, resulting from your use of or inability to use the Service, even if we have been advised of the possibility of such damages.
                        </p>

                        <h4 class="fw-bold mb-3">10. Account Termination</h4>
                        <p class="text-muted mb-4">
                            We reserve the right to suspend or terminate your account at any time, without notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties, or for any other reason at our sole discretion. You may also delete your account at any time through your account settings.
                        </p>

                        <h4 class="fw-bold mb-3">11. Modifications to Service</h4>
                        <p class="text-muted mb-4">
                            We reserve the right to modify, suspend, or discontinue the Service (or any part thereof) at any time, with or without notice. We shall not be liable to you or any third party for any modification, suspension, or discontinuation of the Service.
                        </p>

                        <h4 class="fw-bold mb-3">12. Governing Law</h4>
                        <p class="text-muted mb-4">
                            These Terms shall be governed by and construed in accordance with the laws of Kenya. Any disputes arising from these Terms or your use of the Service shall be subject to the exclusive jurisdiction of the courts of Kenya.
                        </p>

                        <h4 class="fw-bold mb-3">13. Severability</h4>
                        <p class="text-muted mb-4">
                            If any provision of these Terms is held to be invalid or unenforceable, the remaining provisions shall continue in full force and effect. The invalid or unenforceable provision shall be modified to the minimum extent necessary to make it valid and enforceable.
                        </p>

                        <h4 class="fw-bold mb-3">14. Contact Information</h4>
                        <p class="text-muted mb-4">
                            If you have any questions about these Terms, please contact us at:
                        </p>
                        <ul class="text-muted mb-0">
                            <li>Email: <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'support@tvetrevision.co.ke') }}">{{ \App\Models\SiteSetting::get('contact_email', 'support@tvetrevision.co.ke') }}</a></li>
                            <li>Phone: {{ \App\Models\SiteSetting::get('contact_phone', '+254 700 000 000') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
