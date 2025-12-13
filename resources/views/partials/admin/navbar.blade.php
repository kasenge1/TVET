<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <button class="btn btn-link" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            <!-- Notifications -->
            <x-notification-bell :route="route('admin.notifications.index')" prefix="admin" />

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                         class="rounded-circle me-2" width="32" height="32" alt="Profile">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="bi bi-person me-2"></i> Profile
                    </a></li>
                    @canany(['manage settings', 'manage branding', 'manage contact settings', 'manage social settings', 'manage payment settings', 'manage email settings', 'manage ai settings', 'manage ads settings', 'manage security settings', 'manage feature settings', 'manage packages', 'manage hero settings', 'manage maintenance', 'view system info'])
                    <li><a class="dropdown-item" href="{{ route('admin.settings') }}">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a></li>
                    @endcanany
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-2"></i> View Site
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

@push('scripts')
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar-wrapper')?.classList.toggle('d-none');
    });
</script>
@endpush
