@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Payment Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="M-Pesa Configuration" class="border-success">
            <form action="{{ route('admin.settings.payments.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Configure your M-Pesa API credentials for processing payments.
                    Get credentials from <a href="https://developer.safaricom.co.ke" target="_blank">Safaricom Developer Portal</a>
                </div>

                @if(!empty($mpesaSettings['consumer_key']))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>
                    M-Pesa is configured. Callback URL: <code>{{ url('/api/mpesa/callback') }}</code>
                </div>
                @endif

                <div class="mb-4">
                    <label for="mpesa_consumer_key" class="form-label fw-medium">Consumer Key</label>
                    <input type="text"
                           class="form-control @error('mpesa_consumer_key') is-invalid @enderror"
                           id="mpesa_consumer_key"
                           name="mpesa_consumer_key"
                           value="{{ old('mpesa_consumer_key', $mpesaSettings['consumer_key'] ?? '') }}"
                           placeholder="Enter M-Pesa Consumer Key">
                    @error('mpesa_consumer_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="mpesa_consumer_secret" class="form-label fw-medium">Consumer Secret</label>
                    <input type="password"
                           class="form-control @error('mpesa_consumer_secret') is-invalid @enderror"
                           id="mpesa_consumer_secret"
                           name="mpesa_consumer_secret"
                           value="{{ old('mpesa_consumer_secret', $mpesaSettings['consumer_secret'] ?? '') }}"
                           placeholder="Enter M-Pesa Consumer Secret">
                    @error('mpesa_consumer_secret')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank to keep existing value</small>
                </div>

                <div class="mb-4">
                    <label for="mpesa_shortcode" class="form-label fw-medium">Business Short Code</label>
                    <input type="text"
                           class="form-control @error('mpesa_shortcode') is-invalid @enderror"
                           id="mpesa_shortcode"
                           name="mpesa_shortcode"
                           value="{{ old('mpesa_shortcode', $mpesaSettings['shortcode'] ?? '') }}"
                           placeholder="e.g., 174379 (sandbox) or your Paybill/Till">
                    @error('mpesa_shortcode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="mpesa_passkey" class="form-label fw-medium">Lipa Na M-Pesa Passkey</label>
                    <input type="password"
                           class="form-control @error('mpesa_passkey') is-invalid @enderror"
                           id="mpesa_passkey"
                           name="mpesa_passkey"
                           value="{{ old('mpesa_passkey', $mpesaSettings['passkey'] ?? '') }}"
                           placeholder="Enter Lipa Na M-Pesa Passkey">
                    @error('mpesa_passkey')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank to keep existing value</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Environment</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mpesa_environment" id="sandbox" value="sandbox"
                               {{ ($mpesaSettings['environment'] ?? 'sandbox') === 'sandbox' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sandbox">
                            Sandbox (Testing)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mpesa_environment" id="production" value="production"
                               {{ ($mpesaSettings['environment'] ?? '') === 'production' ? 'checked' : '' }}>
                        <label class="form-check-label" for="production">
                            Production (Live)
                        </label>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-success px-4">
                        <i class="bi bi-check-circle me-2"></i>Save M-Pesa Settings
                    </button>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="M-Pesa Status" class="border-secondary">
            <div class="text-center py-3">
                @if(!empty($mpesaSettings['consumer_key']))
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-lg fs-3"></i>
                    </div>
                    <h5 class="text-success">Configured</h5>
                    <p class="text-muted small">M-Pesa integration is active</p>
                @else
                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-lg fs-3"></i>
                    </div>
                    <h5 class="text-warning">Not Configured</h5>
                    <p class="text-muted small">Add your M-Pesa credentials to enable payments</p>
                @endif
            </div>
        </x-card>

        <x-card title="Quick Links" class="mt-4">
            <div class="list-group list-group-flush">
                <a href="https://developer.safaricom.co.ke" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-box-arrow-up-right me-3 text-primary"></i>
                    <span>Safaricom Developer Portal</span>
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-credit-card me-3 text-success"></i>
                    <span>View Subscriptions</span>
                </a>
                <a href="{{ route('admin.settings.packages.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-box-seam me-3 text-info"></i>
                    <span>Manage Packages</span>
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
