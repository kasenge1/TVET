@auth
    @if(auth()->user()->isStudent())
        @php
            $subscriptionsEnabled = \App\Models\SiteSetting::subscriptionsEnabled();
            $userIsPremium = auth()->user()->isPremium();
            $showPremiumNav = $subscriptionsEnabled || $userIsPremium;
        @endphp
        <!-- Mobile Bottom Navigation -->
        <nav class="mobile-bottom-nav d-lg-none">
            <a href="{{ route('learn.index') }}" class="mobile-nav-item {{ request()->routeIs('learn.index') ? 'active' : '' }}">
                <i class="bi bi-book{{ request()->routeIs('learn.index') ? '-fill' : '' }}"></i>
                <span>Course</span>
            </a>
            <a href="{{ route('learn.search') }}" class="mobile-nav-item {{ request()->routeIs('learn.search') ? 'active' : '' }}">
                <i class="bi bi-search"></i>
                <span>Search</span>
            </a>
            <a href="{{ route('learn.saved') }}" class="mobile-nav-item {{ request()->routeIs('learn.saved') ? 'active' : '' }}">
                <i class="bi bi-bookmark{{ request()->routeIs('learn.saved') ? '-fill' : '' }}"></i>
                <span>Saved</span>
            </a>
            @if($showPremiumNav)
            <a href="{{ route('learn.subscription') }}" class="mobile-nav-item {{ request()->routeIs('learn.subscription*') ? 'active' : '' }}">
                <i class="bi bi-star{{ request()->routeIs('learn.subscription*') ? '-fill' : '' }}"></i>
                <span>Premium</span>
            </a>
            @endif
            <a href="{{ route('learn.settings') }}" class="mobile-nav-item {{ request()->routeIs('learn.settings') ? 'active' : '' }}">
                <i class="bi bi-gear{{ request()->routeIs('learn.settings') ? '-fill' : '' }}"></i>
                <span>Account</span>
            </a>
        </nav>

        <style>
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                display: flex;
                justify-content: space-around;
                align-items: center;
                padding: 0.5rem 0;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1030;
                border-top: 1px solid #e9ecef;
            }

            .mobile-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 0.25rem 0.5rem;
                color: #6c757d;
                text-decoration: none;
                font-size: 0.65rem;
                transition: color 0.2s ease;
                min-width: 60px;
            }

            .mobile-nav-item i {
                font-size: 1.25rem;
                margin-bottom: 0.15rem;
            }

            .mobile-nav-item.active {
                color: #0d6efd;
            }

            .mobile-nav-item:hover {
                color: #0d6efd;
            }

            /* Add padding to body to prevent content from being hidden behind nav */
            @media (max-width: 991.98px) {
                body {
                    padding-bottom: 70px;
                }
            }
        </style>
    @endif
@endauth
