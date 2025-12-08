@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Contact Information')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Contact Details" class="border-info">
            <form action="{{ route('admin.settings.contact.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    This information will be displayed on the Contact page and footer.
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('contact_email') is-invalid @enderror"
                               id="contact_email"
                               name="contact_email"
                               value="{{ old('contact_email', $contactSettings['email'] ?? '') }}"
                               required>
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label fw-medium">Phone Number <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('contact_phone') is-invalid @enderror"
                               id="contact_phone"
                               name="contact_phone"
                               value="{{ old('contact_phone', $contactSettings['phone'] ?? '') }}"
                               placeholder="+254 700 000 000"
                               required>
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="contact_address" class="form-label fw-medium">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('contact_address') is-invalid @enderror"
                               id="contact_address"
                               name="contact_address"
                               value="{{ old('contact_address', $contactSettings['address'] ?? '') }}"
                               placeholder="Nairobi, Kenya"
                               required>
                        @error('contact_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_address_line2" class="form-label fw-medium">Address Line 2</label>
                        <input type="text"
                               class="form-control @error('contact_address_line2') is-invalid @enderror"
                               id="contact_address_line2"
                               name="contact_address_line2"
                               value="{{ old('contact_address_line2', $contactSettings['address_line2'] ?? '') }}"
                               placeholder="Tom Mboya Street">
                        @error('contact_address_line2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="contact_working_hours" class="form-label fw-medium">Working Hours</label>
                    <input type="text"
                           class="form-control @error('contact_working_hours') is-invalid @enderror"
                           id="contact_working_hours"
                           name="contact_working_hours"
                           value="{{ old('contact_working_hours', $contactSettings['working_hours'] ?? '') }}"
                           placeholder="Mon - Fri: 8:00 AM - 6:00 PM">
                    @error('contact_working_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-info px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Contact Information
                    </button>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Preview" class="border-secondary">
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill text-info me-2"></i>
                    <strong>Email</strong>
                </div>
                <p class="text-muted mb-0">{{ $contactSettings['email'] ?? 'Not set' }}</p>
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-success me-2"></i>
                    <strong>Phone</strong>
                </div>
                <p class="text-muted mb-0">{{ $contactSettings['phone'] ?? 'Not set' }}</p>
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                    <strong>Address</strong>
                </div>
                <p class="text-muted mb-0">
                    {{ $contactSettings['address'] ?? 'Not set' }}<br>
                    {{ $contactSettings['address_line2'] ?? '' }}
                </p>
            </div>
            <div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-clock-fill text-warning me-2"></i>
                    <strong>Working Hours</strong>
                </div>
                <p class="text-muted mb-0">{{ $contactSettings['working_hours'] ?? 'Not set' }}</p>
            </div>
        </x-card>
    </div>
</div>
@endsection
