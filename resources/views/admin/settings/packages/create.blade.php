@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create Subscription Package')
@section('page-actions')
    <a href="{{ route('admin.settings.packages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Packages
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Package Details">
            <form action="{{ route('admin.settings.packages.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Package Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g., Monthly Plan, Yearly Plan"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3"
                              placeholder="Brief description of the package">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-medium">Price (KES) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">KES</span>
                            <input type="number"
                                   class="form-control @error('price') is-invalid @enderror"
                                   id="price"
                                   name="price"
                                   value="{{ old('price') }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="duration_days" class="form-label fw-medium">Duration <span class="text-danger">*</span></label>
                        <select class="form-select @error('duration_days') is-invalid @enderror"
                                id="duration_days"
                                name="duration_days"
                                required>
                            <option value="">Select duration...</option>
                            <option value="7" {{ old('duration_days') == '7' ? 'selected' : '' }}>1 Week (7 days)</option>
                            <option value="30" {{ old('duration_days') == '30' ? 'selected' : '' }}>1 Month (30 days)</option>
                            <option value="90" {{ old('duration_days') == '90' ? 'selected' : '' }}>3 Months (90 days)</option>
                            <option value="180" {{ old('duration_days') == '180' ? 'selected' : '' }}>6 Months (180 days)</option>
                            <option value="365" {{ old('duration_days') == '365' ? 'selected' : '' }}>1 Year (365 days)</option>
                        </select>
                        @error('duration_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Features</label>
                    <div id="features-container">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="features[]" placeholder="e.g., Ad-free experience">
                            <button type="button" class="btn btn-outline-danger remove-feature" disabled>
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature">
                        <i class="bi bi-plus me-1"></i>Add Feature
                    </button>
                    <small class="text-muted d-block mt-2">List the features included in this package</small>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox"
                               class="form-check-input"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                        <small class="text-muted d-block">Make this package available for purchase</small>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox"
                               class="form-check-input"
                               id="is_popular"
                               name="is_popular"
                               value="1"
                               {{ old('is_popular') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_popular">Mark as Popular</label>
                        <small class="text-muted d-block">Highlight this package as recommended</small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Package
                    </button>
                    <a href="{{ route('admin.settings.packages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Tips">
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Monthly Plan:</strong> Set duration to 30 days
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Yearly Plan:</strong> Set duration to 365 days
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Popular Badge:</strong> Use for your best value package
                </li>
                <li>
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Features:</strong> List 3-5 key benefits
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('features-container');
    const addBtn = document.getElementById('add-feature');

    addBtn.addEventListener('click', function() {
        const newInput = document.createElement('div');
        newInput.className = 'input-group mb-2';
        newInput.innerHTML = `
            <input type="text" class="form-control" name="features[]" placeholder="Enter feature">
            <button type="button" class="btn btn-outline-danger remove-feature">
                <i class="bi bi-x"></i>
            </button>
        `;
        container.appendChild(newInput);
        updateRemoveButtons();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const inputs = container.querySelectorAll('.input-group');
        inputs.forEach((input, index) => {
            const btn = input.querySelector('.remove-feature');
            btn.disabled = inputs.length === 1;
        });
    }
});
</script>
@endpush
