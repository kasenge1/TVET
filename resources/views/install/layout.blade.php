<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Installation') - TVET Revision</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .install-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .install-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .install-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .install-header .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .install-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .install-body {
            padding: 2rem;
        }

        .steps {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 50px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .step.active {
            background: var(--primary-gradient);
            color: white;
        }

        .step.completed {
            background: #198754;
            color: white;
        }

        .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .step.active .step-number,
        .step.completed .step-number {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-install {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }

        .requirement-item.success {
            background: #d1e7dd;
        }

        .requirement-item.danger {
            background: #f8d7da;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 576px) {
            .steps {
                flex-direction: column;
                align-items: stretch;
            }

            .step {
                justify-content: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <div class="logo">
                    <i class="bi bi-mortarboard-fill fs-1"></i>
                </div>
                <h1>TVET Revision</h1>
                <p class="mb-0 opacity-75">Installation Wizard</p>
            </div>

            <div class="install-body">
                <!-- Steps Progress -->
                <div class="steps">
                    <div class="step @yield('step1-class', '')">
                        <span class="step-number">1</span>
                        <span class="d-none d-sm-inline">Welcome</span>
                    </div>
                    <div class="step @yield('step2-class', '')">
                        <span class="step-number">2</span>
                        <span class="d-none d-sm-inline">Requirements</span>
                    </div>
                    <div class="step @yield('step3-class', '')">
                        <span class="step-number">3</span>
                        <span class="d-none d-sm-inline">Database</span>
                    </div>
                    <div class="step @yield('step4-class', '')">
                        <span class="step-number">4</span>
                        <span class="d-none d-sm-inline">Application</span>
                    </div>
                    <div class="step @yield('step5-class', '')">
                        <span class="step-number">5</span>
                        <span class="d-none d-sm-inline">Admin</span>
                    </div>
                    <div class="step @yield('step6-class', '')">
                        <span class="step-number">6</span>
                        <span class="d-none d-sm-inline">Finish</span>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <p class="text-center text-muted mt-3 small">
            TVET Revision &copy; {{ date('Y') }} | Powered by Laravel
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
