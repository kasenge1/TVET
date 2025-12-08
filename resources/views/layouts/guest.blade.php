<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'TVET Revision'))</title>

    <!-- Favicon -->
    @php
        $favicon = \App\Models\SiteSetting::getFavicon();
    @endphp
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset($favicon) }}">
        <link rel="shortcut icon" href="{{ asset($favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset($favicon) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
        }

        /* Left side - Branding */
        .auth-branding {
            flex: 1;
            background: var(--primary-gradient);
            position: relative;
            display: none;
            padding: 3rem;
            overflow: hidden;
        }

        @media (min-width: 992px) {
            .auth-branding {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }

        .auth-branding::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .auth-branding-content {
            position: relative;
            z-index: 1;
            color: white;
            max-width: 480px;
        }

        .auth-branding h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .auth-branding > p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.7;
        }

        .feature-list {
            margin-top: 2.5rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .feature-text p {
            font-size: 0.875rem;
            opacity: 0.8;
            margin: 0;
        }

        /* Decorative circles */
        .auth-branding .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -50px;
            left: -50px;
        }

        /* Right side - Form */
        .auth-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #f8fafc;
            overflow-y: auto;
        }

        @media (min-width: 992px) {
            .auth-form-section {
                max-width: 600px;
            }
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 480px;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
        }

        @media (max-width: 767.98px) {
            .auth-form-section {
                padding: 1.25rem;
            }

            .auth-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .auth-header h2 {
                font-size: 1.35rem;
            }

            .auth-header p {
                font-size: 0.85rem;
            }

            .form-control, .form-select {
                padding: 0.75rem 0.875rem;
                font-size: 0.875rem;
            }

            .input-with-icon .form-control {
                padding-left: 2.5rem;
            }

            .input-with-icon .input-icon {
                left: 0.875rem;
                font-size: 1rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            .btn {
                padding: 0.75rem 1.25rem;
                font-size: 0.875rem;
            }

            .auth-logo i {
                font-size: 2rem;
            }

            .auth-logo span {
                font-size: 1.25rem;
            }

            .auth-footer {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 575.98px) {
            .auth-form-section {
                padding: 1rem;
            }

            .auth-card {
                padding: 1.25rem;
                border-radius: 14px;
            }

            .auth-header h2 {
                font-size: 1.2rem;
            }

            .auth-header p {
                font-size: 0.8rem;
            }

            .form-control, .form-select {
                padding: 0.625rem 0.75rem;
                font-size: 0.8rem;
                border-radius: 10px;
            }

            .form-label {
                font-size: 0.75rem;
            }

            .btn {
                padding: 0.625rem 1rem;
                font-size: 0.8rem;
                border-radius: 10px;
            }

            .auth-logo {
                margin-bottom: 1.5rem;
            }

            .auth-logo i {
                font-size: 1.75rem;
            }

            .auth-logo span {
                font-size: 1.1rem;
            }

            .auth-header {
                margin-bottom: 1.5rem;
            }

            .warning-box {
                padding: 0.75rem;
                font-size: 0.75rem;
            }

            .warning-box i {
                font-size: 1rem;
            }

            .warning-box p {
                font-size: 0.75rem;
            }

            .mb-4 {
                margin-bottom: 0.875rem !important;
            }

            .mb-3 {
                margin-bottom: 0.625rem !important;
            }
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .auth-logo i {
            font-size: 2.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-logo span {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #64748b;
            margin: 0;
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            padding: 0.875rem 1rem;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #f9fafb;
        }

        .form-control:hover, .form-select:hover {
            border-color: #d1d5db;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus, .form-select.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .form-control {
            padding-left: 3rem;
        }

        .input-with-icon .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            z-index: 4;
        }

        .btn {
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 14px 0 rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(102, 126, 234, 0.5);
            background: var(--primary-gradient);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
        }

        .auth-footer a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .mobile-header {
            display: block;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 992px) {
            .mobile-header {
                display: none;
            }
        }

        .back-to-home {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            opacity: 0.9;
            transition: opacity 0.2s;
            z-index: 10;
        }

        .back-to-home:hover {
            color: white;
            opacity: 1;
        }

        @media (max-width: 991px) {
            .back-to-home {
                color: #667eea;
                position: static;
            }
            .back-to-home:hover {
                color: #764ba2;
            }
        }

        /* Warning box */
        .warning-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .warning-box i {
            color: #d97706;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .warning-box p {
            margin: 0;
            font-size: 0.8125rem;
            color: #92400e;
            line-height: 1.5;
        }

        /* Course info box */
        .course-info-box {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Left Branding Section -->
        <div class="auth-branding">
            <a href="{{ route('home') }}" class="back-to-home">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>

            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>

            <div class="auth-branding-content">
                <h1>Prepare Smarter for Your TVET Exams</h1>
                <p>Access thousands of past exam questions with detailed answers. Study efficiently and ace your KNEC examinations.</p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Comprehensive Question Bank</h4>
                            <p>Access past papers organized by course and unit</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Detailed Explanations</h4>
                            <p>Every question comes with a clear, easy-to-understand answer</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Study Anywhere</h4>
                            <p>Mobile-friendly design for learning on the go</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="auth-form-section">
            <div class="auth-form-wrapper">
                <div class="mobile-header">
                    <a href="{{ route('home') }}" class="back-to-home">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>

                <div class="auth-card">
                    <div class="auth-logo">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>TVET Revision</span>
                    </div>

                    @yield('main')
                </div>

                <p class="text-center mt-4 small text-muted">
                    &copy; {{ date('Y') }} TVET Revision. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
