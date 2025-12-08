@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Courses Management')
@section('page-actions')
    <a href="{{ route('admin.courses.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Course
    </a>
@endsection

@section('main')
<x-card>
    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="alert alert-primary d-none mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-check2-square me-2"></i>
                <span id="selectedCount">0</span> course(s) selected
            </div>
            <div class="d-flex gap-2">
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
                    <th>Course</th>
                    <th class="text-center">Units</th>
                    <th class="text-center">Enrolled</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
        <tbody>
            @forelse($courses as $course)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input course-checkbox" value="{{ $course->id }}" data-name="{{ $course->title }}">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($course->thumbnail_url)
                            <img src="{{ asset('storage/' . $course->thumbnail_url) }}"
                                 class="rounded me-2"
                                 width="40"
                                 height="40"
                                 alt="{{ $course->title }}"
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-book text-muted"></i>
                            </div>
                        @endif
                        <div style="min-width: 0;">
                            <div class="fw-medium text-truncate">{{ $course->title }}</div>
                            <small class="text-muted">{{ $course->code }} · {{ $course->level_display ?: 'No Level' }}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-info rounded-pill">{{ $course->units_count }}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-primary rounded-pill">{{ $course->enrollments_count }}</span>
                </td>
                <td>
                    @if($course->is_published)
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Published</span>
                    @else
                        <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Draft</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="{{ route('admin.courses.show', $course) }}"
                           class="btn btn-sm btn-light"
                           title="View">
                            <i class="bi bi-eye text-primary"></i>
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}"
                           class="btn btn-sm btn-light"
                           title="Edit">
                            <i class="bi bi-pencil text-secondary"></i>
                        </a>
                        @if($course->is_published)
                            <form action="{{ route('admin.courses.unpublish', $course) }}"
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
                            <form action="{{ route('admin.courses.publish', $course) }}"
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
                        <form action="{{ route('admin.courses.destroy', $course) }}"
                              method="POST"
                              class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-light delete-btn"
                                    title="Delete"
                                    data-name="{{ $course->title }}"
                                    data-units="{{ $course->units_count }}"
                                    data-enrollments="{{ $course->enrollments_count }}">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No courses found</h5>
                    <p class="text-muted mb-3">Get started by creating your first course</p>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Course
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</x-card>

@if($courses->hasPages())
    <div class="mt-4">
        {{ $courses->links() }}
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
        const units = parseInt(this.dataset.units);
        const enrollments = parseInt(this.dataset.enrollments);

        let warningText = `You are about to delete the course <strong>"${name}"</strong>.`;

        if (units > 0 || enrollments > 0) {
            warningText += '<br><br><span class="text-danger">⚠️ Warning:</span><ul class="text-start mb-0">';
            if (units > 0) {
                warningText += `<li>This will delete <strong>${units} unit(s)</strong> and all their questions</li>`;
            }
            if (enrollments > 0) {
                warningText += `<li><strong>${enrollments} student(s)</strong> are enrolled in this course</li>`;
            }
            warningText += '</ul>';
        }

        warningText += '<br>This action cannot be undone.';

        Swal.fire({
            title: 'Are you sure?',
            html: warningText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-2"></i>Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            width: '500px'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Bulk selection handling
const selectAllCheckbox = document.getElementById('selectAll');
const courseCheckboxes = document.querySelectorAll('.course-checkbox');
const bulkActionsBar = document.getElementById('bulkActionsBar');
const selectedCountSpan = document.getElementById('selectedCount');

function updateBulkActionsBar() {
    const checkedBoxes = document.querySelectorAll('.course-checkbox:checked');
    const count = checkedBoxes.length;

    selectedCountSpan.textContent = count;

    if (count > 0) {
        bulkActionsBar.classList.remove('d-none');
    } else {
        bulkActionsBar.classList.add('d-none');
    }

    // Update select all checkbox state
    if (courseCheckboxes.length > 0) {
        selectAllCheckbox.checked = count === courseCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < courseCheckboxes.length;
    }
}

selectAllCheckbox?.addEventListener('change', function() {
    courseCheckboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionsBar();
});

courseCheckboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkActionsBar);
});

function clearSelection() {
    courseCheckboxes.forEach(cb => cb.checked = false);
    selectAllCheckbox.checked = false;
    updateBulkActionsBar();
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.course-checkbox:checked')).map(cb => cb.value);
}

function getSelectedNames() {
    return Array.from(document.querySelectorAll('.course-checkbox:checked')).map(cb => cb.dataset.name);
}

function bulkAction(action) {
    const ids = getSelectedIds();
    const names = getSelectedNames();

    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select at least one course.', 'warning');
        return;
    }

    let title, text, confirmText, confirmColor, icon;

    switch (action) {
        case 'publish':
            title = 'Publish Courses?';
            text = `Are you sure you want to publish ${ids.length} course(s)?`;
            confirmText = '<i class="bi bi-check-circle me-1"></i>Yes, Publish';
            confirmColor = '#198754';
            icon = 'question';
            break;
        case 'unpublish':
            title = 'Unpublish Courses?';
            text = `Are you sure you want to unpublish ${ids.length} course(s)? Students won't be able to see them.`;
            confirmText = '<i class="bi bi-eye-slash me-1"></i>Yes, Unpublish';
            confirmColor = '#ffc107';
            icon = 'warning';
            break;
        case 'delete':
            title = 'Delete Courses?';
            text = `You are about to delete ${ids.length} course(s):<br><ul class="text-start">${names.map(n => `<li>${n}</li>`).join('')}</ul><br><strong class="text-danger">This action cannot be undone!</strong>`;
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
            // Submit via form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.courses.bulk-action") }}';

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
