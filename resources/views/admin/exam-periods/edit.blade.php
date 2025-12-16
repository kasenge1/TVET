@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Exam Period')
@section('page-actions')
    <a href="{{ route('admin.exam-periods.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Exam Periods
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.exam-periods.update', $examPeriod) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="month" class="form-label fw-medium">Month <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg @error('month') is-invalid @enderror"
                            id="month"
                            name="month"
                            required>
                        <option value="">Select a month...</option>
                        @foreach($months as $num => $monthName)
                            <option value="{{ $num }}"
                                {{ old('month', $examPeriod->month) == $num ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                    @error('month')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="year" class="form-label fw-medium">Year <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg @error('year') is-invalid @enderror"
                            id="year"
                            name="year"
                            required>
                        <option value="">Select a year...</option>
                        @foreach($years as $yearOption)
                            <option value="{{ $yearOption }}"
                                {{ old('year', $examPeriod->year) == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Custom Name (Optional)</label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', $examPeriod->name) }}"
                           placeholder="e.g., July 2025 Series 1">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave empty to auto-generate (e.g., "July 2025")</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3">{{ old('description', $examPeriod->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $examPeriod->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (available for question selection)
                        </label>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Exam Period
                        </button>
                        <a href="{{ route('admin.exam-periods.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Exam Period Statistics">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Period</span>
                <span class="fw-bold">{{ $examPeriod->formatted_period }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Slug</span>
                <span class="fw-bold">{{ $examPeriod->slug }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Questions Assigned</span>
                <span class="fw-bold">{{ $examPeriod->questions()->count() }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Created</span>
                <span>{{ $examPeriod->created_at->format('M d, Y') }}</span>
            </div>
        </x-card>

        @if($examPeriod->questions()->count() > 0)
            <x-card title="Warning" class="mt-4 border-warning">
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This exam period cannot be deleted because it has {{ $examPeriod->questions()->count() }} question(s) assigned to it.
                </div>
            </x-card>
        @endif
    </div>
</div>
@endsection
