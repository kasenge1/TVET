@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Blog Posts')
@section('page-actions')
    <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-folder me-2"></i>Categories
    </a>
    <a href="{{ route('admin.blog.posts.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Post
    </a>
@endsection

@section('main')
<!-- Filters -->
<x-card class="mb-4">
    <form action="{{ route('admin.blog.posts.index') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</x-card>

<x-card>
    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="alert alert-primary d-none mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <i class="bi bi-check2-square me-2"></i>
                <span id="selectedCount">0</span> post(s) selected
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('publish')">
                    <i class="bi bi-check-circle me-1"></i>Publish
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="bulkAction('unpublish')">
                    <i class="bi bi-eye-slash me-1"></i>Unpublish
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('delete')">
                    <i class="bi bi-trash me-1"></i>Delete
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="clearSelection()">
                    <i class="bi bi-x-lg me-1"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
            <tr>
                <th width="40">
                    <input type="checkbox" class="form-check-input" id="selectAll" title="Select All">
                </th>
                <th>Post</th>
                <th>Category</th>
                <th class="text-center">Views</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input post-checkbox" value="{{ $post->id }}" data-name="{{ $post->title }}">
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                 class="rounded"
                                 width="40"
                                 height="30"
                                 alt=""
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 30px;">
                                <i class="bi bi-image text-muted small"></i>
                            </div>
                        @endif
                        <div>
                            <div class="fw-medium small">
                                {{ Str::limit($post->title, 50) }}
                                @if($post->is_featured)
                                    <i class="bi bi-star-fill text-warning ms-1"></i>
                                @endif
                            </div>
                            <small class="text-muted">{{ $post->author->name ?? 'Unknown' }} &bull; {{ $post->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    @if($post->category)
                        <span class="badge bg-info">{{ $post->category->name }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge bg-secondary rounded-pill">{{ number_format($post->views_count) }}</span>
                </td>
                <td>
                    @if($post->status === 'published')
                        <span class="badge bg-success">Published</span>
                    @elseif($post->status === 'scheduled')
                        <span class="badge bg-info">Scheduled</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="{{ route('admin.blog.posts.edit', $post) }}"
                           class="btn btn-sm btn-light"
                           title="Edit">
                            <i class="bi bi-pencil text-secondary"></i>
                        </a>
                        @if($post->status === 'published')
                            <form action="{{ route('admin.blog.posts.unpublish', $post) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-light"
                                        title="Unpublish">
                                    <i class="bi bi-eye-slash text-warning"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.blog.posts.publish', $post) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-light"
                                        title="Publish">
                                    <i class="bi bi-check-circle text-success"></i>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.blog.posts.destroy', $post) }}"
                              method="POST"
                              class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-light delete-btn"
                                    title="Delete"
                                    data-name="{{ $post->title }}">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="bi bi-file-earmark-text display-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No blog posts found</h5>
                    <p class="text-muted mb-3">Get started by creating your first blog post</p>
                    <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Post
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</x-card>

@if($posts->hasPages())
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
// Single delete handler
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        const name = this.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete the post <strong>"${name}"</strong>.<br><br>This action cannot be undone.`,
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

// Bulk selection handling
const selectAllCheckbox = document.getElementById('selectAll');
const postCheckboxes = document.querySelectorAll('.post-checkbox');
const bulkActionsBar = document.getElementById('bulkActionsBar');
const selectedCountSpan = document.getElementById('selectedCount');

function updateBulkActionsBar() {
    const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
    const count = checkedBoxes.length;

    selectedCountSpan.textContent = count;

    if (count > 0) {
        bulkActionsBar.classList.remove('d-none');
    } else {
        bulkActionsBar.classList.add('d-none');
    }

    if (postCheckboxes.length > 0) {
        selectAllCheckbox.checked = count === postCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < postCheckboxes.length;
    }
}

selectAllCheckbox?.addEventListener('change', function() {
    postCheckboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionsBar();
});

postCheckboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkActionsBar);
});

function clearSelection() {
    postCheckboxes.forEach(cb => cb.checked = false);
    selectAllCheckbox.checked = false;
    updateBulkActionsBar();
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.post-checkbox:checked')).map(cb => cb.value);
}

function getSelectedNames() {
    return Array.from(document.querySelectorAll('.post-checkbox:checked')).map(cb => cb.dataset.name);
}

function bulkAction(action) {
    const ids = getSelectedIds();
    const names = getSelectedNames();

    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select at least one post.', 'warning');
        return;
    }

    let title, text, confirmText, confirmColor, icon;

    switch (action) {
        case 'publish':
            title = 'Publish Posts?';
            text = `Are you sure you want to publish ${ids.length} post(s)?`;
            confirmText = '<i class="bi bi-check-circle me-1"></i>Yes, Publish';
            confirmColor = '#198754';
            icon = 'question';
            break;
        case 'unpublish':
            title = 'Unpublish Posts?';
            text = `Are you sure you want to unpublish ${ids.length} post(s)?`;
            confirmText = '<i class="bi bi-eye-slash me-1"></i>Yes, Unpublish';
            confirmColor = '#ffc107';
            icon = 'warning';
            break;
        case 'delete':
            title = 'Delete Posts?';
            text = `You are about to delete ${ids.length} post(s):<br><ul class="text-start">${names.map(n => `<li>${n}</li>`).join('')}</ul><br><strong class="text-danger">This action cannot be undone!</strong>`;
            confirmText = '<i class="bi bi-trash me-1"></i>Yes, Delete All';
            confirmColor = '#dc3545';
            icon = 'warning';
            break;
    }

    Swal.fire({
        title: title,
        html: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.blog.posts.bulk-action") }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
