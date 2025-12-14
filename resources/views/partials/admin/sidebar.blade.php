<div id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-3 border-bottom border-secondary">
        @php
            $siteLogo = \App\Models\SiteSetting::getLogo();
            $siteName = \App\Models\SiteSetting::get('site_logo_alt', '') ?: config('app.name', 'TVET Revision');
        @endphp
        @if($siteLogo)
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="img-fluid" style="max-height: 40px; max-width: 180px;">
            </a>
        @else
            <h5 class="mb-0 text-white fw-bold">
                <i class="bi bi-shield-check me-2"></i>{{ $siteName }}
            </h5>
        @endif
    </div>
    <div class="p-2">
        {{-- Dashboard - visible to all admin users --}}
        @can('view dashboard')
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        @endcan

        {{-- CONTENT SECTION --}}
        @canany(['view courses', 'view units', 'view questions', 'view levels', 'view blog'])
        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">CONTENT</div>
        @endcanany

        @can('view courses')
        <a href="{{ route('admin.courses.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            <i class="bi bi-book me-2"></i> Courses
        </a>
        @endcan

        @can('view levels')
        <a href="{{ route('admin.levels.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">
            <i class="bi bi-layers me-2"></i> Levels
        </a>
        @endcan

        @can('view units')
        <a href="{{ route('admin.units.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
            <i class="bi bi-collection me-2"></i> Units
        </a>
        @endcan

        @can('view questions')
        <a href="{{ route('admin.questions.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
            <i class="bi bi-question-circle me-2"></i> Questions
        </a>
        @endcan

        @can('view blog')
        <a href="{{ route('admin.blog.posts.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper me-2"></i> Blog
        </a>
        @endcan

        {{-- USERS SECTION --}}
        @canany(['view users', 'view roles', 'view subscriptions'])
        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">USERS</div>
        @endcanany

        @can('view users')
        <a href="{{ route('admin.users.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Users
        </a>
        @endcan

        @can('view roles')
        <a href="{{ route('admin.roles.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-2"></i> Roles & Permissions
        </a>
        @endcan

        @can('view subscriptions')
        <a href="{{ route('admin.subscriptions.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card me-2"></i> Subscriptions
        </a>
        @endcan

        {{-- ANALYTICS SECTION --}}
        @canany(['view analytics', 'view activity logs', 'send notifications'])
        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">ANALYTICS</div>
        @endcanany

        @can('view analytics')
        <a href="{{ route('admin.analytics.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up me-2"></i> Analytics
        </a>
        @endcan

        @can('view activity logs')
        <a href="{{ route('admin.activity-logs.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Activity Logs
        </a>
        @endcan

        @can('send notifications')
        <a href="{{ route('admin.notifications.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell me-2"></i> Notifications
            @php
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
            @endphp
            @if($unreadCount > 0)
                <span class="badge bg-danger ms-auto">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </a>
        @endcan

        {{-- SYSTEM SECTION - Visible to users with any settings permission --}}
        @canany(['manage settings', 'manage branding', 'manage contact settings', 'manage social settings', 'manage payment settings', 'manage email settings', 'manage ai settings', 'manage ads settings', 'manage security settings', 'manage feature settings', 'manage packages', 'manage hero settings', 'manage maintenance', 'view system info'])
        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">SYSTEM</div>

        <!-- Settings with Submenu -->
        @php
            $settingsOpen = request()->routeIs('admin.settings') || request()->routeIs('admin.settings.*');
        @endphp
        <div class="sidebar-submenu-wrapper">
            <a href="#settingsSubmenu"
               class="sidebar-item text-white text-decoration-none d-flex align-items-center justify-content-between {{ $settingsOpen ? 'active' : '' }}"
               data-bs-toggle="collapse"
               role="button"
               aria-expanded="{{ $settingsOpen ? 'true' : 'false' }}">
                <span><i class="bi bi-gear me-2"></i> Settings</span>
                <i class="bi bi-chevron-down submenu-arrow" style="font-size: 0.7rem; transition: transform 0.2s;"></i>
            </a>
            <div class="collapse {{ $settingsOpen ? 'show' : '' }}" id="settingsSubmenu">
                <div class="submenu-items ps-3" style="border-left: 2px solid rgba(255,255,255,0.1); margin-left: 1rem;">
                    @can('manage settings')
                    <a href="{{ route('admin.settings.general') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> General
                    </a>
                    @endcan
                    @can('manage branding')
                    <a href="{{ route('admin.settings.branding') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.branding') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-palette me-2" style="font-size: 0.8rem;"></i> Branding
                    </a>
                    @endcan
                    @can('manage packages')
                    <a href="{{ route('admin.settings.packages.index') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.packages.*') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-box-seam me-2" style="font-size: 0.8rem;"></i> Packages
                    </a>
                    @endcan
                    @can('manage ads settings')
                    <a href="{{ route('admin.settings.ads.index') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.ads.*') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-megaphone me-2" style="font-size: 0.8rem;"></i> Google Ads
                    </a>
                    @endcan
                    @can('manage hero settings')
                    <a href="{{ route('admin.settings.hero') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.hero') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-image me-2" style="font-size: 0.8rem;"></i> Hero Section
                    </a>
                    @endcan
                    @can('manage contact settings')
                    <a href="{{ route('admin.settings.contact') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.contact') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-telephone me-2" style="font-size: 0.8rem;"></i> Contact Info
                    </a>
                    @endcan
                    @can('manage social settings')
                    <a href="{{ route('admin.settings.social') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.social') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-share me-2" style="font-size: 0.8rem;"></i> Social Media
                    </a>
                    @endcan
                    @can('manage payment settings')
                    <a href="{{ route('admin.settings.payments') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.payments') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-credit-card-2-front me-2" style="font-size: 0.8rem;"></i> Payments
                    </a>
                    @endcan
                    @can('manage email settings')
                    <a href="{{ route('admin.settings.email') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-envelope me-2" style="font-size: 0.8rem;"></i> Email
                    </a>
                    @endcan
                    @can('manage ai settings')
                    <a href="{{ route('admin.settings.ai') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.ai') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-robot me-2" style="font-size: 0.8rem;"></i> AI Settings
                    </a>
                    @endcan
                    @can('manage security settings')
                    <a href="{{ route('admin.settings.recaptcha') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.recaptcha') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-shield-lock me-2" style="font-size: 0.8rem;"></i> Security
                    </a>
                    @endcan
                    @can('manage feature settings')
                    <a href="{{ route('admin.settings.features') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.features') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-toggles me-2" style="font-size: 0.8rem;"></i> Features
                    </a>
                    @endcan
                    @can('manage maintenance')
                    <a href="{{ route('admin.settings.maintenance') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.maintenance') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-tools me-2" style="font-size: 0.8rem;"></i> Maintenance
                    </a>
                    @endcan
                    @can('view system info')
                    <a href="{{ route('admin.settings.system') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.system') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-hdd-stack me-2" style="font-size: 0.8rem;"></i> System
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endcanany
    </div>
</div>

<style>
    .sidebar-submenu-wrapper .submenu-arrow {
        transform: rotate(0deg);
    }
    .sidebar-submenu-wrapper [aria-expanded="true"] .submenu-arrow {
        transform: rotate(180deg);
    }
    .sidebar-subitem {
        opacity: 0.85;
    }
    .sidebar-subitem:hover {
        opacity: 1;
        background: rgba(255,255,255,0.1) !important;
    }
    .sidebar-subitem.active {
        opacity: 1;
        background: rgba(255,255,255,0.15) !important;
    }
</style>
