@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create New Level')
@section('page-actions')
    <a href="{{ route('admin.levels.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Levels
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.levels.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="course_id" class="form-label fw-medium">Course <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg @error('course_id') is-invalid @enderror"
                            id="course_id"
                            name="course_id"
                            required>
                        <option value="">Select a course...</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}"
                                {{ (old('course_id') == $course->id || ($selectedCourse && $selectedCourse->id == $course->id)) ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Level Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g., Level 3"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Level number will be assigned automatically</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3"
                              placeholder="Brief description of this level">{{ old('description') }}</textarea>
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
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (visible to users)
                        </label>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Level
                        </button>
                        <a href="{{ route('admin.levels.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Tips">
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Course:</strong> Select the course this level belongs to
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Level Name:</strong> Use clear naming (e.g., Level 3, Level 4, Level 5)
                </li>
                <li>
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Status:</strong> Inactive levels won't appear in unit creation forms
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
