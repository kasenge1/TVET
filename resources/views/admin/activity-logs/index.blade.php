@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Activity Logs')
@section('page-actions')
    <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="btn-modern btn btn-outline-success">
        <i class="bi bi-download me-2"></i>Export CSV
    </a>
    <button type="button" class="btn-modern btn btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
        <i class="bi bi-trash me-2"></i>Clear Old Logs
    </button>
@endsection

@section('main')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Today"
            value="{{ number_format($stats['today']) }}"
            icon="calendar-day"
            color="primary"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="This Week"
            value="{{ number_format($stats['this_week']) }}"
            icon="calendar-week"
            color="success"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="This Month"
            value="{{ number_format($stats['this_month']) }}"
            icon="calendar-month"
            color="info"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Total Logs"
            value="{{ number_format($stats['total']) }}"
            icon="database"
            color="secondary"
        />
    </div>
</div>

<!-- Filters -->
<x-card class="mb-4">
    <form action="{{ route('admin.activity-logs.index') }}" method="GET">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label small fw-medium">Action</label>
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">User</label>
                <select name="user_id" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-filter"></i> Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>
    </form>
</x-card>

<!-- Activity Log Table -->
<x-card>
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Resource</th>
                    <th>IP Address</th>
                </tr>
            </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td class="text-nowrap">
                    <div class="small">
                        <div class="fw-medium">{{ $log->created_at->format('M d, Y') }}</div>
                        <div class="text-muted">{{ $log->created_at->format('h:i A') }}</div>
                    </div>
                </td>
                <td>
                    @if($log->user)
                        <div class="fw-medium small">{{ $log->user->name }}</div>
                        <small class="text-muted">{{ Str::limit($log->user->email, 20) }}</small>
                    @else
                        <span class="text-muted">System</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $log->action_color }} bg-opacity-10 text-{{ $log->action_color }}">
                        <i class="bi bi-{{ $log->action_icon }} me-1"></i>
                        {{ $log->action_label }}
                    </span>
                </td>
                <td>
                    @if($log->resource_type)
                        <span class="small text-muted">{{ class_basename($log->resource_type) }}</span>
                        @if($log->resource_id)
                            <span class="small">#{{ $log->resource_id }}</span>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <small class="font-monospace text-muted">{{ $log->ip_address ?? '—' }}</small>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5">
                    <i class="bi bi-clock-history display-3 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No Activity Logs</h5>
                    <p class="text-muted mb-0">Activity logs will appear here as users interact with the system</p>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="border-top p-3">
        {{ $logs->links() }}
    </div>
    @endif
</x-card>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear Old Activity Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.activity-logs.clear') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">This will permanently delete activity logs older than the specified number of days.</p>
                    <div class="mb-3">
                        <label class="form-label">Delete logs older than</label>
                        <select name="days" class="form-select">
                            <option value="1">1 day</option>
                            <option value="3">3 days</option>
                            <option value="7" selected>7 days</option>
                            <option value="14">14 days</option>
                            <option value="30">30 days</option>
                            <option value="60">60 days</option>
                            <option value="90">90 days</option>
                            <option value="180">180 days</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This action cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Clear Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
});
</script>
@endpush
