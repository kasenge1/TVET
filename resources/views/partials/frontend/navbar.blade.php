@php
    $siteLogo = \App\Models\SiteSetting::getLogo();
    $siteName = \App\Models\SiteSetting::get('site_logo_alt', '') ?: config('app.name', 'TVET Revision');
    $darkModeEnabled = \App\Models\SiteSetting::darkModeEnabled();
    $pwaEnabled = \App\Models\SiteSetting::pwaEnabled();
    $pwaRequiresSubscription = \App\Models\SiteSetting::pwaRequiresSubscription();
    $subscriptionsEnabled = \App\Models\SiteSetting::subscriptionsEnabled();
    $userIsPremium = auth()->check() && auth()->user()->isPremium();
    $canInstallPwa = !$pwaRequiresSubscription || $userIsPremium;
    $showSubscriptionLink = $subscriptionsEnabled || $userIsPremium;
@endphp

<!-- Off-canvas overlay -->
<div class="fe-offcanvas-overlay" id="offcanvasOverlay"></div>

<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ auth()->check() && auth()->user()->isStudent() ? route('learn.index') : route('home') }}">
            @if($siteLogo)
                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="img-fluid" style="max-height: 40px; max-width: 180px;">
            @else
                <span class="text-logo d-inline-flex align-items-center">
                    <span class="logo-icon me-2">
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>
                    <span class="logo-text">{{ $siteName }}</span>
                </span>
            @endif
        </a>

        <!-- Desktop nav -->
        <div class="d-none d-lg-flex align-items-center gap-4">
            <ul class="navbar-nav flex-row gap-1">
                @auth
                    @if(auth()->user()->isStudent())
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('learn.index') ? 'text-primary' : 'text-dark' }}" href="{{ route('learn.index') }}">
                                <i class="bi bi-book me-1"></i>My Course
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('learn.search') ? 'text-primary' : 'text-dark' }}" href="{{ route('learn.search') }}">
                                <i class="bi bi-search me-1"></i>Search
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('learn.saved') ? 'text-primary' : 'text-dark' }}" href="{{ route('learn.saved') }}">
                                <i class="bi bi-bookmark me-1"></i>Saved
                            </a>
                        </li>
                        @if($showSubscriptionLink)
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('learn.subscription*') ? 'text-primary' : 'text-dark' }}" href="{{ route('learn.subscription') }}">
                                <i class="bi bi-credit-card me-1"></i>Premium
                            </a>
                        </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('home') ? 'text-primary' : 'text-dark' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('courses.*') ? 'text-primary' : 'text-dark' }}" href="{{ route('courses.index') }}">Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('blog.*') ? 'text-primary' : 'text-dark' }}" href="{{ route('blog.index') }}">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('about') ? 'text-primary' : 'text-dark' }}" href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium px-3 {{ request()->routeIs('contact') ? 'text-primary' : 'text-dark' }}" href="{{ route('contact') }}">Contact</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3 {{ request()->routeIs('home') ? 'text-primary' : 'text-dark' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3 {{ request()->routeIs('courses.*') ? 'text-primary' : 'text-dark' }}" href="{{ route('courses.index') }}">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3 {{ request()->routeIs('blog.*') ? 'text-primary' : 'text-dark' }}" href="{{ route('blog.index') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3 {{ request()->routeIs('about') ? 'text-primary' : 'text-dark' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3 {{ request()->routeIs('contact') ? 'text-primary' : 'text-dark' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                @endauth
            </ul>

            <div class="d-flex gap-2 align-items-center">
                @if($darkModeEnabled)
                <button type="button" class="theme-toggle" id="themeToggle" title="Toggle dark mode">
                    <i class="bi bi-moon fs-5"></i>
                    <i class="bi bi-sun fs-5"></i>
                </button>
                @endif

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-speedometer2 me-1"></i>Admin Panel
                        </a>
                    @else
                        @if(auth()->user()->isPremium())
                            <span class="badge bg-warning text-dark me-1">
                                <i class="bi bi-star-fill"></i>
                                <span class="d-none d-sm-inline ms-1">Premium</span>
                            </span>
                        @endif

                        <x-notification-bell :route="route('learn.notifications')" prefix="learn" />

                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>{{ Str::limit(auth()->user()->name, 15) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('learn.index') }}"><i class="bi bi-book me-2"></i>My Course</a></li>
                                <li><a class="dropdown-item" href="{{ route('learn.saved') }}"><i class="bi bi-bookmark me-2"></i>Saved Questions</a></li>
                                @if($showSubscriptionLink)
                                <li><a class="dropdown-item" href="{{ route('learn.subscription') }}"><i class="bi bi-credit-card me-2"></i>Subscription</a></li>
                                @endif
                                @if($pwaEnabled)
                                <li id="installAppMenuItem">
                                    @if($canInstallPwa)
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="installApp()">
                                        <i class="bi bi-download me-2"></i>Install App
                                    </a>
                                    @else
                                    <a class="dropdown-item" href="{{ route('learn.subscription') }}">
                                        <i class="bi bi-download me-2"></i>Install App <span class="badge bg-warning text-dark ms-1">Premium</span>
                                    </a>
                                    @endif
                                </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('learn.settings') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                @endauth
            </div>
        </div>

        <!-- Mobile hamburger button -->
        <button class="fe-hamburger d-lg-none" type="button" id="offcanvasToggle" aria-label="Menu">
            <span class="fe-hamburger-line"></span>
            <span class="fe-hamburger-line"></span>
            <span class="fe-hamburger-line"></span>
        </button>
    </div>
</nav>

<!-- Off-canvas mobile menu -->
<div class="fe-offcanvas" id="offcanvasMenu">
    <div class="fe-offcanvas-header">
        <button class="fe-offcanvas-close" id="offcanvasClose" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="fe-offcanvas-body">
        <ul class="fe-offcanvas-nav">
            @auth
                @if(auth()->user()->isStudent())
                    <li>
                        <a href="{{ route('learn.index') }}" class="fe-offcanvas-link {{ request()->routeIs('learn.index') ? 'active' : '' }}">
                            <i class="bi bi-book"></i>My Course
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('learn.search') }}" class="fe-offcanvas-link {{ request()->routeIs('learn.search') ? 'active' : '' }}">
                            <i class="bi bi-search"></i>Search
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('learn.saved') }}" class="fe-offcanvas-link {{ request()->routeIs('learn.saved') ? 'active' : '' }}">
                            <i class="bi bi-bookmark"></i>Saved
                        </a>
                    </li>
                    @if($showSubscriptionLink)
                    <li>
                        <a href="{{ route('learn.subscription') }}" class="fe-offcanvas-link {{ request()->routeIs('learn.subscription*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card"></i>Premium
                        </a>
                    </li>
                    @endif
                    <li class="fe-offcanvas-divider"></li>
                    <li>
                        <a href="{{ route('learn.settings') }}" class="fe-offcanvas-link {{ request()->routeIs('learn.settings') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i>Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('learn.notifications') }}" class="fe-offcanvas-link {{ request()->routeIs('learn.notifications') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i>Notifications
                        </a>
                    </li>
                    <li class="fe-offcanvas-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="fe-offcanvas-link text-danger w-100" style="background: none; border: none;">
                                <i class="bi bi-box-arrow-right"></i>Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('home') }}" class="fe-offcanvas-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="bi bi-house"></i>Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="fe-offcanvas-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                            <i class="bi bi-collection"></i>Courses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}" class="fe-offcanvas-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                            <i class="bi bi-newspaper"></i>Blog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="fe-offcanvas-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            <i class="bi bi-info-circle"></i>About
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="fe-offcanvas-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                            <i class="bi bi-envelope"></i>Contact
                        </a>
                    </li>
                    <li class="fe-offcanvas-divider"></li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="fe-offcanvas-link">
                            <i class="bi bi-speedometer2"></i>Admin Panel
                        </a>
                    </li>
                @endif
            @else
                <li>
                    <a href="{{ route('home') }}" class="fe-offcanvas-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('courses.index') }}" class="fe-offcanvas-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>Courses
                    </a>
                </li>
                <li>
                    <a href="{{ route('blog.index') }}" class="fe-offcanvas-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                        <i class="bi bi-newspaper"></i>Blog
                    </a>
                </li>
                <li>
                    <a href="{{ route('about') }}" class="fe-offcanvas-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>About
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="fe-offcanvas-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="bi bi-envelope"></i>Contact
                    </a>
                </li>
            @endauth
        </ul>

        @guest
        <div class="fe-offcanvas-footer">
            <a href="{{ route('login') }}" class="fe-btn fe-btn-primary w-100 mb-2">Sign In</a>
            <a href="{{ route('register') }}" class="fe-btn w-100" style="background: var(--fe-bg); color: var(--fe-primary); border: 1.5px solid var(--fe-primary);">Get Started</a>
        </div>
        @endguest
    </div>
</div>

<style>
    /* Hamburger button */
    .fe-hamburger {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        width: 36px;
        height: 36px;
        padding: 6px;
        background: none;
        border: none;
        cursor: pointer;
        z-index: 1050;
    }

    .fe-hamburger-line {
        display: block;
        width: 22px;
        height: 2px;
        background: #1a1a2e;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .fe-hamburger.active .fe-hamburger-line:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .fe-hamburger.active .fe-hamburger-line:nth-child(2) {
        opacity: 0;
    }

    .fe-hamburger.active .fe-hamburger-line:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* Overlay */
    .fe-offcanvas-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(2px);
    }

    .fe-offcanvas-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Off-canvas panel */
    .fe-offcanvas {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 300px;
        max-width: 85vw;
        background: #fff;
        z-index: 1045;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
    }

    .fe-offcanvas.active {
        transform: translateX(0);
    }

    .fe-offcanvas-header {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .fe-offcanvas-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fe-offcanvas-close:hover {
        background: #e2e8f0;
        color: #1a1a2e;
    }

    .fe-offcanvas-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
    }

    .fe-offcanvas-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .fe-offcanvas-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .fe-offcanvas-link:hover {
        background: #f8fafc;
        color: #2563eb;
    }

    .fe-offcanvas-link.active {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
    }

    .fe-offcanvas-link i {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }

    .fe-offcanvas-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 0.5rem 1.25rem;
    }

    .fe-offcanvas-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
    }

    /* Prevent body scroll when menu is open */
    body.fe-offcanvas-open {
        overflow: hidden;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('offcanvasToggle');
    const menu = document.getElementById('offcanvasMenu');
    const overlay = document.getElementById('offcanvasOverlay');
    const closeBtn = document.getElementById('offcanvasClose');

    function openMenu() {
        toggle.classList.add('active');
        menu.classList.add('active');
        overlay.classList.add('active');
        document.body.classList.add('fe-offcanvas-open');
    }

    function closeMenu() {
        toggle.classList.remove('active');
        menu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('fe-offcanvas-open');
    }

    toggle.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMenu();
    });
});
</script>
