@extends('layouts.frontend')

@section('title', 'Payment History - TVET Revision')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('learn.index') }}" class="text-decoration-none">My Course</a></li>
            <li class="breadcrumb-item"><a href="{{ route('learn.subscription') }}" class="text-decoration-none">Subscription</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payment History</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Payment History</h4>
                    <p class="mb-0 opacity-75">View all your subscription transactions</p>
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

    <!-- Payment History Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @include('learn.partials.payment-history-table', ['payments' => $payments, 'showPagination' => true])
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('learn.subscription') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Subscription
        </a>
    </div>
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
});
</script>
@endpush
