@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Import Questions')
@section('page-actions')
    <a href="{{ route('admin.questions.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Questions
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="Import Questions from File">
            <form action="{{ route('admin.questions.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Instructions:</strong> Upload a CSV file with questions.
                    <a href="{{ route('admin.questions.import.template') }}" class="alert-link">Download template</a>
                </div>

                @if(session('import_errors'))
                <div class="alert alert-warning">
                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Import Errors</h6>
                    <ul class="mb-0 small">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mb-4">
                    <label for="unit_id" class="form-label fw-medium">Select Unit <span class="text-danger">*</span></label>
                    <select class="form-select @error('unit_id') is-invalid @enderror"
                            id="unit_id"
                            name="unit_id"
                            required>
                        <option value="">-- Select Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->course->name }} - Unit {{ $unit->unit_number }}: {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">All imported questions will be added to this unit</small>
                </div>

                <div class="mb-4">
                    <label for="file" class="form-label fw-medium">Upload File <span class="text-danger">*</span></label>
                    <input type="file"
                           class="form-control @error('file') is-invalid @enderror"
                           id="file"
                           name="file"
                           accept=".csv,.txt"
                           required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Supported format: CSV (max 5MB)</small>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skip_header" id="skip_header" value="1" checked>
                            <label class="form-check-label" for="skip_header">
                                First row contains headers
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="update_existing" id="update_existing" value="1">
                            <label class="form-check-label" for="update_existing">
                                Update existing questions
                            </label>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-upload me-2"></i>Import Questions
                    </button>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="File Format Guide" class="border-info">
            <h6 class="fw-medium mb-3">Required Columns</h6>
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Column</th>
                        <th>Required</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>question_number</code></td>
                        <td><span class="badge bg-danger">Yes</span></td>
                    </tr>
                    <tr>
                        <td><code>question_text</code></td>
                        <td><span class="badge bg-danger">Yes</span></td>
                    </tr>
                    <tr>
                        <td><code>answer_text</code></td>
                        <td><span class="badge bg-secondary">No</span></td>
                    </tr>
                    <tr>
                        <td><code>order</code></td>
                        <td><span class="badge bg-secondary">No</span></td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <h6 class="fw-medium mb-3">Sample Data</h6>
            <div class="bg-light p-3 rounded small font-monospace">
                <div class="text-muted">question_number, question_text, answer_text, order</div>
                <div>1, "What is supply?", "Supply is...", 1</div>
                <div>2a, "Define demand", "Demand is...", 2</div>
            </div>

            <hr>

            <a href="{{ route('admin.questions.import.template') }}" class="btn btn-outline-primary w-100">
                <i class="bi bi-download me-2"></i>Download Template
            </a>
        </x-card>

        <x-card title="Tips" class="mt-4">
            <ul class="mb-0 small">
                <li class="mb-2">Question numbers can include letters (e.g., "1a", "2b")</li>
                <li class="mb-2">Empty answer fields are allowed - you can add answers later</li>
                <li class="mb-2">Enable "Update existing" to modify questions with the same number</li>
                <li class="mb-2">For multi-line answers, use \n for line breaks</li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
