@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'User Details')
@section('page-actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-modern btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit User
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Users
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-4">
        <x-card title="User Profile">
            <div class="text-center mb-4">
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}"
                         alt="{{ $user->name }}"
                         class="rounded-circle mb-3"
                         style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width: 120px; height: 120px; font-size: 3rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>

                <div class="d-flex gap-2 justify-content-center mb-3">
                    @if($user->role === 'admin')
                        <span class="badge-modern badge bg-danger">
                            <i class="bi bi-shield-fill me-1"></i>Admin
                        </span>
                    @else
                        <span class="badge-modern badge bg-info">
                            <i class="bi bi-person-fill me-1"></i>Student
                        </span>
                    @endif

                    @if($user->subscription_tier === 'premium')
                        <span class="badge-modern badge bg-warning text-dark">
                            <i class="bi bi-star-fill me-1"></i>Premium
                        </span>
                    @else
                        <span class="badge-modern badge bg-secondary">Free</span>
                    @endif
                </div>

                @if($user->email_verified_at)
                    <div class="text-success small">
                        <i class="bi bi-check-circle-fill"></i> Email Verified
                    </div>
                @else
                    <div class="text-warning small">
                        <i class="bi bi-exclamation-circle-fill"></i> Email Unverified
                    </div>
                @endif
            </div>

            <hr>

            <div class="mb-3">
                <div class="text-muted small mb-1">User ID</div>
                <div class="fw-medium">#{{ $user->id }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Member Since</div>
                <div class="fw-medium">{{ $user->created_at->format('F d, Y') }}</div>
                <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
            </div>

            @if($user->subscription_expires_at)
                <div class="mb-3">
                    <div class="text-muted small mb-1">Subscription Expires</div>
                    <div class="fw-medium">{{ $user->subscription_expires_at->format('F d, Y') }}</div>
                    @if($user->subscription_expires_at->isPast())
                        <div class="text-danger small">Expired {{ $user->subscription_expires_at->diffForHumans() }}</div>
                    @else
                        <div class="text-success small">{{ $user->subscription_expires_at->diffForHumans() }}</div>
                    @endif
                </div>
            @endif

            @if($user->google_id)
                <div class="mb-3">
                    <div class="text-muted small mb-1">Login Method</div>
                    <div>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-google"></i> Google Account
                        </span>
                    </div>
                </div>
            @endif
        </x-card>
    </div>

    <div class="col-xl-8">
        @if($user->enrollment)
            <x-card title="Course Enrollment" class="mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2">{{ $user->enrollment->course->title }}</h5>
                        <p class="text-muted mb-2">
                            <strong>Course Code:</strong> {{ $user->enrollment->course->code ?? 'N/A' }}
                        </p>
                        <p class="text-muted mb-0">
                            <strong>Enrolled:</strong> {{ $user->enrollment->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('admin.courses.show', $user->enrollment->course) }}"
                           class="btn btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View Course
                        </a>
                    </div>
                </div>
            </x-card>
        @endif

        <x-card title="Subscription History">
            @if($subscriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th>Package</th>
                                <th class="text-center">Plan</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Period</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $subscription->package->name ?? 'Premium' }}</div>
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
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($subscription->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($subscription->status === 'expired')
                                        <span class="badge bg-secondary">Expired</span>
                                    @elseif($subscription->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">KES {{ number_format($subscription->amount) }}</td>
                                <td class="text-center">
                                    @if($subscription->status === 'pending')
                                        <span class="text-muted">—</span>
                                    @elseif($subscription->starts_at && $subscription->expires_at)
                                        <small>{{ $subscription->starts_at->format('M d') }} - {{ $subscription->expires_at->format('M d, Y') }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($subscriptions->hasPages())
                    <div class="mt-3">
                        {{ $subscriptions->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="bi bi-credit-card display-4 text-muted"></i>
                    <p class="text-muted mt-3 mb-0">No subscription history available</p>
                </div>
            @endif
        </x-card>

        <x-card title="Recent Activity" class="mt-4">
            @if($user->activityLogs->count() > 0)
                @foreach($user->activityLogs->take(2) as $log)
                <div class="d-flex align-items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-{{ $log->action_color }}-subtle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-{{ $log->action_icon }} text-{{ $log->action_color }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-medium small">{{ $log->action_label }}</div>
                        @if($log->description)
                            <div class="text-muted small">{{ Str::limit($log->description, 60) }}</div>
                        @endif
                        <div class="text-muted small mt-1">
                            <i class="bi bi-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-3">
                    <i class="bi bi-clock-history fs-1 text-muted"></i>
                    <p class="text-muted small mt-2 mb-0">No activity recorded yet</p>
                </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
