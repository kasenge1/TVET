@extends('layouts.base')

@section('content')
    <div class="d-flex" id="wrapper">
        <!-- Desktop Sidebar - Hidden on mobile -->
        <div class="d-none d-lg-block" id="sidebar-desktop">
            @include('partials.student.sidebar')
        </div>

        <!-- Mobile Sidebar Offcanvas -->
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" style="width: 280px;">
            <div class="offcanvas-header border-bottom border-white border-opacity-25">
                <h5 class="offcanvas-title text-white">
                    <i class="bi bi-book-fill me-2"></i> My Learning
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                @include('partials.student.sidebar-menu')
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-fill min-vh-100">
            <!-- Top Navbar -->
            @include('partials.student.navbar')

            <!-- Main Content -->
            <div class="container-fluid p-3 p-md-4">
                <!-- Header Ad (for free users) -->
                <x-google-ad slot="header" />

                <!-- Subscription Notice (only show to non-premium users) -->
                @if(!Auth::user()->isPremium())
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Free Account</strong>
                                    <span class="d-none d-sm-inline"> - Upgrade to Premium to remove ads and get unlimited access!</span>
                                </div>
                            </div>
                            <a href="{{ route('student.subscription') }}" class="btn btn-sm btn-primary ms-sm-auto">Upgrade Now</a>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Heading -->
                @hasSection('page-header')
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
                        <h2 class="mb-0 fs-4 fs-md-3">@yield('page-title')</h2>
                        <div class="d-flex gap-2 flex-wrap">
                            @yield('page-actions')
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('main')

                <!-- Footer Ad (for free users) -->
                <x-google-ad slot="content" class="mt-4" />
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav d-lg-none">
        <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('student.questions.index') }}" class="nav-link {{ request()->routeIs('student.questions.*') ? 'active' : '' }}">
            <i class="bi bi-question-circle-fill"></i>
            <span>Questions</span>
        </a>
        <a href="{{ route('student.bookmarks') }}" class="nav-link {{ request()->routeIs('student.bookmarks') ? 'active' : '' }}">
            <i class="bi bi-bookmark-fill"></i>
            <span>Bookmarks</span>
        </a>
        <a href="{{ route('student.search') }}" class="nav-link {{ request()->routeIs('student.search') ? 'active' : '' }}">
            <i class="bi bi-search"></i>
            <span>Search</span>
        </a>
        <a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            <span>Profile</span>
        </a>
    </nav>

    <!-- SweetAlert2 Notifications -->
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    @if(session('error'))
        <x-alert type="danger" :message="session('error')" />
    @endif
    @if(session('warning'))
        <x-alert type="warning" :message="session('warning')" />
    @endif
    @if(session('info'))
        <x-alert type="info" :message="session('info')" />
    @endif
    @if($errors->any())
        <x-alert type="danger" :message="$errors->first()" title="Validation Error" />
    @endif
@endsection

@push('styles')
<style>
    /* Global font size adjustments for student dashboard */
    #page-content-wrapper {
        font-size: 0.9rem;
    }

    #page-content-wrapper h1 { font-size: 1.5rem; }
    #page-content-wrapper h2 { font-size: 1.25rem; }
    #page-content-wrapper h3 { font-size: 1.1rem; }
    #page-content-wrapper h4 { font-size: 1rem; }
    #page-content-wrapper h5 { font-size: 0.95rem; }
    #page-content-wrapper h6 { font-size: 0.875rem; }

    #page-content-wrapper .form-control,
    #page-content-wrapper .form-select {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
    }

    #page-content-wrapper .form-label {
        font-size: 0.85rem;
    }

    #page-content-wrapper .btn {
        font-size: 0.85rem;
    }

    #page-content-wrapper .card-title {
        font-size: 0.95rem;
    }

    #wrapper {
        min-height: 100vh;
    }

    /* Use same dark theme as admin */
    #sidebar-desktop #sidebar-wrapper,
    #sidebar-wrapper {
        min-width: 250px;
        max-width: 250px;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        color: #fff;
        transition: all 0.3s;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    #page-content-wrapper {
        width: 100%;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    /* Mobile bottom navigation */
    .mobile-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1030;
        display: flex;
        justify-content: space-around;
        padding: 0.5rem 0;
        padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
    }

    .mobile-bottom-nav .nav-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.25rem 0.5rem;
        color: #6c757d;
        font-size: 0.65rem;
        text-decoration: none;
        transition: color 0.2s;
    }

    .mobile-bottom-nav .nav-link.active {
        color: #667eea;
    }

    .mobile-bottom-nav .nav-link i {
        font-size: 1.25rem;
        margin-bottom: 0.125rem;
    }

    /* Add padding at bottom for mobile nav */
    @media (max-width: 991.98px) {
        #page-content-wrapper {
            padding-bottom: 70px;
        }
    }

    /* Mobile responsive improvements */
    @media (max-width: 767.98px) {
        .btn-modern {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }

        .card-body {
            padding: 0.875rem;
        }

        h2, .fs-4 {
            font-size: 1.15rem !important;
        }

        h3, .fs-5 {
            font-size: 1rem !important;
        }

        h4 {
            font-size: 0.95rem !important;
        }

        h5 {
            font-size: 0.875rem !important;
        }

        /* Stack cards on mobile */
        .row > [class*="col-"] {
            margin-bottom: 0.75rem;
        }

        /* Container padding */
        .container-fluid.p-3 {
            padding: 0.75rem !important;
        }

        /* Reduce gaps */
        .gap-3 {
            gap: 0.5rem !important;
        }

        .gap-4 {
            gap: 0.75rem !important;
        }

        /* Margins */
        .mb-4 {
            margin-bottom: 0.875rem !important;
        }

        .mb-3 {
            margin-bottom: 0.625rem !important;
        }

        /* Alert adjustments */
        .alert {
            padding: 0.625rem 0.875rem;
            font-size: 0.8rem;
        }

        .alert .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Form controls */
        #page-content-wrapper .form-control,
        #page-content-wrapper .form-select {
            font-size: 0.8rem;
            padding: 0.35rem 0.625rem;
        }

        /* Badges */
        .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.45em;
        }

        /* Small text */
        .small, small, .text-muted.small {
            font-size: 0.7rem;
        }

        /* Table cells */
        .table td, .table th {
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        /* List group items */
        .list-group-item {
            padding: 0.625rem 0.875rem;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 575.98px) {
        h2, .fs-4 {
            font-size: 1.05rem !important;
        }

        .card-body {
            padding: 0.75rem;
        }

        .container-fluid.p-3 {
            padding: 0.625rem !important;
        }

        /* Buttons */
        .btn {
            padding: 0.35rem 0.625rem;
            font-size: 0.75rem;
        }

        .btn-lg {
            padding: 0.45rem 0.875rem;
            font-size: 0.8rem;
        }

        /* Icons in buttons */
        .btn i {
            font-size: 0.8rem;
        }
    }

    /* Improve touch targets */
    @media (max-width: 991.98px) {
        .list-group-item {
            padding: 1rem 1.25rem;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
        }
    }

    /* Offcanvas sidebar styles - match admin dark theme */
    .offcanvas.offcanvas-start {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    }

    .offcanvas .list-group-item {
        background: transparent;
        border-color: rgba(255,255,255,0.1);
        color: #fff;
    }

    .offcanvas .list-group-item:hover,
    .offcanvas .list-group-item.active {
        background-color: rgba(102, 126, 234, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Desktop sidebar toggle
        const desktopToggle = document.getElementById('sidebarToggle');
        const desktopSidebar = document.getElementById('sidebar-desktop');

        if (desktopToggle && desktopSidebar) {
            desktopToggle.addEventListener('click', function() {
                if (window.innerWidth >= 992) {
                    desktopSidebar.classList.toggle('d-none');
                    desktopSidebar.classList.toggle('d-lg-block');
                }
            });
        }
    });
</script>
@endpush
