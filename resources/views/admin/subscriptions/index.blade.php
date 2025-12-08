@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Subscription Management')

@section('main')
<x-card>
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <select name="status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Payment</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th class="text-center">Plan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Expires</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
        <tbody>
            @forelse($subscriptions as $subscription)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        @if($subscription->user->profile_photo_url)
                            <img src="{{ $subscription->user->profile_photo_url }}"
                                 alt="{{ $subscription->user->name }}"
                                 class="rounded-circle me-2 flex-shrink-0"
                                 style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                 style="width: 32px; height: 32px; font-size: 11px; font-weight: 600;">
                                {{ strtoupper(substr($subscription->user->name, 0, 2)) }}
                            </div>
                        @endif
                        <div style="min-width: 0;">
                            <div class="fw-medium small text-truncate">{{ $subscription->user->name }}</div>
                            <small class="text-muted">KES {{ number_format($subscription->amount) }}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    @if($subscription->plan === 'monthly')
                        <span class="badge bg-info">Monthly</span>
                    @else
                        <span class="badge bg-primary">Yearly</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($subscription->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending Payment</span>
                    @elseif($subscription->status === 'active')
                        @if($subscription->isActive())
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Expiring</span>
                        @endif
                    @elseif($subscription->status === 'expired')
                        <span class="badge bg-secondary">Expired</span>
                    @elseif($subscription->status === 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($subscription->status === 'pending')
                        <span class="text-muted small">Awaiting payment</span>
                    @elseif($subscription->expires_at)
                        <div class="small">{{ $subscription->expires_at->format('M d, Y') }}</div>
                        <small class="{{ $subscription->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                            {{ $subscription->expires_at->diffForHumans() }}
                        </small>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                        @if($subscription->status === 'pending')
                            <form action="{{ route('admin.subscriptions.approve', $subscription) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-light"
                                        title="Approve Payment">
                                    <i class="bi bi-check-circle text-success"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.subscriptions.cancel', $subscription) }}"
                                  method="POST"
                                  class="d-inline delete-pending-form">
                                @csrf
                                <button type="button"
                                        class="btn btn-sm btn-light delete-pending-btn"
                                        title="Delete Pending"
                                        data-user="{{ $subscription->user->name }}">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        @elseif($subscription->status === 'active')
                            <form action="{{ route('admin.subscriptions.cancel', $subscription) }}"
                                  method="POST"
                                  class="d-inline cancel-form">
                                @csrf
                                <button type="button"
                                        class="btn btn-sm btn-light cancel-btn"
                                        title="Cancel Subscription"
                                        data-user="{{ $subscription->user->name }}">
                                    <i class="bi bi-x-circle text-danger"></i>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.users.show', $subscription->user) }}"
                           class="btn btn-sm btn-light"
                           title="View User">
                            <i class="bi bi-person text-primary"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5">
                    <i class="bi bi-credit-card display-3 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No subscriptions found</h5>
                    <p class="text-muted mb-3">Try adjusting your filters</p>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>

    @if($subscriptions->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $subscriptions->firstItem() ?? 0 }} to {{ $subscriptions->lastItem() ?? 0 }} of {{ $subscriptions->total() }} subscriptions
            </div>
            <div>
                {{ $subscriptions->links() }}
            </div>
        </div>
    @endif
</x-card>

<!-- Statistics Cards -->
<div class="row g-4 mt-2">
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Pending Payment"
            value="{{ number_format(\App\Models\Subscription::where('status', 'pending')->count()) }}"
            icon="hourglass-split"
            color="warning"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Active"
            value="{{ number_format(\App\Models\Subscription::where('status', 'active')->count()) }}"
            icon="check-circle"
            color="success"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Expired"
            value="{{ number_format(\App\Models\Subscription::where('status', 'expired')->count()) }}"
            icon="x-circle"
            color="secondary"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Total Revenue"
            value="KES {{ number_format(\App\Models\Subscription::where('status', 'active')->sum('amount')) }}"
            icon="cash-stack"
            color="primary"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.cancel-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.cancel-form');
        const userName = this.dataset.user;

        Swal.fire({
            title: 'Cancel Subscription?',
            html: `You are about to cancel the subscription for <strong>"${userName}"</strong>.<br><br>This will revert the user to free tier.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-x-circle me-2"></i>Yes, cancel it!',
            cancelButtonText: 'No, keep it',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

document.querySelectorAll('.delete-pending-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-pending-form');
        const userName = this.dataset.user;

        Swal.fire({
            title: 'Delete Pending Subscription?',
            html: `You are about to delete the pending subscription for <strong>"${userName}"</strong>.<br><br>This will remove the unpaid subscription request.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-2"></i>Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
