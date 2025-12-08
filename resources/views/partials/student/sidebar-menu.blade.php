<div class="p-2">
    <a href="{{ route('student.dashboard') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <a href="{{ route('student.course') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.course') ? 'active' : '' }}">
        <i class="bi bi-book me-2"></i> My Course
    </a>

    <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">STUDY</div>

    <a href="{{ route('student.questions.index') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.questions.*') ? 'active' : '' }}">
        <i class="bi bi-question-circle me-2"></i> Questions
    </a>

    <a href="{{ route('student.bookmarks') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.bookmarks') ? 'active' : '' }}">
        <i class="bi bi-bookmark-fill me-2"></i> Bookmarks
    </a>

    <a href="{{ route('student.search') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.search') ? 'active' : '' }}">
        <i class="bi bi-search me-2"></i> Search
    </a>

    <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">ACCOUNT</div>

    <a href="{{ route('student.subscription') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.subscription') ? 'active' : '' }}">
        <i class="bi bi-star-fill me-2"></i>
        @if(Auth::user()->isPremium())
            Premium
        @else
            Upgrade
        @endif
    </a>

    <a href="{{ route('student.profile') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.profile') ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i> Profile
    </a>

    <a href="{{ route('student.notifications.index') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center {{ request()->routeIs('student.notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell me-2"></i> Notifications
        @php
            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
        @endphp
        @if($unreadCount > 0)
            <span class="badge bg-danger ms-auto">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </a>

    <div class="text-white-50 small fw-bold mt-3 mb-2 px-3" style="font-size: 0.7rem;">NAVIGATION</div>

    <a href="{{ route('home') }}"
       class="sidebar-item text-white text-decoration-none d-flex align-items-center">
        <i class="bi bi-house me-2"></i> Home
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="sidebar-item text-white text-decoration-none d-flex align-items-center w-100 border-0 bg-transparent">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</div>
