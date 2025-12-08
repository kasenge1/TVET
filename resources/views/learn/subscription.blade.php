@extends('layouts.frontend')

@section('title', 'Subscription - TVET Revision')

@php
    // Check if user has pending subscription
    $hasPendingSubscription = $subscriptionHistory->where('status', 'pending')->count() > 0;
    $hasActiveSubscription = $currentSubscription && $currentSubscription->isActive();
    $canSubscribe = !$hasPendingSubscription && !$hasActiveSubscription;
@endphp

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">My Course</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subscription</li>
        </ol>
    </nav>

    @if(!$subscriptionsEnabled && !$hasActiveSubscription)
    <!-- Subscriptions Disabled Notice -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center py-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 mb-4" style="width: 80px; height: 80px;">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
            </div>
            <h4 class="fw-bold text-dark mb-3">Subscriptions Currently Unavailable</h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                {{ $subscriptionNotice }}
            </p>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('learn.index') }}" class="btn btn-primary">
                    <i class="bi bi-book me-2"></i>Continue Learning
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-envelope me-2"></i>Contact Support
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Current Subscription Status -->
    @if($currentSubscription && $currentSubscription->isActive())
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body p-4 text-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-star-fill fs-3"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">Premium Member</h4>
                            <p class="mb-0 opacity-75">{{ $currentSubscription->package->name ?? 'Premium Plan' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="small opacity-75 mb-1">Subscription Expires</div>
                    <div class="fw-bold fs-5">{{ $currentSubscription->expires_at->format('M d, Y') }}</div>
                    <small class="opacity-75">{{ $currentSubscription->expires_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Header for non-subscribers -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                    <i class="bi bi-credit-card fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Go Premium</h4>
                    <p class="mb-0 opacity-75">Enjoy ad-free learning experience</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($hasPendingSubscription)
        @php
            $pendingSub = $subscriptionHistory->where('status', 'pending')->first();
        @endphp
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>Pending Payment</strong> - You have a pending payment for <strong>{{ $pendingSub->package->name ?? 'a plan' }}</strong>.
                Complete the payment or cancel to subscribe to a different plan.
            </div>
            <div class="ms-3 d-flex gap-1 flex-shrink-0">
                <a href="{{ route('learn.subscription.pay', $pendingSub) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-credit-card me-1"></i>Pay Now
                </a>
                <form action="{{ route('learn.subscription.cancel', $pendingSub) }}" method="POST" class="d-inline cancel-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- What You Get Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-gift me-2 text-primary"></i>What You Get With Premium</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 rounded-3 bg-light">
                                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="bi bi-x-circle text-danger"></i>
                                </div>
                                <h6 class="fw-bold mb-1 small">No Ads</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Study without distractions</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 rounded-3 bg-light">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="bi bi-lightning-charge text-primary"></i>
                                </div>
                                <h6 class="fw-bold mb-1 small">Fast Learning</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Focus on what matters</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 rounded-3 bg-light">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="bi bi-heart text-success"></i>
                                </div>
                                <h6 class="fw-bold mb-1 small">Support Us</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Help us improve</p>
                            </div>
                        </div>
                        @if(\App\Models\SiteSetting::pwaEnabled() && \App\Models\SiteSetting::pwaRequiresSubscription())
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 rounded-3 bg-light">
                                <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="bi bi-download text-info"></i>
                                </div>
                                <h6 class="fw-bold mb-1 small">Offline App</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Study anywhere, anytime</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="text-center p-3 rounded-3 bg-light">
                                <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="bi bi-wifi-off text-warning"></i>
                                </div>
                                <h6 class="fw-bold mb-1 small">No Internet?</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">No problem!</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subscription Packages -->
            @if($packages->count() > 0)
            <div class="mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-tags me-2 text-primary"></i>
                    @if($currentSubscription && $currentSubscription->isActive())
                        Your Current Plan
                    @else
                        Choose Your Plan
                    @endif
                </h6>
                <div class="row g-3">
                    @foreach($packages as $package)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm position-relative {{ $package->is_popular ? 'border border-2 border-primary' : '' }}" style="border-radius: 12px;">
                            @if($package->is_popular)
                            <div class="position-absolute top-0 start-50 translate-middle">
                                <span class="badge bg-primary px-2 py-1 rounded-pill shadow-sm small">
                                    <i class="bi bi-star-fill me-1"></i>POPULAR
                                </span>
                            </div>
                            @endif

                            <div class="card-body p-3 {{ $package->is_popular ? 'pt-4' : '' }}">
                                <!-- Plan Name & Price -->
                                <div class="text-center mb-3">
                                    <h6 class="fw-bold text-dark mb-1">{{ $package->name }}</h6>
                                    <span class="text-muted small">{{ $package->duration_text }}</span>
                                    <div class="mt-2">
                                        <span class="text-muted small">KES</span>
                                        <span class="fs-4 fw-bold text-dark">{{ number_format($package->price, 0) }}</span>
                                    </div>
                                    @if($package->duration_days >= 365)
                                        @php
                                            $monthlyPackage = $packages->where('duration_days', 30)->first();
                                            $monthlyPrice = $monthlyPackage ? $monthlyPackage->price : 0;
                                            $savings = ($monthlyPrice * 12) - $package->price;
                                        @endphp
                                        @if($savings > 0)
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 mt-1">
                                            Save KES {{ number_format($savings, 0) }}
                                        </span>
                                        @endif
                                    @endif
                                </div>

                                <!-- Features -->
                                @if($package->features && count($package->features) > 0)
                                <div class="mb-3">
                                    @foreach(array_slice($package->features, 0, 3) as $feature)
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-check-circle-fill text-success me-2 small"></i>
                                        <span class="small text-dark">{{ $feature }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Subscribe Button -->
                                @if($hasActiveSubscription)
                                    <button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                                        <i class="bi bi-check-circle me-1"></i>Subscribed
                                    </button>
                                @elseif($hasPendingSubscription)
                                    <button type="button" class="btn btn-secondary btn-sm w-100" disabled title="Complete or cancel pending payment first">
                                        <i class="bi bi-clock me-1"></i>Pending Payment
                                    </button>
                                @else
                                    <form action="{{ route('learn.subscription.subscribe', $package) }}" method="POST" class="subscribe-form" data-package="{{ $package->name }}" data-price="{{ number_format($package->price, 0) }}">
                                        @csrf
                                        <button type="submit" class="btn {{ $package->is_popular ? 'btn-primary' : 'btn-outline-primary' }} btn-sm w-100">
                                            <i class="bi bi-credit-card me-1"></i>Subscribe
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <i class="bi bi-box-seam display-6 text-muted mb-2"></i>
                    <h6 class="text-muted">No Plans Available</h6>
                    <p class="text-muted small mb-0">Please check back later.</p>
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar -->
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
                        <a href="{{ route('learn.settings') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-question-circle me-2"></i>FAQ</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How do I pay?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 small text-muted">
                                    We accept M-Pesa payments. Enter your phone number and you'll receive an STK push.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I cancel anytime?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 small text-muted">
                                    Subscription expires automatically. No auto-renewal or cancellation needed.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Can I change my plan?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 small text-muted">
                                    You can subscribe to a new plan after your current subscription expires.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center text-muted small">
                        <i class="bi bi-shield-lock me-2"></i>
                        <span>Secure M-Pesa Payment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History - Full Width -->
    @if($subscriptionHistory->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Payment History</h6>
            @if($subscriptionHistory->count() > 2)
            <a href="{{ route('learn.subscription.history') }}" class="small text-primary text-decoration-none">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
            @endif
        </div>
        <div class="card-body p-0">
            @include('learn.partials.payment-history-table', ['payments' => $subscriptionHistory->take(2)])
        </div>
    </div>
    @endif
    @endif {{-- End of subscriptions enabled check --}}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel subscription confirmation with SweetAlert
    document.querySelectorAll('.cancel-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formElement = this;

            Swal.fire({
                title: 'Cancel Subscription?',
                text: 'Are you sure you want to cancel this pending subscription?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Cancel It',
                cancelButtonText: 'No, Keep It'
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
        });
    });

    // Subscribe form with progress indication
    document.querySelectorAll('.subscribe-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formElement = this;
            const packageName = this.dataset.package;
            const price = this.dataset.price;

            Swal.fire({
                title: 'Subscribe to ' + packageName + '?',
                html: `<p>You are about to subscribe for <strong>KES ${price}</strong></p><p class="text-muted small">You will receive an M-Pesa prompt on your phone.</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-credit-card me-1"></i> Proceed to Pay',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    // Show processing state
                    Swal.fire({
                        title: 'Processing...',
                        html: '<div class="mb-3"><i class="bi bi-arrow-repeat spin text-primary" style="font-size: 2rem;"></i></div><p>Please wait while we prepare your payment...</p>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            formElement.submit();
                        }
                    });
                    return false; // Prevent SweetAlert from auto-closing
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });
    });
});
</script>
<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    /* Mobile responsive styles for subscription page */
    @media (max-width: 767.98px) {
        .container.py-4 {
            padding: 1rem !important;
        }

        /* Header card */
        .card-body.p-4 {
            padding: 1rem !important;
        }

        .card-body.p-4 h4 {
            font-size: 1.1rem;
        }

        .card-body.p-4 .fs-5 {
            font-size: 1rem !important;
        }

        /* Subscription icon */
        .card-body.p-4 .rounded-circle {
            width: 45px !important;
            height: 45px !important;
        }

        .card-body.p-4 .rounded-circle .fs-3 {
            font-size: 1.25rem !important;
        }

        /* Section headings */
        h6.fw-bold {
            font-size: 0.9rem;
        }

        /* What you get cards */
        .rounded-3.bg-light h6 {
            font-size: 0.75rem;
        }

        .rounded-3.bg-light p {
            font-size: 0.65rem !important;
        }

        .rounded-3.bg-light .rounded-circle {
            width: 38px !important;
            height: 38px !important;
        }

        .rounded-3.bg-light .rounded-circle i {
            font-size: 0.9rem;
        }

        /* Package cards */
        .col-md-6.col-lg-4 {
            width: 100%;
        }

        .card-body.p-3 h6 {
            font-size: 0.9rem;
        }

        .card-body.p-3 .fs-4 {
            font-size: 1.25rem !important;
        }

        .card-body.p-3 .small {
            font-size: 0.75rem;
        }

        /* FAQ accordion */
        .accordion-button.small {
            font-size: 0.8rem;
            padding: 0.625rem 0;
        }

        .accordion-body.small {
            font-size: 0.75rem;
        }

        /* Alert pending payment */
        .alert {
            font-size: 0.85rem;
            padding: 0.75rem;
        }

        .alert .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 575.98px) {
        .container.py-4 {
            padding: 0.75rem !important;
        }

        .card-body.p-4 h4 {
            font-size: 1rem;
        }

        /* Subscription icon */
        .card-body.p-4 .rounded-circle {
            width: 40px !important;
            height: 40px !important;
        }

        /* What you get - stack 2x2 */
        .col-6.col-md-4 {
            width: 50%;
        }

        .col-12.col-md-4 {
            width: 100%;
        }

        .rounded-3.bg-light {
            padding: 0.625rem !important;
        }

        .rounded-3.bg-light .rounded-circle {
            width: 32px !important;
            height: 32px !important;
        }

        .rounded-3.bg-light .rounded-circle i {
            font-size: 0.8rem;
        }

        /* Package cards */
        .card-body.p-3 {
            padding: 0.75rem !important;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.35rem 0.625rem;
        }
    }
</style>
@endpush
