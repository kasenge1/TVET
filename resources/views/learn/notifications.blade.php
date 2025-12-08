@extends('layouts.frontend')

@section('title', 'Notifications - TVET Revision')

@php
    // Helper function to transform action URLs for frontend
    $transformUrl = function($url) {
        if (!$url) return $url;

        // Handle old question URLs with unit parameter like /questions?unit=2 or /student/questions?unit=2
        if (preg_match('#/(?:student/)?questions\?unit=(\d+)#', $url, $matches)) {
            $unit = \App\Models\Unit::find($matches[1]);
            if ($unit) {
                return route('learn.unit', $unit->slug);
            }
            return route('learn.index');
        }

        $url = str_replace('/student/', '/learn/', $url);
        // Map student routes to learn routes
        $url = preg_replace('#/learn/dashboard$#', '/learn', $url);
        $url = preg_replace('#/learn/bookmarks$#', '/learn/saved', $url);
        $url = preg_replace('#/learn/profile$#', '/learn/settings', $url);
        return $url;
    };
@endphp

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">My Course</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notifications</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                        <i class="bi bi-bell-fill fs-3"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">Notifications</h4>
                        <p class="mb-0 opacity-75">
                            @if($unreadCount > 0)
                                {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}
                            @else
                                All caught up!
                            @endif
                        </p>
                    </div>
                </div>
                @if($unreadCount > 0)
                <form action="{{ route('learn.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-check-all me-1"></i>Mark All Read
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Filter Tabs -->
            <ul class="nav nav-pills mb-3 gap-2">
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('learn.notifications', ['filter' => 'all']) }}">
                        All
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'unread' ? 'active' : '' }}" href="{{ route('learn.notifications', ['filter' => 'unread']) }}">
                        Unread
                        @if($unreadCount > 0)
                            <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'read' ? 'active' : '' }}" href="{{ route('learn.notifications', ['filter' => 'read']) }}">
                        Read
                    </a>
                </li>
            </ul>

            <!-- Notifications List -->
            @if($notifications->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                    <div class="list-group-item {{ $notification->isRead() ? '' : 'bg-light' }} py-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-circle bg-{{ $notification->icon_color }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-{{ $notification->icon_class }} text-{{ $notification->icon_color }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 {{ $notification->isRead() ? 'text-muted' : 'fw-bold' }}">
                                            {{ $notification->title }}
                                        </h6>
                                        <p class="text-muted small mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">{{ $notification->time_ago }}</small>
                                    </div>
                                    <div class="d-flex gap-1">
                                        @if(!$notification->isRead())
                                        <form action="{{ route('learn.notifications.read', $notification) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark as read">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('learn.notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @if($notification->action_url)
                                <a href="{{ $transformUrl($notification->action_url) }}"
                                   class="btn btn-sm btn-link text-primary p-0 mt-2"
                                   onclick="markNotificationRead({{ $notification->id }})">
                                    View Details <i class="bi bi-arrow-right"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $notifications->appends(['filter' => $filter])->links() }}
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-bell-slash display-5 text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Notifications</h5>
                    <p class="text-muted mb-0">
                        @if($filter === 'unread')
                            You have no unread notifications.
                        @elseif($filter === 'read')
                            You have no read notifications.
                        @else
                            You don't have any notifications yet.
                        @endif
                    </p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Quick Links -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">Quick Links</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('learn.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-book me-2"></i>My Course
                        </a>
                        <a href="{{ route('learn.saved') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-bookmark-fill me-2"></i>Saved Questions
                        </a>
                        <a href="{{ route('learn.subscription') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-credit-card me-2"></i>Subscription
                        </a>
                        <a href="{{ route('learn.settings') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notification Types -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>About Notifications</h6>
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted small mb-2">You'll receive notifications for:</p>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>New questions in your course</li>
                        <li>New units added</li>
                        <li>Subscription status updates</li>
                        <li>System announcements</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markNotificationRead(id) {
    fetch(`/learn/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    });
}
</script>
@endpush
