@extends('layouts.base')

@section('content')
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('partials.admin.sidebar')

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-fill">
            <!-- Top Navbar -->
            @include('partials.admin.navbar')

            <!-- Main Content -->
            <div class="container-fluid p-4">
                <!-- Page Heading -->
                @hasSection('page-header')
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h2 class="mb-0 fw-bold">@yield('page-title')</h2>
                        <div class="d-flex gap-2 align-items-center">
                            @yield('page-actions')
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('main')
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Notifications -->
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    @if(session('error'))
        <x-alert type="danger" :message="session('error')" />
    @endif
    @if(session('warning'))
        <x-alert type="warning" :message="session('warning')" />
    @endif
    @if(session('info'))
        <x-alert type="info" :message="session('info')" />
    @endif
    @if($errors->any())
        <x-alert type="danger" :message="$errors->first()" title="Validation Error" />
    @endif
@endsection

@push('styles')
<style>
    #wrapper {
        min-height: 100vh;
    }
    #sidebar-wrapper {
        min-width: 250px;
        max-width: 250px;
        background-color: #1e293b;
        color: #fff;
        transition: all 0.3s;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 100;
    }
    #page-content-wrapper {
        width: 100%;
        position: relative;
    }
    /* Ensure navbar and dropdowns appear above sidebar and sticky elements */
    #page-content-wrapper .navbar {
        position: sticky;
        top: 0;
        z-index: 1020;
    }
    #page-content-wrapper .navbar .dropdown-menu {
        z-index: 1030;
    }
    /* Lower z-index for sticky sidebar cards so they don't overlap navbar dropdowns */
    #page-content-wrapper .sticky-top {
        z-index: 1010;
    }
    /* Reduce form input size in admin */
    .form-control, .form-select {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    .form-control-lg, .form-select-lg {
        font-size: 0.95rem;
        padding: 0.6rem 0.85rem;
    }
    .form-label {
        font-size: 0.875rem;
    }

    /* Smaller badges in admin */
    .badge {
        font-size: 0.7rem;
        padding: 0.3em 0.5em;
    }

    .badge.rounded-pill {
        font-size: 0.7rem;
        padding: 0.25em 0.6em;
    }

    /* Admin Mobile Responsive Styles */
    @media (max-width: 991.98px) {
        #sidebar-wrapper {
            position: fixed;
            left: -250px;
            z-index: 1040;
        }

        #sidebar-wrapper.show {
            left: 0;
        }

        /* Ensure navbar dropdown still appears above mobile sidebar */
        #page-content-wrapper .navbar .dropdown-menu {
            z-index: 1050;
        }

        .container-fluid.p-4 {
            padding: 1rem !important;
        }
    }

    @media (max-width: 767.98px) {
        /* Headings */
        h2.fw-bold {
            font-size: 1.15rem;
        }

        h4, .card-title {
            font-size: 0.95rem;
        }

        h5 {
            font-size: 0.875rem;
        }

        h6 {
            font-size: 0.8rem;
        }

        /* Page header */
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 .d-flex.gap-2 {
            width: 100%;
            flex-wrap: wrap;
        }

        /* Cards */
        .card-body {
            padding: 0.875rem;
        }

        .card-body.p-4 {
            padding: 1rem !important;
        }

        /* Buttons */
        .btn {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }

        /* Form controls */
        .form-control, .form-select {
            font-size: 0.8rem;
            padding: 0.4rem 0.625rem;
        }

        .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        /* Margins and gaps */
        .mb-4 {
            margin-bottom: 0.875rem !important;
        }

        .mb-3 {
            margin-bottom: 0.625rem !important;
        }

        .gap-3 {
            gap: 0.5rem !important;
        }

        .gap-4 {
            gap: 0.75rem !important;
        }

        /* Tables */
        .table {
            font-size: 0.8rem;
        }

        .table td, .table th {
            padding: 0.5rem 0.625rem;
        }

        /* Badges */
        .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.45em;
        }

        /* Small text */
        .small, small {
            font-size: 0.7rem;
        }

        /* Revenue amount */
        .revenue-amount {
            font-size: 1.5rem !important;
        }

        /* Stat card values */
        .fs-5 {
            font-size: 0.9rem !important;
        }

        /* Row gutters */
        .row.g-4 {
            --bs-gutter-x: 0.75rem;
            --bs-gutter-y: 0.75rem;
        }
    }

    @media (max-width: 575.98px) {
        .container-fluid.p-4 {
            padding: 0.625rem !important;
        }

        h2.fw-bold {
            font-size: 1.05rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .btn {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
        }

        /* Make cards full width on very small screens */
        .col-xl-3, .col-md-6 {
            width: 50%;
        }

        /* Table responsive scroll */
        .table-responsive {
            font-size: 0.75rem;
        }
    }
</style>
@endpush
