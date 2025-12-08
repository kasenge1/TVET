@php
    $siteLogo = \App\Models\SiteSetting::getLogo();
    $siteName = \App\Models\SiteSetting::get('site_logo_alt', '') ?: config('app.name', 'TVET Revision');
    $darkModeEnabled = \App\Models\SiteSetting::darkModeEnabled();
    $pwaEnabled = \App\Models\SiteSetting::pwaEnabled();
    $pwaRequiresSubscription = \App\Models\SiteSetting::pwaRequiresSubscription();
    $subscriptionsEnabled = \App\Models\SiteSetting::subscriptionsEnabled();
    $userIsPremium = auth()->check() && auth()->user()->isPremium();
    $canInstallPwa = !$pwaRequiresSubscription || $userIsPremium;
    // Show subscription link only if subscriptions enabled OR user is already premium
    $showSubscriptionLink = $subscriptionsEnabled || $userIsPremium;
@endphp
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
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
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                @auth
                    @if(auth()->user()->isStudent())
                        {{-- Student Navigation Menu --}}
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
                        {{-- Admin sees normal menu --}}
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
                    {{-- Guest Navigation Menu --}}
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
                <!-- Dark Mode Toggle -->
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
                        <!-- Premium Badge -->
                        @if(auth()->user()->isPremium())
                            <span class="badge bg-warning text-dark me-1">
                                <i class="bi bi-star-fill"></i>
                                <span class="d-none d-sm-inline ms-1">Premium</span>
                            </span>
                        @endif

                        <!-- Notification Bell -->
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
    </div>
</nav>
