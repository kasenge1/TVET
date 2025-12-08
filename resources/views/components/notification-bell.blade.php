@props(['route' => '#', 'prefix' => 'student'])

@php
    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
        ->whereNull('read_at')
        ->count();

    $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Helper function to transform action URLs for frontend
    $transformUrl = function($url) use ($prefix) {
        if (!$url) return $url;

        // Handle old question URLs with unit parameter like /questions?unit=2 or /student/questions?unit=2
        if (preg_match('#/(?:student/)?questions\?unit=(\d+)#', $url, $matches)) {
            $unit = \App\Models\Unit::find($matches[1]);
            if ($unit) {
                return route('learn.unit', $unit->slug);
            }
            return route('learn.index');
        }

        if ($prefix === 'student') return $url;

        $url = str_replace('/student/', '/' . $prefix . '/', $url);
        // Map student routes to learn routes
        $url = preg_replace('#/' . $prefix . '/dashboard$#', '/' . $prefix, $url);
        $url = preg_replace('#/' . $prefix . '/bookmarks$#', '/' . $prefix . '/saved', $url);
        $url = preg_replace('#/' . $prefix . '/profile$#', '/' . $prefix . '/settings', $url);
        return $url;
    };
@endphp

<div class="dropdown">
    <button class="btn btn-link text-dark position-relative p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
        <i class="bi bi-bell fs-5"></i>
        @if($unreadCount > 0)
            <span class="position-absolute bg-danger text-white d-flex align-items-center justify-content-center" style="top: 2px; right: 2px; min-width: 18px; height: 18px; font-size: 0.65rem; border-radius: 50%; padding: 0 4px;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <h6 class="mb-0 fw-bold">Notifications</h6>
            @if($unreadCount > 0)
                <span class="badge bg-primary">{{ $unreadCount }} new</span>
            @endif
        </div>

        @if($recentNotifications->count() > 0)
            @foreach($recentNotifications as $notification)
                <a href="{{ $notification->action_url ? $transformUrl($notification->action_url) : '#' }}"
                   class="dropdown-item py-2 {{ $notification->isRead() ? '' : 'bg-light' }}"
                   onclick="markNotificationRead({{ $notification->id }})">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-2">
                            <div class="rounded-circle bg-{{ $notification->icon_color }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-{{ $notification->icon_class }} text-{{ $notification->icon_color }} small"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium small {{ $notification->isRead() ? 'text-muted' : '' }}">
                                {{ Str::limit($notification->title, 30) }}
                            </div>
                            <div class="text-muted small" style="font-size: 0.75rem;">
                                {{ Str::limit($notification->message, 40) }}
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                {{ $notification->time_ago }}
                            </div>
                        </div>
                        @if(!$notification->isRead())
                            <span class="badge bg-primary rounded-pill" style="font-size: 0.5rem;">•</span>
                        @endif
                    </div>
                </a>
            @endforeach
            <div class="dropdown-divider"></div>
            <a href="{{ $route }}" class="dropdown-item text-center text-primary py-2">
                <small>View All Notifications</small>
            </a>
        @else
            <div class="text-center py-4">
                <i class="bi bi-bell-slash text-muted fs-4"></i>
                <p class="text-muted small mb-0 mt-2">No notifications yet</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function markNotificationRead(id) {
    fetch(`/{{ $prefix }}/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    });
}
</script>
@endpush
