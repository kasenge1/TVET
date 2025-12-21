<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0b5ed7 0%, #0a4db5 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow: hidden;
            position: relative;
            padding: 1rem;
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
            background: rgba(255, 255, 255, 0.1);
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

        .error-container {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            animation: slideUp 0.6s ease-out;
            position: relative;
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

        .card-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0b5ed7 0%, #0a4db5 50%, #0b5ed7 100%);
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

        .error-code {
            font-size: 5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #0b5ed7 0%, #0a4db5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .error-icon {
            font-size: 2.5rem;
            color: #0b5ed7;
            margin-bottom: 0.5rem;
            animation: float 3s ease-in-out infinite;
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

        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .error-description {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .button-group {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            padding: 0.625rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: #0b5ed7;
            color: white;
            box-shadow: 0 2px 10px rgba(11, 94, 215, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(11, 94, 215, 0.5);
            background: #0a4db5;
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            color: #0b5ed7;
            border: 2px solid #0b5ed7;
        }

        .btn-secondary-custom:hover {
            background: #0b5ed7;
            color: white;
            transform: translateY(-2px);
        }

        .suggestions {
            text-align: left;
            background: rgba(11, 94, 215, 0.05);
            padding: 1rem;
            border-radius: 12px;
            border-left: 3px solid #0b5ed7;
        }

        .suggestions h3 {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .suggestions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .suggestions li {
            padding: 0.35rem 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
        }

        .suggestions li i {
            color: #0b5ed7;
            font-size: 0.9rem;
        }

        .suggestions a {
            color: #0b5ed7;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .suggestions a:hover {
            color: #0a4db5;
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            .error-code {
                font-size: 4rem;
            }

            .error-icon {
                font-size: 2rem;
            }

            .error-title {
                font-size: 1.25rem;
            }

            .error-description {
                font-size: 0.875rem;
            }

            .error-card {
                padding: 1.5rem 1rem;
            }

            .btn-custom {
                font-size: 0.85rem;
                padding: 0.5rem 1.25rem;
            }

            .suggestions h3 {
                font-size: 0.875rem;
            }

            .suggestions li {
                font-size: 0.8rem;
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

    <div class="error-container">
        <div class="error-card">
            <div class="card-decoration"></div>

            <div class="error-icon">
                <i class="bi bi-compass"></i>
            </div>

            <div class="error-code">404</div>

            <h1 class="error-title">Oops! Page Not Found</h1>

            <p class="error-description">
                The page you're looking for seems to have wandered off. Don't worry, it happens to the best of us!
            </p>

            <div class="button-group">
                <a href="{{ url('/') }}" class="btn-custom btn-primary-custom">
                    <i class="bi bi-house-door-fill"></i>
                    Go Home
                </a>
                <button onclick="history.back()" class="btn-custom btn-secondary-custom">
                    <i class="bi bi-arrow-left"></i>
                    Go Back
                </button>
            </div>

            <div class="suggestions">
                <h3>
                    <i class="bi bi-lightbulb-fill"></i>
                    Here are some helpful links:
                </h3>
                <ul>
                    <li>
                        <i class="bi bi-arrow-right-circle-fill"></i>
                        <a href="{{ url('/') }}">Homepage</a>
                    </li>
                    @auth
                        @if(Auth::user()->hasRole('admin'))
                            <li>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                            <li>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                                <a href="{{ route('admin.questions.index') }}">Questions</a>
                            </li>
                        @else
                            <li>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                                <a href="{{ route('learn.courses') }}">My Courses</a>
                            </li>
                        @endif
                    @else
                        <li>
                            <i class="bi bi-arrow-right-circle-fill"></i>
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                        <li>
                            <i class="bi bi-arrow-right-circle-fill"></i>
                            <a href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
