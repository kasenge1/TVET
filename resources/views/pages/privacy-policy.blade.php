@extends('layouts.frontend')

@section('title', 'Privacy Policy - TVET Revision')
@section('description', 'Read our Privacy Policy to understand how TVET Revision collects, uses, and protects your personal information.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 fw-bold mb-3">Privacy Policy</h1>
                <p class="lead opacity-90">Your privacy is important to us. Learn how we collect, use, and protect your data.</p>
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

                        <h4 class="fw-bold mb-3">1. Introduction</h4>
                        <p class="text-muted mb-4">
                            Welcome to TVET Revision ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services.
                        </p>

                        <h4 class="fw-bold mb-3">2. Information We Collect</h4>
                        <p class="text-muted mb-3">We collect information that you provide directly to us, including:</p>
                        <ul class="text-muted mb-4">
                            <li><strong>Account Information:</strong> Name, email address, phone number, and password when you register for an account.</li>
                            <li><strong>Course Enrollment:</strong> Information about the courses you select during registration.</li>
                            <li><strong>Payment Information:</strong> M-Pesa phone number for subscription payments (we do not store full payment details).</li>
                            <li><strong>Usage Data:</strong> Information about how you use our platform, including questions viewed, progress, and bookmarks.</li>
                            <li><strong>Device Information:</strong> IP address, browser type, and device information for security and analytics purposes.</li>
                        </ul>

                        <h4 class="fw-bold mb-3">3. How We Use Your Information</h4>
                        <p class="text-muted mb-3">We use the information we collect to:</p>
                        <ul class="text-muted mb-4">
                            <li>Provide, maintain, and improve our services</li>
                            <li>Process transactions and send related information</li>
                            <li>Send you technical notices, updates, and support messages</li>
                            <li>Respond to your comments, questions, and requests</li>
                            <li>Track your learning progress and provide personalized recommendations</li>
                            <li>Monitor and analyze usage and trends to improve user experience</li>
                            <li>Detect, prevent, and address technical issues and security threats</li>
                        </ul>

                        <h4 class="fw-bold mb-3">4. Data Security</h4>
                        <p class="text-muted mb-4">
                            We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.
                        </p>

                        <h4 class="fw-bold mb-3">5. Data Retention</h4>
                        <p class="text-muted mb-4">
                            We retain your personal information for as long as your account is active or as needed to provide you services. We may retain certain information as required by law or for legitimate business purposes, such as resolving disputes and enforcing our agreements.
                        </p>

                        <h4 class="fw-bold mb-3">6. Third-Party Services</h4>
                        <p class="text-muted mb-3">We may share your information with third-party service providers for:</p>
                        <ul class="text-muted mb-4">
                            <li><strong>Payment Processing:</strong> Safaricom M-Pesa for subscription payments</li>
                            <li><strong>Analytics:</strong> Google Analytics to understand how our service is used</li>
                            <li><strong>Advertising:</strong> Google AdSense for displaying relevant advertisements (for free tier users)</li>
                        </ul>

                        <h4 class="fw-bold mb-3">7. Your Rights</h4>
                        <p class="text-muted mb-3">You have the right to:</p>
                        <ul class="text-muted mb-4">
                            <li>Access the personal information we hold about you</li>
                            <li>Request correction of inaccurate data</li>
                            <li>Request deletion of your account and associated data</li>
                            <li>Opt out of marketing communications</li>
                            <li>Export your data in a portable format</li>
                        </ul>

                        <h4 class="fw-bold mb-3">8. Cookies</h4>
                        <p class="text-muted mb-4">
                            We use cookies and similar tracking technologies to track activity on our service and hold certain information. Cookies are files with a small amount of data that may include an anonymous unique identifier. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                        </p>

                        <h4 class="fw-bold mb-3">9. Children's Privacy</h4>
                        <p class="text-muted mb-4">
                            Our service is intended for users who are at least 16 years old. We do not knowingly collect personal information from children under 16. If we become aware that we have collected personal information from a child under 16, we will take steps to delete such information.
                        </p>

                        <h4 class="fw-bold mb-3">10. Changes to This Policy</h4>
                        <p class="text-muted mb-4">
                            We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.
                        </p>

                        <h4 class="fw-bold mb-3">11. Contact Us</h4>
                        <p class="text-muted mb-4">
                            If you have any questions about this Privacy Policy or our data practices, please contact us at:
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
