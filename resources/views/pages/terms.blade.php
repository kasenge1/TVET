@extends('layouts.frontend')

@section('title', 'Terms of Service - TVET Revision')
@section('description', 'Read our Terms of Service to understand the rules and guidelines for using TVET Revision.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container text-center" style="padding: 3.5rem 0;">
        <h1 class="fe-hero-title" style="font-size: 2.5rem;">Terms of Service</h1>
        <p class="fe-hero-subtitle mx-auto">Please read these terms carefully before using our platform.</p>
    </div>
</section>

<!-- Content Section -->
<section class="fe-section" style="background: var(--fe-bg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fe-sidebar-card" style="padding: 2rem;">
                    <p style="color: var(--fe-text-muted); margin-bottom: 2rem;">Last updated: {{ date('F d, Y') }}</p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">1. Acceptance of Terms</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        By accessing or using TVET Revision ("Service"), you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, please do not use our Service. We reserve the right to modify these Terms at any time, and your continued use of the Service after such modifications constitutes acceptance of the updated Terms.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">2. Description of Service</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        TVET Revision is an online learning platform that provides access to past examination questions and answers for TVET (Technical and Vocational Education and Training) courses in Kenya. Our Service is designed to help students prepare for their KNEC examinations.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">3. User Registration</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">To use our Service, you must:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li>Be at least 16 years of age</li>
                        <li>Register for an account with accurate and complete information</li>
                        <li>Maintain the security of your account credentials</li>
                        <li>Accept responsibility for all activities that occur under your account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">4. Course Enrollment</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <strong>Important:</strong> When you register, you will select one course to enroll in. This selection is permanent and cannot be changed. Please choose your course carefully during registration. Each student account is limited to one enrolled course.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">5. Subscription and Payments</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">Our Service offers both free and premium tiers:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li><strong>Free Tier:</strong> Access to all questions and answers with advertisements displayed</li>
                        <li><strong>Premium Tier:</strong> Ad-free experience with additional features</li>
                        <li>Payments are processed through M-Pesa</li>
                        <li>Subscription fees are non-refundable once the subscription period has begun</li>
                        <li>We reserve the right to change subscription prices with reasonable notice</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">6. Acceptable Use</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">You agree not to:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li>Share your account credentials with others</li>
                        <li>Copy, distribute, or reproduce our content without permission</li>
                        <li>Use automated tools to access or scrape our Service</li>
                        <li>Attempt to bypass any security measures</li>
                        <li>Use the Service for any illegal or unauthorized purpose</li>
                        <li>Interfere with or disrupt the Service or its servers</li>
                        <li>Impersonate any person or entity</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">7. Intellectual Property</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        All content on TVET Revision, including but not limited to questions, answers, text, graphics, logos, and software, is the property of TVET Revision or its content providers and is protected by intellectual property laws. You may not copy, modify, distribute, or create derivative works based on our content without explicit written permission.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">8. Content Disclaimer</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        While we strive to provide accurate and up-to-date content, TVET Revision does not guarantee the accuracy, completeness, or reliability of any information on our platform. Our content is intended for educational purposes only and should be used as a supplement to, not a replacement for, official study materials and coursework.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">9. Limitation of Liability</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        To the maximum extent permitted by law, TVET Revision shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or other intangible losses, resulting from your use of or inability to use the Service, even if we have been advised of the possibility of such damages.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">10. Account Termination</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        We reserve the right to suspend or terminate your account at any time, without notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties, or for any other reason at our sole discretion. You may also delete your account at any time through your account settings.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">11. Modifications to Service</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        We reserve the right to modify, suspend, or discontinue the Service (or any part thereof) at any time, with or without notice. We shall not be liable to you or any third party for any modification, suspension, or discontinuation of the Service.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">12. Governing Law</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        These Terms shall be governed by and construed in accordance with the laws of Kenya. Any disputes arising from these Terms or your use of the Service shall be subject to the exclusive jurisdiction of the courts of Kenya.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">13. Severability</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        If any provision of these Terms is held to be invalid or unenforceable, the remaining provisions shall continue in full force and effect. The invalid or unenforceable provision shall be modified to the minimum extent necessary to make it valid and enforceable.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">14. Contact Information</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">
                        If you have any questions about these Terms, please contact us at:
                    </p>
                    <ul style="color: var(--fe-text-secondary); line-height: 1.8; margin-bottom: 0;">
                        <li>Email: <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'support@tvetrevision.co.ke') }}">{{ \App\Models\SiteSetting::get('contact_email', 'support@tvetrevision.co.ke') }}</a></li>
                        <li>Phone: {{ \App\Models\SiteSetting::get('contact_phone', '+254 700 000 000') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
