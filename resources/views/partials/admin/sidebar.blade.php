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
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">CONTENT</div>

        <a href="{{ route('admin.courses.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            <i class="bi bi-book me-2"></i> Courses
        </a>

        <a href="{{ route('admin.units.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
            <i class="bi bi-collection me-2"></i> Units
        </a>

        <a href="{{ route('admin.questions.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
            <i class="bi bi-question-circle me-2"></i> Questions
        </a>

        <a href="{{ route('admin.levels.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">
            <i class="bi bi-layers me-2"></i> Levels
        </a>

        <a href="{{ route('admin.blog.posts.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper me-2"></i> Blog
        </a>

        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">USERS</div>

        <a href="{{ route('admin.users.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Users
        </a>

        <a href="{{ route('admin.roles.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-2"></i> Roles & Permissions
        </a>

        <a href="{{ route('admin.subscriptions.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card me-2"></i> Subscriptions
        </a>

        <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">ANALYTICS</div>

        <a href="{{ route('admin.analytics.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up me-2"></i> Analytics
        </a>

        <a href="{{ route('admin.activity-logs.index') }}"
           class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Activity Logs
        </a>

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
                    <a href="{{ route('admin.settings.general') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> General
                    </a>
                    <a href="{{ route('admin.settings.branding') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.branding') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-palette me-2" style="font-size: 0.8rem;"></i> Branding
                    </a>
                    <a href="{{ route('admin.settings.packages.index') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.packages.*') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-box-seam me-2" style="font-size: 0.8rem;"></i> Packages
                    </a>
                    <a href="{{ route('admin.settings.ads.index') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.ads.*') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-megaphone me-2" style="font-size: 0.8rem;"></i> Google Ads
                    </a>
                    <a href="{{ route('admin.settings.contact') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.contact') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-telephone me-2" style="font-size: 0.8rem;"></i> Contact Info
                    </a>
                    <a href="{{ route('admin.settings.social') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.social') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-share me-2" style="font-size: 0.8rem;"></i> Social Media
                    </a>
                    <a href="{{ route('admin.settings.payments') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.payments') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-credit-card-2-front me-2" style="font-size: 0.8rem;"></i> Payments
                    </a>
                    <a href="{{ route('admin.settings.email') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-envelope me-2" style="font-size: 0.8rem;"></i> Email
                    </a>
                    <a href="{{ route('admin.settings.ai') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.ai') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-robot me-2" style="font-size: 0.8rem;"></i> AI Settings
                    </a>
                    <a href="{{ route('admin.settings.recaptcha') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.recaptcha') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-shield-lock me-2" style="font-size: 0.8rem;"></i> Security
                    </a>
                    <a href="{{ route('admin.settings.features') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.features') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-toggles me-2" style="font-size: 0.8rem;"></i> Features
                    </a>
                    <a href="{{ route('admin.settings.maintenance') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.maintenance') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-tools me-2" style="font-size: 0.8rem;"></i> Maintenance
                    </a>
                    <a href="{{ route('admin.settings.system') }}"
                       class="sidebar-item sidebar-subitem text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('admin.settings.system') ? 'active' : '' }}"
                       style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        <i class="bi bi-hdd-stack me-2" style="font-size: 0.8rem;"></i> System
                    </a>
                </div>
            </div>
        </div>
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
