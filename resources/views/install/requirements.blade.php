@extends('install.layout')

@section('title', 'Requirements')
@section('step1-class', 'completed')
@section('step2-class', 'active')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-3">Server Requirements</h4>
    <p class="text-muted">
        Let's make sure your server meets all the requirements to run TVET Revision.
    </p>
</div>

<!-- PHP Version -->
<div class="mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-cpu me-2 text-primary"></i>PHP Version</h6>
    <div class="requirement-item {{ $requirements['php_version']['status'] ? 'success' : 'danger' }}">
        <div>
            <span class="fw-medium">PHP {{ $requirements['php_version']['required'] }}+</span>
            <span class="text-muted small ms-2">(Current: {{ $requirements['php_version']['current'] }})</span>
        </div>
        <i class="bi {{ $requirements['php_version']['status'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
    </div>
</div>

<!-- PHP Extensions -->
<div class="mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-puzzle me-2 text-primary"></i>PHP Extensions</h6>
    @foreach($requirements['extensions']['list'] as $extension => $installed)
        <div class="requirement-item {{ $installed ? 'success' : 'danger' }}">
            <span class="fw-medium">{{ $extension }}</span>
            <i class="bi {{ $installed ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
        </div>
    @endforeach
</div>

<!-- Directory Permissions -->
<div class="mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-folder me-2 text-primary"></i>Directory Permissions</h6>
    @foreach($requirements['directories']['list'] as $directory => $writable)
        <div class="requirement-item {{ $writable ? 'success' : 'danger' }}">
            <span class="fw-medium">{{ $directory }}</span>
            <div>
                @if($writable)
                    <span class="badge bg-success">Writable</span>
                @else
                    <span class="badge bg-danger">Not Writable</span>
                @endif
            </div>
        </div>
    @endforeach
</div>

@if(!$canProceed)
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Requirements not met!</strong> Please fix the issues above before continuing.
    </div>
@endif

<div class="d-flex justify-content-between">
    <a href="{{ route('install.welcome') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
    @if($canProceed)
        <a href="{{ route('install.database') }}" class="btn btn-install">
            Continue<i class="bi bi-arrow-right ms-2"></i>
        </a>
    @else
        <button class="btn btn-secondary" disabled>
            Fix Issues First
        </button>
    @endif
</div>
@endsection
