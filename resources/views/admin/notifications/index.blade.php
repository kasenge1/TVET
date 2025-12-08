@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Notifications')


@section('page-actions')
    <a href="{{ route('admin.notifications.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-send me-1"></i> Send Notification
    </a>
    @if($unreadCount > 0)
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline ms-2">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-check-all me-1"></i> Mark All Read
            </button>
        </form>
    @endif
    @if($notifications->count() > 0)
        <button type="button" class="btn btn-outline-warning ms-2" data-bs-toggle="modal" data-bs-target="#clearReadModal">
            <i class="bi bi-trash me-1"></i> Clear Read
        </button>
        <button type="button" class="btn btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#clearAllModal">
            <i class="bi bi-trash-fill me-1"></i> Clear All
        </button>
    @endif
    <a href="{{ route('admin.notifications.preferences') }}" class="btn btn-outline-secondary ms-2 d-inline-flex align-items-center">
        <i class="bi bi-gear me-1"></i> Preferences
    </a>
@endsection

@section('main')
<!-- Filter Tabs -->
<div class="row mb-4">
    <div class="col-12">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
               class="btn btn-{{ $filter === 'all' ? 'primary' : 'outline-primary' }}">
                All
            </a>
            <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
               class="btn btn-{{ $filter === 'unread' ? 'primary' : 'outline-primary' }}">
                Unread
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}"
               class="btn btn-{{ $filter === 'read' ? 'primary' : 'outline-primary' }}">
                Read
            </a>
        </div>
    </div>
</div>

<!-- Notifications List -->
<div class="row">
    <div class="col-12">
        <x-card>
            @if($notifications->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <div class="list-group-item {{ $notification->isRead() ? '' : 'bg-light' }}">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-{{ $notification->icon_color }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                        <i class="bi bi-{{ $notification->icon_class }} text-{{ $notification->icon_color }} fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 fw-bold {{ $notification->isRead() ? 'text-muted' : '' }}">
                                                {{ $notification->title }}
                                                @if(!$notification->isRead())
                                                    <span class="badge bg-primary ms-2">New</span>
                                                @endif
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $notification->time_ago }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-1 ms-2">
                                            @if($notification->action_url)
                                                <a href="{{ route('admin.notifications.read', $notification) }}"
                                                   class="btn btn-sm btn-light"
                                                   title="View">
                                                    <i class="bi bi-eye text-primary"></i>
                                                </a>
                                            @endif
                                            @if(!$notification->isRead())
                                                <form action="{{ route('admin.notifications.read', $notification) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light" title="Mark as Read">
                                                        <i class="bi bi-check-lg text-success"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light" title="Delete">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-3">
                    {{ $notifications->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No Notifications</h5>
                    <p class="text-muted">You don't have any {{ $filter !== 'all' ? $filter : '' }} notifications yet.</p>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Clear Read Notifications Modal -->
<div class="modal fade" id="clearReadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear Read Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">This will permanently delete all notifications you have already read.</p>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.notifications.clear-read') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-trash me-2"></i>Clear Read
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Clear All Notifications Modal -->
<div class="modal fade" id="clearAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear All Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">This will permanently delete <strong>all</strong> your notifications, including unread ones.</p>
                <div class="alert alert-danger mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.notifications.clear-all') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill me-2"></i>Clear All
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
