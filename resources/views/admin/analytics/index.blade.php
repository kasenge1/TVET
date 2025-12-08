@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Analytics & Reports')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
    .metric-trend {
        font-size: 0.75rem;
    }
    .metric-trend.up { color: #198754; }
    .metric-trend.down { color: #dc3545; }
</style>
@endpush

@section('main')
<!-- Key Metrics -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Total Revenue"
            value="KES {{ number_format($totalRevenue, 2) }}"
            icon="cash-stack"
            color="success"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Monthly Revenue"
            value="KES {{ number_format($monthlyRevenue, 2) }}"
            icon="calendar-check"
            color="primary"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Active Users (7d)"
            value="{{ number_format($activeUsers) }}"
            icon="people-fill"
            color="info"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card
            title="Total Students"
            value="{{ number_format($totalStudents) }}"
            icon="mortarboard-fill"
            color="warning"
        />
    </div>
</div>

<!-- Export Buttons -->
<div class="mb-4">
    <a href="{{ route('admin.analytics.export', ['type' => 'revenue']) }}" class="btn btn-outline-success btn-sm">
        <i class="bi bi-download me-1"></i> Export Revenue
    </a>
    <a href="{{ route('admin.analytics.export', ['type' => 'users']) }}" class="btn btn-outline-primary btn-sm ms-2">
        <i class="bi bi-download me-1"></i> Export Users
    </a>
    <a href="{{ route('admin.analytics.export', ['type' => 'subscriptions']) }}" class="btn btn-outline-info btn-sm ms-2">
        <i class="bi bi-download me-1"></i> Export Subscriptions
    </a>
</div>

<!-- Revenue & User Growth Charts -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <x-card title="Revenue Trends (Last 12 Months)">
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </x-card>
    </div>
    <div class="col-xl-4">
        <x-card title="Subscription Status">
            <div class="chart-container">
                <canvas id="subscriptionChart"></canvas>
            </div>
        </x-card>
    </div>
</div>

<!-- User Growth & Daily Active Users -->
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <x-card title="User Growth (Last 12 Months)">
            <div class="chart-container">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </x-card>
    </div>
    <div class="col-xl-6">
        <x-card title="Daily Active Users (Last 30 Days)">
            <div class="chart-container">
                <canvas id="dailyActiveChart"></canvas>
            </div>
        </x-card>
    </div>
</div>

<!-- Popular Courses & Questions Distribution -->
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <x-card title="Popular Courses (by Enrollments)">
            <div class="chart-container">
                <canvas id="popularCoursesChart"></canvas>
            </div>
        </x-card>
    </div>
    <div class="col-xl-6">
        <x-card title="Questions per Course">
            <div class="chart-container">
                <canvas id="questionsChart"></canvas>
            </div>
        </x-card>
    </div>
</div>

<!-- Revenue by Plan & Recent Subscriptions -->
<div class="row g-4">
    <div class="col-xl-4">
        <x-card title="Revenue by Plan">
            <div class="chart-container">
                <canvas id="planRevenueChart"></canvas>
            </div>
            <div class="mt-3">
                @foreach($revenueByPlan as $plan)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-capitalize">{{ $plan->plan ?? 'Unknown' }}</span>
                    <span class="badge bg-primary">{{ $plan->count }} subs - KES {{ number_format($plan->total, 2) }}</span>
                </div>
                @endforeach
            </div>
        </x-card>
    </div>
    <div class="col-xl-8">
        <x-card title="Recent Subscriptions">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSubscriptions as $sub)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                        <span class="text-primary fw-medium">{{ substr($sub->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $sub->user->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $sub->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $sub->package->name ?? $sub->plan ?? 'N/A' }}</td>
                            <td>KES {{ number_format($sub->amount, 2) }}</td>
                            <td>
                                @switch($sub->status)
                                    @case('active')
                                        <span class="badge bg-success">Active</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-secondary">Expired</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Failed</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $sub->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <small>{{ $sub->created_at->format('M d, Y') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No subscriptions yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color palette
    const colors = {
        primary: '#0d6efd',
        success: '#198754',
        warning: '#ffc107',
        danger: '#dc3545',
        info: '#0dcaf0',
        secondary: '#6c757d',
        purple: '#6f42c1',
        pink: '#d63384',
        orange: '#fd7e14',
        teal: '#20c997'
    };

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueByMonth->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (KES)',
                data: {!! json_encode($revenueByMonth->pluck('total')) !!},
                borderColor: colors.success,
                backgroundColor: colors.success + '20',
                fill: true,
                tension: 0.4
            }, {
                label: 'Subscriptions',
                data: {!! json_encode($revenueByMonth->pluck('count')) !!},
                borderColor: colors.primary,
                backgroundColor: 'transparent',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Revenue (KES)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Subscriptions' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Subscription Status Chart
    const subscriptionCtx = document.getElementById('subscriptionChart').getContext('2d');
    new Chart(subscriptionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Pending', 'Expired', 'Failed'],
            datasets: [{
                data: [
                    {{ $subscriptionStats['active'] ?? 0 }},
                    {{ $subscriptionStats['pending'] ?? 0 }},
                    {{ $subscriptionStats['expired'] ?? 0 }},
                    {{ $subscriptionStats['failed'] ?? 0 }}
                ],
                backgroundColor: [colors.success, colors.warning, colors.secondary, colors.danger]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($userGrowth->pluck('month')) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($userGrowth->pluck('count')) !!},
                backgroundColor: colors.primary,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Daily Active Users Chart
    const dailyActiveCtx = document.getElementById('dailyActiveChart').getContext('2d');
    new Chart(dailyActiveCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyActiveUsers->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
            datasets: [{
                label: 'Active Users',
                data: {!! json_encode($dailyActiveUsers->pluck('count')) !!},
                borderColor: colors.info,
                backgroundColor: colors.info + '20',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Popular Courses Chart
    const popularCoursesCtx = document.getElementById('popularCoursesChart').getContext('2d');
    new Chart(popularCoursesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($popularCourses->pluck('name')->map(fn($n) => strlen($n) > 20 ? substr($n, 0, 20) . '...' : $n)) !!},
            datasets: [{
                label: 'Enrollments',
                data: {!! json_encode($popularCourses->pluck('enrollments_count')) !!},
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.info, colors.purple, colors.pink, colors.orange, colors.teal, colors.danger, colors.secondary]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Questions per Course Chart
    const questionsCtx = document.getElementById('questionsChart').getContext('2d');
    new Chart(questionsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($questionsPerCourse->pluck('name')->map(fn($n) => strlen($n) > 20 ? substr($n, 0, 20) . '...' : $n)) !!},
            datasets: [{
                label: 'Questions',
                data: {!! json_encode($questionsPerCourse->pluck('questions')) !!},
                backgroundColor: colors.warning,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Revenue by Plan Chart
    const planRevenueCtx = document.getElementById('planRevenueChart').getContext('2d');
    new Chart(planRevenueCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($revenueByPlan->pluck('plan')->map(fn($p) => ucfirst($p ?? 'Unknown'))) !!},
            datasets: [{
                data: {!! json_encode($revenueByPlan->pluck('total')) !!},
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.info]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
