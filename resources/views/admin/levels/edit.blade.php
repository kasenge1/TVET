@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Level')
@section('page-actions')
    <a href="{{ route('admin.levels.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Levels
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.levels.update', $level) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Level Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', $level->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This will be displayed to users</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3">{{ old('description', $level->description) }}</textarea>
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
                               {{ old('is_active', $level->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (visible to users)
                        </label>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Level
                        </button>
                        <a href="{{ route('admin.levels.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Level Statistics">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Courses Using This Level</span>
                <span class="fw-bold">{{ $level->courses()->count() }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Created</span>
                <span>{{ $level->created_at->format('M d, Y') }}</span>
            </div>
        </x-card>

        @if($level->courses()->count() > 0)
            <x-card title="Warning" class="mt-4 border-warning">
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This level cannot be deleted because it has {{ $level->courses()->count() }} course(s) assigned to it.
                </div>
            </x-card>
        @endif
    </div>
</div>
@endsection
