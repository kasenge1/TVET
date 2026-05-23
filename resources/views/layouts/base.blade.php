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
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=comfortaa:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Quill CSS for rich text display -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <style>
        /* Rich text content styling */
        .ql-editor, .question-text, .answer-text, .explanation-content {
            font-size: 1rem;
            line-height: 1.7;
        }
        .ql-editor p, .question-text p, .answer-text p {
            margin-bottom: 0.75rem;
        }
        .ql-editor img, .question-text img, .answer-text img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>

    @stack('styles')

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
        <div style="height: 50px;"></div> <!-- Spacer for fixed banner -->
        @endif
    @endauth

    <div id="app">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
