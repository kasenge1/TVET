@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create Category')
@section('page-actions')
    <a href="{{ route('admin.blog.categories.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Categories
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-6">
        <x-card>
            <form action="{{ route('admin.blog.categories.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Category Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g., Study Tips"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="form-label fw-medium">Slug</label>
                    <input type="text"
                           class="form-control @error('slug') is-invalid @enderror"
                           id="slug"
                           name="slug"
                           value="{{ old('slug') }}"
                           placeholder="auto-generated-from-name">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave empty to auto-generate from name</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3"
                              placeholder="Brief description of this category..."
                              maxlength="500">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium d-block">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               class="form-check-input"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <small class="text-muted">Inactive categories are hidden from public</small>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Category
                        </button>
                        <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-6">
        <x-card title="Category Tips">
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Name:</strong> Keep it short and descriptive
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Description:</strong> Helps with SEO and user understanding
                </li>
                <li>
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Status:</strong> Inactive categories are hidden from public
                </li>
            </ul>
        </x-card>

        <x-card title="Suggested Categories" class="mt-4">
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Study Tips & Strategies</li>
                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Exam Preparation</li>
                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Career Guidance</li>
                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Industry News</li>
                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Success Stories</li>
                <li><i class="bi bi-check2 text-success me-2"></i>TVET Updates</li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
