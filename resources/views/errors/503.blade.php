<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
            padding: 2rem 1rem;
        }

        /* Animated Background Shapes */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            animation: float-shapes 20s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 40%;
            left: 5%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 85%;
            animation-delay: 1s;
        }

        .shape:nth-child(5) {
            width: 90px;
            height: 90px;
            top: 80%;
            left: 20%;
            animation-delay: 3s;
        }

        @keyframes float-shapes {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.6;
            }
        }

        .maintenance-container {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .maintenance-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 30px;
            padding: 2rem 2rem;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.3);
            color: #333;
            backdrop-filter: blur(20px);
            animation: slideUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Decorative top element */
        .card-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 50%, #4facfe 100%);
            background-size: 200% 100%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .maintenance-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: float 3s ease-in-out infinite, pulse 2s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .maintenance-subtitle {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #666;
            position: relative;
            display: inline-block;
            padding: 0.25rem 1rem;
        }

        .maintenance-subtitle::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 2px;
        }

        .maintenance-description {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: #555;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .progress-container {
            margin: 1.5rem 0;
            position: relative;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
            background: linear-gradient(90deg, #e0e0e0 0%, #f0f0f0 100%);
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            background-size: 200% 100%;
            animation: progress 2s ease-in-out infinite, shimmer 3s ease infinite;
            height: 100%;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shine 2s infinite;
        }

        @keyframes progress {
            0% {
                width: 30%;
            }
            50% {
                width: 75%;
            }
            100% {
                width: 30%;
            }
        }

        @keyframes shimmer {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            padding: 1.25rem 0.75rem;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.08) 0%, rgba(0, 242, 254, 0.08) 100%);
            border-radius: 16px;
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 16px;
            padding: 2px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.3;
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.25);
        }

        .info-item i {
            font-size: 2rem;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .info-item .info-label {
            font-size: 0.75rem;
            color: #888;
            margin-bottom: 0.35rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item .info-value {
            font-size: 1rem;
            font-weight: 700;
            color: #333;
        }

        .countdown {
            font-size: 0.9rem;
            color: #666;
            margin-top: 1.25rem;
            padding: 0.75rem 1rem;
            background: rgba(79, 172, 254, 0.08);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .countdown i {
            color: #4facfe;
        }

        .social-links {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1.15rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .social-links a:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 25px rgba(79, 172, 254, 0.5);
        }

        .contact-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid rgba(79, 172, 254, 0.15);
        }

        .contact-info a {
            color: #4facfe;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .contact-info a:hover {
            color: #00f2fe;
            text-decoration: underline;
        }

        /* Loading dots animation */
        .loading-dots {
            display: inline-flex;
            gap: 3px;
            margin-left: 4px;
        }

        .loading-dots span {
            width: 5px;
            height: 5px;
            background: #4facfe;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .loading-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loading-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% {
                transform: scale(1.2);
                opacity: 1;
            }
        }

        /* Tablet & Desktop Responsive */
        @media (min-width: 768px) {
            .maintenance-card {
                padding: 2.5rem 3rem;
            }

            .maintenance-icon {
                font-size: 6rem;
                margin-bottom: 1.25rem;
            }

            .maintenance-title {
                font-size: 3rem;
            }

            .maintenance-subtitle {
                font-size: 1.25rem;
            }

            .maintenance-description {
                font-size: 1rem;
            }

            .info-item i {
                font-size: 2.25rem;
            }

            .info-item .info-value {
                font-size: 1.1rem;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            body {
                padding: 1rem 0.5rem;
            }

            .maintenance-card {
                padding: 1.5rem 1.25rem;
                border-radius: 20px;
            }

            .maintenance-icon {
                font-size: 4rem;
            }

            .maintenance-title {
                font-size: 1.75rem;
            }

            .maintenance-subtitle {
                font-size: 1rem;
            }

            .maintenance-description {
                font-size: 0.9rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .info-item {
                padding: 1rem 0.75rem;
            }

            .info-item i {
                font-size: 1.75rem;
            }

            .info-item .info-label {
                font-size: 0.7rem;
            }

            .info-item .info-value {
                font-size: 0.95rem;
            }

            .social-links {
                gap: 0.6rem;
            }

            .social-links a {
                width: 42px;
                height: 42px;
                font-size: 1rem;
            }

            .countdown {
                font-size: 0.85rem;
                padding: 0.6rem 0.85rem;
            }

            .contact-info {
                font-size: 0.85rem;
            }
        }

        /* Small Desktop */
        @media (min-width: 992px) and (max-width: 1199px) {
            .maintenance-container {
                max-width: 850px;
            }
        }

        /* Large Desktop */
        @media (min-width: 1200px) {
            .maintenance-container {
                max-width: 900px;
            }

            .maintenance-card {
                padding: 3rem 3.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="maintenance-container">
        <div class="maintenance-card">
            <div class="card-decoration"></div>

            <div class="maintenance-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>

            @php
                $settings = \App\Models\MaintenanceSettings::getSettings();
                $supportEmail = $settings->support_email ?: 'support@' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'example.com');
            @endphp

            <h1 class="maintenance-title">{{ $settings->title }}</h1>

            <h2 class="maintenance-subtitle">{{ $settings->subtitle }}</h2>

            <p class="maintenance-description">
                {{ $settings->message }}
            </p>

            <div class="progress-container">
                <div class="progress">
                    <div class="progress-bar" role="progressbar"></div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <i class="bi bi-hourglass-split"></i>
                    <div class="info-label">Expected Time</div>
                    <div class="info-value">
                        {{ $settings->expected_duration }}
                        <div class="loading-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <i class="bi bi-clock-history"></i>
                    <div class="info-label">Started At</div>
                    <div class="info-value">{{ now()->format('h:i A') }}</div>
                </div>

                <div class="info-item">
                    <i class="bi bi-arrow-repeat"></i>
                    <div class="info-label">Current Status</div>
                    <div class="info-value">In Progress</div>
                </div>
            </div>

            <div class="countdown">
                <i class="bi bi-info-circle-fill"></i>
                <span>This page will automatically refresh every 60 seconds</span>
            </div>

            <div class="social-links">
                @if($settings->facebook_url)
                    <a href="{{ $settings->facebook_url }}" title="Facebook" aria-label="Facebook" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                @endif
                @if($settings->twitter_url)
                    <a href="{{ $settings->twitter_url }}" title="Twitter/X" aria-label="Twitter" target="_blank">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                @endif
                @if($settings->instagram_url)
                    <a href="{{ $settings->instagram_url }}" title="Instagram" aria-label="Instagram" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                @endif
                @if($settings->linkedin_url)
                    <a href="{{ $settings->linkedin_url }}" title="LinkedIn" aria-label="LinkedIn" target="_blank">
                        <i class="bi bi-linkedin"></i>
                    </a>
                @endif
                <a href="mailto:{{ $supportEmail }}" title="Email" aria-label="Email">
                    <i class="bi bi-envelope-fill"></i>
                </a>
            </div>

            <div class="contact-info">
                <p class="text-muted small mb-0">
                    <i class="bi bi-headset"></i> Need urgent assistance? Contact our support team at<br>
                    <a href="mailto:{{ $supportEmail }}">
                        {{ $supportEmail }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh every 60 seconds
        setTimeout(function() {
            location.reload();
        }, 60000);

        // Update countdown timer
        let seconds = 60;
        const countdownElement = document.querySelector('.countdown span');

        setInterval(function() {
            seconds--;
            if (seconds === 0) {
                seconds = 60;
            }
            if (countdownElement) {
                countdownElement.textContent = `Refreshing in ${seconds} second${seconds !== 1 ? 's' : ''}...`;
            }
        }, 1000);
    </script>
</body>
</html>
