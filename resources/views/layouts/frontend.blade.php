<!DOCTYPE html>
@php
    $darkModeEnabled = \App\Models\SiteSetting::darkModeEnabled();
    $defaultTheme = \App\Models\SiteSetting::get('default_theme', 'light');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', config('app.name', 'TVET Revision'))</title>
    <meta name="description" content="@yield('description', 'Access comprehensive TVET past exam questions, study materials, and prepare effectively for your KNEC exams.')">
    <meta name="keywords" content="@yield('keywords', 'TVET, KNEC, past papers, exam revision, Kenya education, diploma, certificate, technical education')">
    <meta name="author" content="TVET Revision">
    <meta name="robots" content="@yield('robots', 'index, follow')">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'TVET Revision - KNEC Exam Preparation')">
    <meta property="og:description" content="@yield('og_description', 'Access comprehensive TVET past exam questions, study materials, and prepare effectively for your KNEC exams.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:site_name" content="TVET Revision">
    <meta property="og:locale" content="en_KE">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', 'TVET Revision - KNEC Exam Preparation')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Access comprehensive TVET past exam questions, study materials, and prepare effectively for your KNEC exams.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.png'))">

    <!-- Favicon -->
    @php
        $favicon = \App\Models\SiteSetting::getFavicon();
        $pwaEnabled = \App\Models\SiteSetting::pwaEnabled();
    @endphp
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset($favicon) }}">
        <link rel="shortcut icon" href="{{ asset($favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset($favicon) }}">
    @endif

    <!-- PWA Meta Tags -->
    @if($pwaEnabled)
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TVET Revision">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=comfortaa:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- KaTeX for Math Formulas -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0b5ed7;
            /* Override Bootstrap's default font family */
            --bs-body-font-family: 'Comfortaa', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --bs-font-sans-serif: 'Comfortaa', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            font-family: 'Comfortaa', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        .navbar-brand {
            font-size: 1.4rem;
            text-decoration: none;
        }

        /* Text Logo Styling */
        .text-logo {
            text-decoration: none;
        }

        .text-logo .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border-radius: 10px;
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .text-logo .logo-text {
            font-weight: 700;
            font-size: 1.25rem;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .nav-link {
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Hero Gradient */
        .hero-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 50%, #084298 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Cards */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Feature Icons */
        .feature-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.5rem;
        }

        /* Stats */
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
        }

        /* Footer links */
        footer a:hover {
            color: #fff !important;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 991.98px) {
            /* Tablet adjustments */
            .display-5 {
                font-size: 2rem;
            }

            .lead {
                font-size: 1rem;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 767.98px) {
            /* Mobile adjustments */
            .display-5 {
                font-size: 1.5rem;
            }

            .display-4 {
                font-size: 1.75rem;
            }

            .display-3 {
                font-size: 2rem;
            }

            .display-1 {
                font-size: 2.5rem;
            }

            .lead {
                font-size: 0.9rem;
                line-height: 1.5;
            }

            h2.fw-bold {
                font-size: 1.35rem;
            }

            h4.fw-bold {
                font-size: 1.1rem;
            }

            h5.fw-bold, h5.card-title {
                font-size: 0.95rem;
            }

            h6 {
                font-size: 0.875rem;
            }

            .stat-number {
                font-size: 1.75rem;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .btn {
                padding: 0.45rem 0.75rem;
                font-size: 0.8rem;
            }

            /* Hero section adjustments */
            .hero-gradient .container.py-5 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }

            /* Cards padding */
            .card-body {
                padding: 0.875rem;
            }

            .card-body.p-4 {
                padding: 1rem !important;
            }

            /* Feature icons smaller on mobile */
            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            /* Container padding */
            .container {
                padding-left: 0.875rem;
                padding-right: 0.875rem;
            }

            /* Section padding */
            section.py-5 {
                padding-top: 1.75rem !important;
                padding-bottom: 1.75rem !important;
            }

            .py-4 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            /* Gap adjustments */
            .gap-4 {
                gap: 0.875rem !important;
            }

            .gap-3 {
                gap: 0.625rem !important;
            }

            /* Margin adjustments */
            .mb-5 {
                margin-bottom: 1.5rem !important;
            }

            .mb-4 {
                margin-bottom: 0.875rem !important;
            }

            .mb-3 {
                margin-bottom: 0.625rem !important;
            }

            /* Form controls */
            .form-control, .form-select {
                font-size: 0.875rem;
                padding: 0.45rem 0.625rem;
            }

            /* Badge sizing */
            .badge {
                font-size: 0.65rem;
                padding: 0.3em 0.5em;
            }

            /* Small text */
            .small, small {
                font-size: 0.75rem;
            }

            /* Breadcrumb */
            .breadcrumb {
                font-size: 0.75rem;
            }

            /* Tables */
            .table {
                font-size: 0.8rem;
            }

            /* Stats on hero */
            .fs-3 {
                font-size: 1.15rem !important;
            }

            .fs-5 {
                font-size: 0.95rem !important;
            }

            /* Navbar brand */
            .navbar-brand {
                font-size: 1.1rem;
            }

            /* Card footer */
            .card-footer {
                padding: 0.625rem 0.875rem;
            }

            /* Input group */
            .input-group-text {
                padding: 0.45rem 0.625rem;
                font-size: 0.875rem;
            }

            /* Row gutters */
            .row.g-4 {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.75rem;
            }

            .row.g-3 {
                --bs-gutter-x: 0.625rem;
                --bs-gutter-y: 0.625rem;
            }
        }

        @media (max-width: 575.98px) {
            /* Extra small devices */
            .display-5 {
                font-size: 1.35rem;
            }

            .lead {
                font-size: 0.85rem;
            }

            h2.fw-bold {
                font-size: 1.2rem;
            }

            h5.fw-bold, h5.card-title {
                font-size: 0.9rem;
            }

            .btn-lg {
                padding: 0.45rem 0.875rem;
                font-size: 0.8rem;
            }

            /* Full width buttons on mobile for CTA */
            .hero-gradient .btn-lg {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .d-flex.flex-wrap.gap-3 > .btn-lg:not(:last-child) {
                margin-bottom: 0.5rem;
            }

            /* Padding adjustments */
            .px-5 {
                padding-left: 1.25rem !important;
                padding-right: 1.25rem !important;
            }

            .px-4 {
                padding-left: 0.875rem !important;
                padding-right: 0.875rem !important;
            }

            /* Card image heights */
            .card-img-top {
                height: 140px !important;
            }

            /* Step numbers smaller */
            .step-number {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }

        /* Dark Mode Styles */
        [data-bs-theme="dark"] {
            --bs-body-bg: #1a1a2e;
            --bs-body-color: #e4e4e7;
            --bs-border-color: #2d2d44;
        }

        [data-bs-theme="dark"] body {
            background-color: #1a1a2e;
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .navbar {
            background-color: #16162a !important;
            border-bottom: 1px solid #2d2d44;
        }

        [data-bs-theme="dark"] .card {
            background-color: #16162a;
            border-color: #2d2d44;
        }

        [data-bs-theme="dark"] .bg-light {
            background-color: #1e1e35 !important;
        }

        [data-bs-theme="dark"] .bg-white {
            background-color: #16162a !important;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #9ca3af !important;
        }

        [data-bs-theme="dark"] .border {
            border-color: #2d2d44 !important;
        }

        [data-bs-theme="dark"] .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.3) !important;
        }

        [data-bs-theme="dark"] footer {
            background-color: #0f0f1e !important;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #1e1e35;
            border-color: #2d2d44;
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #252542;
            border-color: #0d6efd;
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #16162a;
            border-color: #2d2d44;
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #1e1e35;
        }

        [data-bs-theme="dark"] .btn-outline-secondary {
            color: #9ca3af;
            border-color: #2d2d44;
        }

        [data-bs-theme="dark"] .table {
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .table > :not(caption) > * > * {
            background-color: transparent;
            border-bottom-color: #2d2d44;
        }

        [data-bs-theme="dark"] .breadcrumb-item a {
            color: #9ca3af;
        }

        [data-bs-theme="dark"] .breadcrumb-item.active {
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .alert {
            background-color: #1e1e35;
        }

        [data-bs-theme="dark"] .accordion-button {
            background-color: #16162a;
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: #1e1e35;
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: #16162a;
            border-color: #2d2d44;
        }

        [data-bs-theme="dark"] .text-logo .logo-text {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Theme toggle button styles */
        .theme-toggle {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            padding: 0;
            cursor: pointer;
            color: #495057;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            transition: background-color 0.2s, color 0.2s;
            flex-shrink: 0;
        }

        .theme-toggle:hover {
            background-color: rgba(0,0,0,0.08);
            border-radius: 50% !important;
        }

        .theme-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
            border-radius: 50% !important;
        }

        [data-bs-theme="dark"] .theme-toggle {
            color: #e4e4e7;
        }

        [data-bs-theme="dark"] .theme-toggle:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 50% !important;
        }

        .theme-toggle .bi-sun,
        [data-bs-theme="dark"] .theme-toggle .bi-moon {
            display: none;
        }

        [data-bs-theme="dark"] .theme-toggle .bi-sun {
            display: inline-block;
        }

        .theme-toggle .bi-moon {
            display: inline-block;
        }

        /* PWA Install Banner Styles */
        .install-banner {
            position: fixed;
            bottom: -200px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            max-width: 420px;
            width: calc(100% - 2rem);
            transition: bottom 0.4s ease-out;
        }

        .install-banner.show {
            bottom: 80px;
        }

        @media (min-width: 992px) {
            .install-banner.show {
                bottom: 1.5rem;
            }
        }

        .install-banner .card {
            border-radius: 16px;
            overflow: hidden;
        }

        .install-banner .close-banner {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
            font-size: 1.25rem;
        }

        .install-banner .close-banner:hover {
            color: #343a40;
        }

        [data-bs-theme="dark"] .install-banner .close-banner {
            color: #9ca3af;
        }

        [data-bs-theme="dark"] .install-banner .close-banner:hover {
            color: #e4e4e7;
        }

    </style>

    <!-- Dark Mode Script (load early to prevent flash) -->
    <script>
        (function() {
            const darkModeEnabled = {{ $darkModeEnabled ? 'true' : 'false' }};
            const defaultTheme = '{{ $defaultTheme }}';

            if (!darkModeEnabled) return;

            let theme = localStorage.getItem('theme');

            if (!theme) {
                if (defaultTheme === 'system') {
                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                } else {
                    theme = defaultTheme;
                }
            }

            if (theme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        })();
    </script>

    @stack('styles')

    <!-- Structured Data (JSON-LD) -->
    @php
        $socialLinks = \App\Models\SiteSetting::getSocialSettings();
        $sameAsArray = collect([
            $socialLinks['facebook'] ?? null,
            $socialLinks['twitter'] ?? null,
            $socialLinks['instagram'] ?? null,
            $socialLinks['youtube'] ?? null,
            $socialLinks['linkedin'] ?? null,
        ])->filter()->values()->toArray();
        $contactEmail = \App\Models\SiteSetting::getContactSettings()['email'] ?? 'support@tvetrevision.co.ke';

        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => 'TVET Revision',
            'description' => 'Access comprehensive TVET past exam questions, study materials, and prepare effectively for your KNEC exams.',
            'url' => config('app.url'),
            'logo' => asset('images/logo.svg'),
            'sameAs' => $sameAsArray,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => $contactEmail
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @stack('structured_data')

    <!-- Google AdSense -->
    @php
        $adsEnabled = \App\Models\SiteSetting::adsEnabled();
        $adsClientId = \App\Models\SiteSetting::get('ads_client_id', '');
    @endphp
    @if($adsEnabled && $adsClientId)
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-{{ $adsClientId }}"
            crossorigin="anonymous"></script>
    @endif
</head>
<body class="bg-light">
    <!-- Impersonation Banner -->
    @auth
        @if(session('impersonating_from'))
        <div class="impersonation-banner bg-warning text-dark py-2 px-3 d-flex flex-wrap align-items-center justify-content-center gap-2 position-fixed top-0 start-0 end-0" style="z-index: 9999;">
            <i class="bi bi-person-badge-fill fs-5"></i>
            <span class="fw-medium">
                You are viewing the site as <strong>{{ auth()->user()->name }}</strong>
            </span>
            <form action="{{ route('stop-impersonating') }}" method="POST" class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-dark">
                    <i class="bi bi-box-arrow-left me-1"></i>Return to Admin
                </button>
            </form>
        </div>
        <style>.navbar { margin-top: 50px !important; }</style>
        @endif
    @endauth

    <!-- Navigation -->
    @include('partials.frontend.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.frontend.footer')

    <!-- Mobile Bottom Navigation -->
    @include('partials.frontend.mobile-nav')

    <!-- PWA Install Banner - Only for logged-in students who are not premium AND subscriptions are enabled -->
    @if($pwaEnabled && \App\Models\SiteSetting::subscriptionsEnabled() && auth()->check() && auth()->user()->isStudent() && !auth()->user()->isPremium())
    <div id="installBanner" class="install-banner">
        <div class="card border-0 shadow-lg">
            <button type="button" class="close-banner" onclick="dismissInstallBanner()" aria-label="Close">
                <i class="bi bi-x"></i>
            </button>
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-warning bg-gradient rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-star-fill text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Go Premium for Offline Access</h6>
                        <p class="mb-0 small text-muted">Subscribe to download the app & study offline!</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('learn.subscription') }}" class="btn btn-warning w-100">
                        <i class="bi bi-star-fill me-2"></i>Get Premium
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- KaTeX JS for Math Formulas -->
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Render KaTeX formulas in content areas
            document.querySelectorAll('.question-content, .answer-content, .ql-editor').forEach(function(element) {
                renderMathInElement(element, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false},
                        {left: '\\[', right: '\\]', display: true}
                    ],
                    throwOnError: false,
                    trust: true
                });
            });

            // Also render any .katex elements that Quill may have created
            document.querySelectorAll('.ql-formula').forEach(function(element) {
                try {
                    const formula = element.getAttribute('data-value') || element.textContent;
                    if (formula) {
                        katex.render(formula, element, { throwOnError: false });
                    }
                } catch (e) {
                    console.warn('KaTeX render error:', e);
                }
            });
        });
    </script>

    <!-- Dark Mode Toggle Script -->
    @if($darkModeEnabled)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (!themeToggle) return;

            themeToggle.addEventListener('click', function() {
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-bs-theme') || 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });

            // Listen for system theme changes if using system preference
            const defaultTheme = '{{ $defaultTheme }}';
            if (defaultTheme === 'system') {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (!localStorage.getItem('theme')) {
                        document.documentElement.setAttribute('data-bs-theme', e.matches ? 'dark' : 'light');
                    }
                });
            }
        });
    </script>
    @endif

    <!-- PWA Service Worker Registration & Install Prompt -->
    @if($pwaEnabled)
    @php
        $pwaRequiresSubscription = \App\Models\SiteSetting::pwaRequiresSubscription();
        $userIsPremium = auth()->check() && auth()->user()->isPremium();
        $canInstall = !$pwaRequiresSubscription || $userIsPremium;
    @endphp
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('PWA: Service Worker registered with scope:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('PWA: Service Worker registration failed:', error);
                    });
            });
        }

        // PWA Install Prompt Handler
        let deferredPrompt;
        const canInstall = {{ $canInstall ? 'true' : 'false' }};
        const requiresSubscription = {{ $pwaRequiresSubscription ? 'true' : 'false' }};

        window.addEventListener('beforeinstallprompt', function(e) {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later
            deferredPrompt = e;
            // Show install UI
            showInstallUI();
        });

        function showInstallUI() {
            // Install menu item is now always visible in dropdown
            // This function now just shows the install banner and card

            // Show floating install banner for non-premium students (if not dismissed recently)
            const dismissedTime = localStorage.getItem('pwaInstallDismissed');
            const showBanner = !dismissedTime || (Date.now() - parseInt(dismissedTime)) > 86400000; // 24 hours

            if (showBanner) {
                const installBanner = document.getElementById('installBanner');
                if (installBanner) {
                    setTimeout(function() {
                        installBanner.classList.add('show');
                    }, 3000); // Show after 3 seconds
                }
            }
        }

        function installApp() {
            if (!canInstall) {
                // Redirect to subscription page
                window.location.href = '{{ route("learn.subscription") }}';
                return;
            }

            if (!deferredPrompt) {
                // Already installed or not supported
                Swal.fire({
                    icon: 'info',
                    title: 'App Installation',
                    text: 'The app may already be installed or your browser doesn\'t support installation. Try adding to home screen from your browser menu.',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }

            // Show the install prompt
            deferredPrompt.prompt();

            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then(function(choiceResult) {
                if (choiceResult.outcome === 'accepted') {
                    console.log('PWA: User accepted the install prompt');
                    hideInstallUI();
                } else {
                    console.log('PWA: User dismissed the install prompt');
                }
                deferredPrompt = null;
            });
        }

        function dismissInstallBanner() {
            const installBanner = document.getElementById('installBanner');
            if (installBanner) {
                installBanner.classList.remove('show');
                localStorage.setItem('pwaInstallDismissed', Date.now().toString());
            }
        }

        function hideInstallUI() {
            // Hide install banner (menu item stays visible for future reference)
            const installBanner = document.getElementById('installBanner');
            if (installBanner) {
                installBanner.classList.remove('show');
            }
            // Hide install card on dashboard
            const installCard = document.getElementById('installAppCard');
            if (installCard) {
                installCard.style.display = 'none';
            }
        }

        // Listen for successful install
        window.addEventListener('appinstalled', function(evt) {
            console.log('PWA: App was installed');
            hideInstallUI();
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
