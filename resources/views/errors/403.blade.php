<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden | {{ config('app.name') }}</title>
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
            padding: 1rem;
        }

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

        .shape:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 120px; height: 120px; top: 70%; left: 80%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 100px; height: 100px; top: 40%; left: 5%; animation-delay: 4s; }

        @keyframes float-shapes {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 0.6; }
        }

        .error-container {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            animation: slideUp 0.6s ease-out;
            position: relative;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #0b5ed7;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 900;
            color: #0b5ed7;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .error-icon {
            font-size: 2.5rem;
            color: #0b5ed7;
            margin-bottom: 0.5rem;
            animation: shake 2s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 0.75rem;
        }

        .error-description {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.5;
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
            background: #0b5ed7;
            color: white;
            box-shadow: 0 2px 10px rgba(11, 94, 215, 0.3);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(11, 94, 215, 0.5);
            background: #0a4db5;
            color: white;
        }

        @media (max-width: 767px) {
            .error-code { font-size: 4rem; }
            .error-icon { font-size: 2rem; }
            .error-title { font-size: 1.25rem; }
            .error-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="error-container">
        <div class="error-card">
            <div class="card-decoration"></div>

            <div class="error-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <div class="error-code">403</div>

            <h1 class="error-title">Access Forbidden</h1>

            <p class="error-description">
                {{ $exception->getMessage() ?: 'You don\'t have permission to access this resource.' }}
            </p>

            <a href="{{ url('/') }}" class="btn-custom">
                <i class="bi bi-house-door-fill"></i>
                Go Home
            </a>
        </div>
    </div>
</body>
</html>
