@extends('install.layout')

@section('title', 'Application Settings')
@section('step1-class', 'completed')
@section('step2-class', 'completed')
@section('step3-class', 'completed')
@section('step4-class', 'active')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-3">Application Settings</h4>
    <p class="text-muted">
        Configure your application name, URL, and timezone.
    </p>
</div>

<form action="{{ route('install.application.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="app_name" class="form-label">Application Name</label>
        <input type="text"
               class="form-control @error('app_name') is-invalid @enderror"
               id="app_name"
               name="app_name"
               value="{{ old('app_name', 'TVET Revision') }}"
               placeholder="TVET Revision"
               required>
        @error('app_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">This will appear in the browser title and emails.</div>
    </div>

    <div class="mb-3">
        <label for="app_url" class="form-label">Application URL</label>
        <input type="url"
               class="form-control @error('app_url') is-invalid @enderror"
               id="app_url"
               name="app_url"
               value="{{ old('app_url', url('/')) }}"
               placeholder="https://yourdomain.com"
               required>
        @error('app_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Your website's full URL including https://</div>
    </div>

    <div class="mb-4">
        <label for="app_timezone" class="form-label">Timezone</label>
        <select class="form-select @error('app_timezone') is-invalid @enderror"
                id="app_timezone"
                name="app_timezone"
                required>
            <option value="">Select Timezone</option>
            @php
                $timezones = [
                    'Africa/Nairobi' => 'Africa/Nairobi (East Africa Time)',
                    'Africa/Lagos' => 'Africa/Lagos (West Africa Time)',
                    'Africa/Cairo' => 'Africa/Cairo (Egypt)',
                    'Africa/Johannesburg' => 'Africa/Johannesburg (South Africa)',
                    'UTC' => 'UTC (Coordinated Universal Time)',
                    'Europe/London' => 'Europe/London (GMT)',
                    'America/New_York' => 'America/New_York (Eastern Time)',
                    'America/Los_Angeles' => 'America/Los_Angeles (Pacific Time)',
                    'Asia/Dubai' => 'Asia/Dubai (Gulf Standard Time)',
                    'Asia/Singapore' => 'Asia/Singapore (Singapore Time)',
                ];
            @endphp
            @foreach($timezones as $value => $label)
                <option value="{{ $value }}" {{ old('app_timezone', 'Africa/Nairobi') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('app_timezone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('install.database') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
        <button type="submit" class="btn btn-install">
            Continue<i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
</form>
@endsection
