@extends('install.layout')

@section('title', 'Finalize Installation')
@section('step1-class', 'completed')
@section('step2-class', 'completed')
@section('step3-class', 'completed')
@section('step4-class', 'completed')
@section('step5-class', 'completed')
@section('step6-class', 'active')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-3">Ready to Install</h4>
    <p class="text-muted">
        We're ready to set up your application. This may take a moment.
    </p>
</div>

<!-- Summary -->
<div class="card border-0 bg-light mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-list-check text-primary me-2"></i>Installation Summary</h6>

        <div class="row g-2 small">
            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Application Name:</span>
                    <span class="fw-medium">{{ session('install.app_name') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Timezone:</span>
                    <span class="fw-medium">{{ session('install.app_timezone') }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Application URL:</span>
                    <span class="fw-medium">{{ session('install.app_url') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Database Host:</span>
                    <span class="fw-medium">{{ session('install.db_host') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Database Name:</span>
                    <span class="fw-medium">{{ session('install.db_database') }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Admin Email:</span>
                    <span class="fw-medium">{{ session('install.admin_email') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- What will happen -->
<div class="card border-0 bg-light mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-gear text-primary me-2"></i>The installer will:</h6>
        <ul class="mb-0 small text-muted" id="install-steps">
            <li class="mb-2" data-step="env"><i class="bi bi-circle me-2"></i>Configure environment settings</li>
            <li class="mb-2" data-step="migrate"><i class="bi bi-circle me-2"></i>Create database tables</li>
            <li class="mb-2" data-step="seed"><i class="bi bi-circle me-2"></i>Set up default data (roles, packages)</li>
            <li class="mb-2" data-step="admin"><i class="bi bi-circle me-2"></i>Create your admin account</li>
            <li class="mb-2" data-step="storage"><i class="bi bi-circle me-2"></i>Set up storage links</li>
            <li class="mb-2" data-step="cache"><i class="bi bi-circle me-2"></i>Optimize application</li>
        </ul>
    </div>
</div>

<!-- Progress (hidden initially) -->
<div id="install-progress" class="d-none mb-4">
    <div class="progress" style="height: 25px; border-radius: 50px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated"
             role="progressbar"
             style="width: 0%"
             id="progress-bar">
            0%
        </div>
    </div>
    <p class="text-center text-muted small mt-2" id="progress-text">Starting installation...</p>
</div>

<!-- Error message (hidden initially) -->
<div id="install-error" class="alert alert-danger d-none">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <span id="error-message"></span>
</div>

<div class="d-flex justify-content-between" id="action-buttons">
    <a href="{{ route('install.admin') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
    <button type="button" class="btn btn-install" id="install-btn" onclick="startInstallation()">
        <i class="bi bi-download me-2"></i>Install Now
    </button>
</div>
@endsection

@push('scripts')
<script>
function startInstallation() {
    const btn = document.getElementById('install-btn');
    const progress = document.getElementById('install-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const errorDiv = document.getElementById('install-error');
    const errorMsg = document.getElementById('error-message');
    const actionButtons = document.getElementById('action-buttons');

    // Disable button and show progress
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Installing...';
    progress.classList.remove('d-none');
    errorDiv.classList.add('d-none');

    // Simulate progress steps
    let currentProgress = 0;
    const steps = [
        { progress: 15, text: 'Configuring environment...' },
        { progress: 35, text: 'Creating database tables...' },
        { progress: 55, text: 'Setting up roles and packages...' },
        { progress: 70, text: 'Creating admin account...' },
        { progress: 85, text: 'Setting up storage...' },
        { progress: 95, text: 'Optimizing application...' },
    ];

    let stepIndex = 0;
    const stepInterval = setInterval(() => {
        if (stepIndex < steps.length) {
            progressBar.style.width = steps[stepIndex].progress + '%';
            progressBar.textContent = steps[stepIndex].progress + '%';
            progressText.textContent = steps[stepIndex].text;
            stepIndex++;
        }
    }, 800);

    // Make the actual API call
    fetch('{{ route("install.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(stepInterval);

        if (data.success) {
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-success');
            progressText.textContent = 'Installation complete!';

            // Hide action buttons and show success
            actionButtons.innerHTML = `
                <div class="text-center w-100">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="fw-bold text-success mb-3">Installation Successful!</h5>
                    <p class="text-muted mb-4">Your application is ready to use.</p>
                    <a href="${data.redirect}" class="btn btn-install btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                    </a>
                </div>
            `;
        } else {
            throw new Error(data.message || 'Installation failed');
        }
    })
    .catch(error => {
        clearInterval(stepInterval);
        progressBar.classList.remove('progress-bar-animated');
        progressBar.classList.add('bg-danger');
        progressText.textContent = 'Installation failed';

        errorDiv.classList.remove('d-none');
        errorMsg.textContent = error.message;

        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Retry Installation';
    });
}
</script>
@endpush
