@extends('layouts.frontend')

@section('title', 'Privacy Policy - TVET Revision')
@section('description', 'Read our Privacy Policy to understand how TVET Revision collects, uses, and protects your personal information.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container text-center" style="padding: 3.5rem 0;">
        <h1 class="fe-hero-title" style="font-size: 2.5rem;">Privacy Policy</h1>
        <p class="fe-hero-subtitle mx-auto">Your privacy is important to us. Learn how we collect, use, and protect your data.</p>
    </div>
</section>

<!-- Content Section -->
<section class="fe-section" style="background: var(--fe-bg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fe-sidebar-card" style="padding: 2rem;">
                    <p style="color: var(--fe-text-muted); margin-bottom: 2rem;">Last updated: {{ date('F d, Y') }}</p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">1. Introduction</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        Welcome to TVET Revision ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">2. Information We Collect</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">We collect information that you provide directly to us, including:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li><strong>Account Information:</strong> Name, email address, phone number, and password when you register for an account.</li>
                        <li><strong>Course Enrollment:</strong> Information about the courses you select during registration.</li>
                        <li><strong>Payment Information:</strong> M-Pesa phone number for subscription payments (we do not store full payment details).</li>
                        <li><strong>Usage Data:</strong> Information about how you use our platform, including questions viewed, progress, and bookmarks.</li>
                        <li><strong>Device Information:</strong> IP address, browser type, and device information for security and analytics purposes.</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">3. How We Use Your Information</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">We use the information we collect to:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li>Provide, maintain, and improve our services</li>
                        <li>Process transactions and send related information</li>
                        <li>Send you technical notices, updates, and support messages</li>
                        <li>Respond to your comments, questions, and requests</li>
                        <li>Track your learning progress and provide personalized recommendations</li>
                        <li>Monitor and analyze usage and trends to improve user experience</li>
                        <li>Detect, prevent, and address technical issues and security threats</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">4. Data Security</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">5. Data Retention</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        We retain your personal information for as long as your account is active or as needed to provide you services. We may retain certain information as required by law or for legitimate business purposes, such as resolving disputes and enforcing our agreements.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">6. Third-Party Services</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">We may share your information with third-party service providers for:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li><strong>Payment Processing:</strong> Safaricom M-Pesa for subscription payments</li>
                        <li><strong>Analytics:</strong> Google Analytics to understand how our service is used</li>
                        <li><strong>Advertising:</strong> Google AdSense for displaying relevant advertisements (for free tier users)</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">7. Your Rights</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">You have the right to:</p>
                    <ul style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        <li>Access the personal information we hold about you</li>
                        <li>Request correction of inaccurate data</li>
                        <li>Request deletion of your account and associated data</li>
                        <li>Opt out of marketing communications</li>
                        <li>Export your data in a portable format</li>
                    </ul>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">8. Cookies</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        We use cookies and similar tracking technologies to track activity on our service and hold certain information. Cookies are files with a small amount of data that may include an anonymous unique identifier. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">9. Children's Privacy</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        Our service is intended for users who are at least 16 years old. We do not knowingly collect personal information from children under 16. If we become aware that we have collected personal information from a child under 16, we will take steps to delete such information.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">10. Changes to This Policy</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 2rem; line-height: 1.8;">
                        We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.
                    </p>

                    <h4 class="fw-bold mb-3" style="font-size: 1.1rem;">11. Contact Us</h4>
                    <p style="color: var(--fe-text-secondary); margin-bottom: 1rem; line-height: 1.8;">
                        If you have any questions about this Privacy Policy or our data practices, please contact us at:
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
