@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Blog Categories')
@section('page-actions')
    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-file-text me-2"></i>All Posts
    </a>
    <a href="{{ route('admin.blog.categories.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Category
    </a>
@endsection

@section('main')
<x-card>
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Posts</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <div>
                        <div class="fw-medium">{{ $category->name }}</div>
                        <small class="text-muted">{{ $category->slug }}</small>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-info rounded-pill">{{ $category->posts_count }}</span>
                </td>
                <td>
                    @if($category->is_active)
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                    @else
                        <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Inactive</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="{{ route('admin.blog.categories.edit', $category) }}"
                           class="btn btn-sm btn-light"
                           title="Edit">
                            <i class="bi bi-pencil text-secondary"></i>
                        </a>
                        <form action="{{ route('admin.blog.categories.destroy', $category) }}"
                              method="POST"
                              class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-light delete-btn"
                                    title="Delete"
                                    data-name="{{ $category->name }}"
                                    data-posts="{{ $category->posts_count }}">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-5">
                    <i class="bi bi-folder display-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No categories found</h5>
                    <p class="text-muted mb-3">Get started by creating your first category</p>
                    <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Category
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</x-card>

@if($categories->hasPages())
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        const name = this.dataset.name;
        const posts = parseInt(this.dataset.posts);

        if (posts > 0) {
            Swal.fire({
                title: 'Cannot Delete',
                html: `The category <strong>"${name}"</strong> has <strong>${posts} post(s)</strong>.<br><br>Please reassign or delete the posts first.`,
                icon: 'error',
                confirmButtonColor: '#6c757d'
            });
            return;
        }

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
});
</script>
@endpush
