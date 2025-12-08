<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom sticky-top">
    <div class="container-fluid">
        <!-- Mobile menu toggle - triggers offcanvas on mobile, toggles sidebar on desktop -->
        <button class="btn btn-link d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
            <i class="bi bi-list fs-4"></i>
        </button>
        <button class="btn btn-link d-none d-lg-block" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Course Info - Hidden on mobile -->
        @if(Auth::user()->enrollment)
            <div class="d-none d-md-block">
                <span class="badge bg-primary">{{ Auth::user()->course->title ?? 'No Course' }}</span>
            </div>
        @endif

        <div class="ms-auto d-flex align-items-center">
            <!-- Premium Badge -->
            @if(Auth::user()->isPremium())
                <span class="badge bg-warning text-dark me-2 me-md-3">
                    <i class="bi bi-star-fill"></i>
                    <span class="d-none d-sm-inline">Premium</span>
                </span>
            @endif

            <!-- Notifications -->
            <x-notification-bell :route="route('student.notifications.index')" />

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none d-flex align-items-center p-0" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                         class="rounded-circle" width="32" height="32" alt="Profile">
                    <span class="d-none d-md-inline ms-2">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down ms-1 d-none d-md-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li class="dropdown-header d-md-none">
                        <strong>{{ Auth::user()->name }}</strong><br>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </li>
                    <li class="d-md-none"><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('student.profile') }}">
                        <i class="bi bi-person me-2"></i> My Profile
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('student.subscription') }}">
                        <i class="bi bi-star me-2"></i>
                        @if(Auth::user()->isPremium())
                            Manage Subscription
                        @else
                            Upgrade to Premium
                        @endif
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('home') }}">
                        <i class="bi bi-house me-2"></i> Home
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
