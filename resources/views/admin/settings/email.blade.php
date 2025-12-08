@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Email Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="SMTP Configuration" class="border-primary">
            <form action="{{ route('admin.settings.email.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-envelope me-2"></i>
                    Configure SMTP settings for sending emails (subscription notifications, password resets, etc.)
                </div>

                @if(\App\Models\SiteSetting::emailConfigured())
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>
                    Email is configured. Click "Send Test Email" to verify settings.
                </div>
                @endif

                <div class="mb-4">
                    <label for="mail_driver" class="form-label fw-medium">Mail Driver</label>
                    <select class="form-select @error('mail_driver') is-invalid @enderror"
                            id="mail_driver"
                            name="mail_driver">
                        <option value="smtp" {{ ($emailSettings['driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="sendmail" {{ ($emailSettings['driver'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                        <option value="mailgun" {{ ($emailSettings['driver'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                        <option value="ses" {{ ($emailSettings['driver'] ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        <option value="log" {{ ($emailSettings['driver'] ?? '') === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                    </select>
                    @error('mail_driver')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="mail_host" class="form-label fw-medium">SMTP Host <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('mail_host') is-invalid @enderror"
                               id="mail_host"
                               name="mail_host"
                               value="{{ old('mail_host', $emailSettings['host'] ?? '') }}"
                               placeholder="smtp.gmail.com, smtp.mailtrap.io, etc.">
                        @error('mail_host')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="mail_port" class="form-label fw-medium">Port <span class="text-danger">*</span></label>
                        <select class="form-select @error('mail_port') is-invalid @enderror"
                                id="mail_port"
                                name="mail_port">
                            <option value="587" {{ ($emailSettings['port'] ?? '587') == '587' ? 'selected' : '' }}>587 (TLS - Recommended)</option>
                            <option value="465" {{ ($emailSettings['port'] ?? '') == '465' ? 'selected' : '' }}>465 (SSL)</option>
                            <option value="25" {{ ($emailSettings['port'] ?? '') == '25' ? 'selected' : '' }}>25 (Unencrypted)</option>
                            <option value="2525" {{ ($emailSettings['port'] ?? '') == '2525' ? 'selected' : '' }}>2525 (Alternative)</option>
                        </select>
                        @error('mail_port')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="mail_username" class="form-label fw-medium">Username</label>
                        <input type="text"
                               class="form-control @error('mail_username') is-invalid @enderror"
                               id="mail_username"
                               name="mail_username"
                               value="{{ old('mail_username', $emailSettings['username'] ?? '') }}"
                               placeholder="your-email@gmail.com">
                        @error('mail_username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Usually your email address</small>
                    </div>

                    <div class="col-md-6">
                        <label for="mail_password" class="form-label fw-medium">Password / App Password</label>
                        <input type="password"
                               class="form-control @error('mail_password') is-invalid @enderror"
                               id="mail_password"
                               name="mail_password"
                               placeholder="{{ !empty($emailSettings['password']) ? '••••••••' : 'Enter password' }}">
                        @error('mail_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave blank to keep existing. For Gmail, use App Password.</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="mail_encryption" class="form-label fw-medium">Encryption</label>
                    <select class="form-select @error('mail_encryption') is-invalid @enderror"
                            id="mail_encryption"
                            name="mail_encryption">
                        <option value="tls" {{ ($emailSettings['encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                        <option value="ssl" {{ ($emailSettings['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ empty($emailSettings['encryption']) ? 'selected' : '' }}>None</option>
                    </select>
                    @error('mail_encryption')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Sender Information</h6>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="mail_from_address" class="form-label fw-medium">From Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('mail_from_address') is-invalid @enderror"
                               id="mail_from_address"
                               name="mail_from_address"
                               value="{{ old('mail_from_address', $emailSettings['from_address'] ?? '') }}"
                               placeholder="noreply@tvetrevision.co.ke">
                        @error('mail_from_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mail_from_name" class="form-label fw-medium">From Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('mail_from_name') is-invalid @enderror"
                               id="mail_from_name"
                               name="mail_from_name"
                               value="{{ old('mail_from_name', $emailSettings['from_name'] ?? 'TVET Revision') }}"
                               placeholder="TVET Revision">
                        @error('mail_from_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Email Settings
                    </button>
                    @if(\App\Models\SiteSetting::emailConfigured())
                    <button type="button" class="btn btn-outline-success ms-2" onclick="sendTestEmail()">
                        <i class="bi bi-send me-2"></i>Send Test Email
                    </button>
                    @endif
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Email Status" class="border-secondary">
            <div class="text-center py-3">
                @if(\App\Models\SiteSetting::emailConfigured())
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-envelope-check fs-3"></i>
                    </div>
                    <h5 class="text-success">Configured</h5>
                    <p class="text-muted small">Email sending is enabled</p>
                @else
                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-envelope-x fs-3"></i>
                    </div>
                    <h5 class="text-warning">Not Configured</h5>
                    <p class="text-muted small">Add SMTP settings to enable email</p>
                @endif
            </div>
        </x-card>

        <x-card title="Popular SMTP Providers" class="mt-4">
            <div class="small">
                <div class="mb-3 p-2 bg-light rounded">
                    <strong>Gmail</strong>
                    <div class="text-muted">
                        Host: smtp.gmail.com<br>
                        Port: 587 (TLS)<br>
                        <em>Use App Password, not your regular password</em>
                    </div>
                </div>
                <div class="mb-3 p-2 bg-light rounded">
                    <strong>Mailtrap (Testing)</strong>
                    <div class="text-muted">
                        Host: smtp.mailtrap.io<br>
                        Port: 2525
                    </div>
                </div>
                <div class="p-2 bg-light rounded">
                    <strong>SendGrid</strong>
                    <div class="text-muted">
                        Host: smtp.sendgrid.net<br>
                        Port: 587 (TLS)
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
function sendTestEmail() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';

    fetch('{{ route("admin.settings.email.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Test Email Sent',
                text: data.message,
                timer: 5000,
                showConfirmButton: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Email Failed',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to send test email. Please try again.'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>
@endpush
@endsection
