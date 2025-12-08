@extends('layouts.frontend')

@section('title', 'Complete Payment - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">My Course</a></li>
            <li class="breadcrumb-item"><a href="{{ route('learn.subscription') }}" class="text-decoration-none">Subscription</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payment</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                    <i class="bi bi-credit-card fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Complete Payment</h4>
                    <p class="mb-0 opacity-75">{{ $package->name }} - KES {{ number_format($package->price, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

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

    <div class="row">
        <div class="col-lg-7 mb-4">
            <!-- Order Summary Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header bg-primary text-white py-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                            <i class="bi bi-receipt fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Order Summary</h6>
                            <small class="opacity-75">{{ $package->name }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Plan</span>
                        <span class="fw-medium">{{ $package->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Duration</span>
                        <span class="fw-medium">{{ $package->duration_text }}</span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Amount</span>
                        <span class="fw-bold fs-4 text-primary">KES {{ number_format($package->price, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- M-Pesa Payment Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <!-- M-Pesa Logo & Title -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-phone fs-3 text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Pay with M-Pesa</h5>
                        <p class="text-muted small mb-0">Enter your phone number to receive payment prompt</p>
                    </div>

                    @if(!$mpesaConfigured)
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>M-Pesa payments are not configured. Please contact support.
                        </div>
                    @else
                        <!-- Payment Form -->
                        <form action="{{ route('learn.subscription.mpesa', $subscription) }}" method="POST" id="mpesa-form">
                            @csrf

                            <div class="mb-4">
                                <label for="phone_number" class="form-label fw-medium">
                                    <i class="bi bi-phone text-success me-1"></i>Safaricom Phone Number
                                </label>
                                <div class="position-relative">
                                    <input type="tel"
                                           class="form-control form-control-lg text-center @error('phone_number') is-invalid @enderror"
                                           id="phone_number"
                                           name="phone_number"
                                           value="{{ old('phone_number', substr(Auth::user()->phone ?? '', -9)) }}"
                                           placeholder="0712 345 678"
                                           maxlength="12"
                                           required
                                           autocomplete="tel"
                                           style="font-size: 20px; letter-spacing: 2px; padding: 14px 16px; border-radius: 12px; border: 2px solid #e9ecef;">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Enter your Safaricom number (e.g., 0712345678)
                                    </small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold" id="pay-btn" style="border-radius: 12px;">
                                <i class="bi bi-send me-2"></i>Send Payment Request
                            </button>
                        </form>

                        <!-- Payment Status (shown after STK push) -->
                        <div id="payment-status" class="mt-4 d-none">
                            <div class="alert alert-info border-0 mb-3" style="border-radius: 12px;">
                                <div class="d-flex align-items-center">
                                    <div class="spinner-border spinner-border-sm text-info me-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">Payment Request Sent</div>
                                        <span id="status-message" class="small">Check your phone and enter M-Pesa PIN...</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary w-100" id="check-status-btn">
                                <i class="bi bi-arrow-clockwise me-2"></i>Check Payment Status
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- How It Works -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-center">How It Works</h6>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                1
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="fw-medium small">Enter your M-Pesa number</div>
                            <div class="text-muted small">Use your Safaricom registered number</div>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                2
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="fw-medium small">Check your phone</div>
                            <div class="text-muted small">You'll receive an M-Pesa prompt</div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                3
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="fw-medium small">Enter your PIN</div>
                            <div class="text-muted small">Complete payment & enjoy premium!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <!-- What You'll Get -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">
                        <i class="bi bi-gift text-primary me-2"></i>What You'll Get
                    </h6>
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3 flex-shrink-0">
                            <i class="bi bi-x-circle text-success"></i>
                        </div>
                        <div>
                            <div class="fw-medium">Ad-Free Experience</div>
                            <small class="text-muted">Study without any distracting advertisements</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 flex-shrink-0">
                            <i class="bi bi-lightning-charge text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-medium">Faster Learning</div>
                            <small class="text-muted">Focus on your studies without interruptions</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3 flex-shrink-0">
                            <i class="bi bi-star text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-medium">Premium Badge</div>
                            <small class="text-muted">Show your commitment to learning</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQs -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">
                        <i class="bi bi-question-circle text-info me-2"></i>FAQs
                    </h6>
                    <div class="accordion accordion-flush" id="paymentFaq">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 py-2 small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Is M-Pesa payment secure?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#paymentFaq">
                                <div class="accordion-body px-0 pt-0 text-muted small">
                                    Yes! M-Pesa uses Safaricom's secure payment infrastructure. You'll receive a prompt on your phone and must enter your PIN to confirm.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 py-2 small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    What if I don't receive the prompt?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#paymentFaq">
                                <div class="accordion-body px-0 pt-0 text-muted small">
                                    Check that you entered the correct Safaricom number. If the issue persists, wait a few minutes and try again.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 py-2 small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    When does my subscription start?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#paymentFaq">
                                <div class="accordion-body px-0 pt-0 text-muted small">
                                    Your premium access starts immediately after successful payment confirmation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="mb-3 fw-bold">Quick Links</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('learn.subscription') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Back to Plans
                        </a>
                        <a href="{{ route('learn.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-book me-2"></i>My Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mpesaForm = document.getElementById('mpesa-form');
    const payBtn = document.getElementById('pay-btn');
    const paymentStatus = document.getElementById('payment-status');
    const statusMessage = document.getElementById('status-message');
    const checkStatusBtn = document.getElementById('check-status-btn');
    const phoneInput = document.getElementById('phone_number');

    let checkInterval = null;
    let checkCount = 0;
    const maxChecks = 24; // Stop after 2 minutes (24 * 5 seconds)

    // Format phone input with auto-formatting
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove all non-digits
            let value = this.value.replace(/[^0-9]/g, '');

            // Limit to 10 digits
            value = value.slice(0, 10);

            // Format as 0712 345 678
            if (value.length > 4 && value.length <= 7) {
                value = value.slice(0, 4) + ' ' + value.slice(4);
            } else if (value.length > 7) {
                value = value.slice(0, 4) + ' ' + value.slice(4, 7) + ' ' + value.slice(7);
            }

            this.value = value;
        });

        // Style on focus
        phoneInput.addEventListener('focus', function() {
            this.style.borderColor = '#198754';
            this.style.boxShadow = '0 0 0 0.2rem rgba(25, 135, 84, 0.15)';
        });

        phoneInput.addEventListener('blur', function() {
            this.style.borderColor = '#e9ecef';
            this.style.boxShadow = 'none';
        });
    }

    if (mpesaForm) {
        mpesaForm.addEventListener('submit', function(e) {
            payBtn.disabled = true;
            payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending request...';
        });
    }

    // Show status checking after form submission (if success message present)
    @if(session('success') && str_contains(session('success'), 'Payment request sent'))
        if (paymentStatus) {
            paymentStatus.classList.remove('d-none');
            mpesaForm.classList.add('d-none');
            startStatusCheck();
        }
    @endif

    if (checkStatusBtn) {
        checkStatusBtn.addEventListener('click', function() {
            checkPaymentStatus();
        });
    }

    function startStatusCheck() {
        // Check every 5 seconds
        checkCount = 0;
        checkInterval = setInterval(function() {
            checkCount++;
            if (checkCount >= maxChecks) {
                clearInterval(checkInterval);
                showTimeoutMessage();
            } else {
                checkPaymentStatus();
            }
        }, 5000);
    }

    function showTimeoutMessage() {
        paymentStatus.innerHTML = `
            <div class="alert alert-warning border-0 mb-3" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history fs-4 text-warning me-3"></i>
                    <div>
                        <div class="fw-bold">Payment Request Timed Out</div>
                        <span class="small">The payment prompt may have expired. Please try again.</span>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success w-100" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-2"></i>Try Again
            </button>
        `;
    }

    function showFailureMessage(message, canRetry) {
        clearInterval(checkInterval);
        let html = `
            <div class="alert alert-danger border-0 mb-3" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-x-circle-fill fs-4 text-danger me-3"></i>
                    <div>
                        <div class="fw-bold">Payment Failed</div>
                        <span class="small">${message}</span>
                    </div>
                </div>
            </div>
        `;
        if (canRetry) {
            html += `
                <button type="button" class="btn btn-success w-100" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                </button>
            `;
        }
        paymentStatus.innerHTML = html;
    }

    function showSuccessMessage(redirectUrl) {
        clearInterval(checkInterval);
        paymentStatus.innerHTML = `
            <div class="alert alert-success border-0 mb-0" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 text-success me-3"></i>
                    <div>
                        <div class="fw-bold">Payment Successful!</div>
                        <span class="small">Redirecting to your subscription...</span>
                    </div>
                </div>
            </div>
        `;
        setTimeout(() => {
            window.location.href = redirectUrl || '{{ route("learn.subscription") }}';
        }, 2000);
    }

    function checkPaymentStatus() {
        if (checkStatusBtn) {
            checkStatusBtn.disabled = true;
            checkStatusBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking...';
        }

        fetch('{{ route("learn.subscription.status", $subscription) }}')
            .then(response => response.json())
            .then(data => {
                if (checkStatusBtn) {
                    checkStatusBtn.disabled = false;
                    checkStatusBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Check Payment Status';
                }

                // Handle different statuses
                if (data.status === 'active') {
                    showSuccessMessage(data.redirect);
                } else if (data.status === 'failed' || data.status === 'cancelled') {
                    showFailureMessage(data.message, data.can_retry);
                } else if (data.status === 'timeout') {
                    showTimeoutMessage();
                } else {
                    // Still pending
                    if (statusMessage) {
                        statusMessage.textContent = data.message;
                    }
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
                if (checkStatusBtn) {
                    checkStatusBtn.disabled = false;
                    checkStatusBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Check Payment Status';
                }
            });
    }
});
</script>
@endpush
