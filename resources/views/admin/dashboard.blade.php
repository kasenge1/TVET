@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Dashboard')

@section('main')
<!-- Quick Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stat-card 
            title="Total Courses"
            :value="$stats['total_courses']"
            icon="book"
            color="primary"
            :link="route('admin.courses.index')"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card 
            title="Total Students"
            :value="$stats['total_students']"
            icon="people"
            color="success"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card 
            title="Premium Students"
            :value="$stats['premium_students']"
            icon="star-fill"
            color="warning"
        />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card 
            title="Total Questions"
            :value="$stats['total_questions']"
            icon="question-circle"
            color="info"
        />
    </div>
</div>

<!-- Revenue & Activity Row -->
<div class="row g-4 mb-4">
    <!-- Monthly Revenue -->
    <div class="col-12 col-lg-6 col-xl-4">
        <x-card title="Monthly Revenue">
            <div class="text-center py-3">
                <div class="revenue-amount fw-bold text-success mb-2">
                    KES {{ number_format($stats['monthly_revenue'], 2) }}
                </div>
                <p class="text-muted mb-0 small">Last 30 days</p>
            </div>
            <div class="d-flex justify-content-around align-items-center pt-3 border-top">
                <div class="text-center">
                    <div class="fw-bold fs-5 text-info">{{ $stats['weekly_subscriptions'] ?? 0 }}</div>
                    <small class="text-muted">Weekly</small>
                </div>
                <div class="vr" style="height: 40px;"></div>
                <div class="text-center">
                    <div class="fw-bold fs-5 text-primary">{{ $stats['monthly_subscriptions'] ?? 0 }}</div>
                    <small class="text-muted">Monthly</small>
                </div>
                <div class="vr" style="height: 40px;"></div>
                <div class="text-center">
                    <div class="fw-bold fs-5 text-success">{{ $stats['yearly_subscriptions'] ?? 0 }}</div>
                    <small class="text-muted">Yearly</small>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Popular Courses -->
    <div class="col-12 col-xl-8">
        <x-card title="Popular Courses">
            <div class="table-responsive">
                <table class="table-modern table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Level</th>
                            <th class="text-center">Enrollments</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($popularCourses->take(3) as $course)
                        <tr>
                            <td class="fw-medium small">{{ $course->title }}</td>
                            <td class="small">{{ $course->level_display ?: 'No Level' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill">{{ $course->enrollments_count }}</span>
                            </td>
                            <td>
                                @if($course->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No courses available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>

<!-- Quick Access Row -->
<div class="row g-4 mb-4">
    <!-- Business Email Quick Access -->
    <div class="col-12 col-lg-6 col-xl-4">
        @php
            $contactEmail = \App\Models\SiteSetting::get('contact_email', '');
            $emailDomain = $contactEmail ? substr(strrchr($contactEmail, "@"), 1) : '';

            // Determine webmail URL based on domain
            $webmailUrl = '#';
            $emailProvider = 'Email';
            $providerIcon = 'envelope';
            $providerColor = 'primary';

            if (str_contains($emailDomain, 'gmail.com')) {
                $webmailUrl = 'https://mail.google.com';
                $emailProvider = 'Gmail';
                $providerIcon = 'google';
                $providerColor = 'danger';
            } elseif (str_contains($emailDomain, 'outlook') || str_contains($emailDomain, 'hotmail') || str_contains($emailDomain, 'live.com')) {
                $webmailUrl = 'https://outlook.live.com';
                $emailProvider = 'Outlook';
                $providerIcon = 'microsoft';
                $providerColor = 'info';
            } elseif (str_contains($emailDomain, 'yahoo')) {
                $webmailUrl = 'https://mail.yahoo.com';
                $emailProvider = 'Yahoo Mail';
                $providerIcon = 'envelope';
                $providerColor = 'purple';
            } else {
                // For custom domains, link to webmail or cPanel
                $webmailUrl = 'https://mail.' . $emailDomain;
                $emailProvider = 'Webmail';
                $providerIcon = 'envelope';
                $providerColor = 'secondary';
            }
        @endphp
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-{{ $providerColor }} bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-{{ $providerIcon }} text-{{ $providerColor }} fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Business Email</h6>
                        <small class="text-muted">{{ $contactEmail ?: 'Not configured' }}</small>
                    </div>
                </div>
                @if($contactEmail)
                    <p class="text-muted small mb-3">Check and reply to messages from your {{ $emailProvider }} inbox.</p>
                    <a href="{{ $webmailUrl }}" target="_blank" class="btn btn-{{ $providerColor }} w-100">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Open {{ $emailProvider }}
                    </a>
                @else
                    <p class="text-muted small mb-3">Configure your contact email in settings to enable quick access.</p>
                    <a href="{{ route('admin.settings.contact') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-gear me-2"></i>Configure Email
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-plus-circle d-block fs-4 mb-1"></i>
                            <span class="small">New Course</span>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-people d-block fs-4 mb-1"></i>
                            <span class="small">Manage Users</span>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-credit-card d-block fs-4 mb-1"></i>
                            <span class="small">Subscriptions</span>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="bi bi-gear d-block fs-4 mb-1"></i>
                            <span class="small">Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Students & Activities Row -->
<div class="row g-4">
    <!-- Recent Students -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold">Recent Students</h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentStudents as $student)
                    <div class="list-group-item border-0 border-bottom px-3 py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                @if($student->profile_photo_url)
                                    <img src="{{ $student->profile_photo_url }}"
                                         alt="{{ $student->name }}"
                                         class="rounded-circle"
                                         style="width: 36px; height: 36px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 12px; font-weight: 600;">
                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-medium small text-truncate">{{ $student->name }}</div>
                                <div class="text-muted small text-truncate">{{ $student->email }}</div>
                            </div>
                            <div class="text-end ms-2">
                                @if($student->isPremium())
                                    <span class="badge bg-warning text-dark">Premium</span>
                                @else
                                    <span class="badge bg-light text-dark">Free</span>
                                @endif
                                <div class="text-muted small text-nowrap mt-1">
                                    {{ $student->created_at->diffForHumans(null, true) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-people fs-3 mb-2 d-block opacity-50"></i>
                        No students yet
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold">Recent Activity</h6>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                    <div class="list-group-item border-0 border-bottom px-3 py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-circle bg-{{ $activity->action_color }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-{{ $activity->action_icon }} text-{{ $activity->action_color }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-medium small text-truncate">{{ $activity->user->name ?? 'System' }}</div>
                                <div class="text-muted small text-truncate">
                                    {{ $activity->action_label }}
                                    @if($activity->description)
                                        - {{ Str::limit($activity->description, 25) }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-muted small text-nowrap ms-2">
                                {{ $activity->created_at->diffForHumans(null, true) }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-clock-history fs-3 mb-2 d-block opacity-50"></i>
                        No recent activity
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
