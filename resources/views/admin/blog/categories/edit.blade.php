@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Category')
@section('page-actions')
    <a href="{{ route('admin.blog.categories.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Categories
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-6">
        <x-card>
            <form action="{{ route('admin.blog.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Category Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', $category->name) }}"
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
                           value="{{ old('slug', $category->slug) }}"
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
                              maxlength="500">{{ old('description', $category->description) }}</textarea>
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
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <small class="text-muted">Inactive categories are hidden from public</small>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Category
                        </button>
                        <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-6">
        <!-- Category Stats -->
        <x-card title="Category Stats">
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Total Posts</span>
                    <span class="badge bg-info rounded-pill">{{ $category->posts()->count() }}</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Published Posts</span>
                    <span class="badge bg-success rounded-pill">{{ $category->publishedPosts()->count() }}</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Created</span>
                    <span>{{ $category->created_at->format('M d, Y') }}</span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span class="text-muted">Last Updated</span>
                    <span>{{ $category->updated_at->format('M d, Y') }}</span>
                </li>
            </ul>
        </x-card>

        <!-- Danger Zone -->
        @if($category->posts()->count() == 0)
        <x-card title="Danger Zone" class="mt-4 border-danger">
            <p class="text-muted small mb-3">Once you delete a category, there is no going back.</p>
            <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-outline-danger w-100 delete-btn" data-name="{{ $category->name }}">
                    <i class="bi bi-trash me-1"></i>Delete Category
                </button>
            </form>
        </x-card>
        @else
        <x-card title="Danger Zone" class="mt-4">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Cannot delete this category because it has {{ $category->posts()->count() }} post(s). Reassign or delete posts first.
            </div>
        </x-card>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.querySelector('.delete-btn')?.addEventListener('click', function(e) {
    e.preventDefault();
    const form = this.closest('.delete-form');
    const name = this.dataset.name;

    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete the category <strong>"${name}"</strong>.<br><br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash me-2"></i>Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@endpush
@endsection
