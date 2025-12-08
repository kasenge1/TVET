@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Course Levels Management')
@section('page-actions')
    <a href="{{ route('admin.levels.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Level
    </a>
@endsection

@section('main')
<x-card>
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th width="60">Order</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-center">Courses</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                <tr>
                    <td>
                        <span class="badge-modern badge bg-secondary">{{ $level->order }}</span>
                    </td>
                    <td>
                        <div class="fw-medium">{{ $level->name }}</div>
                        <small class="text-muted">{{ $level->slug }}</small>
                    </td>
                    <td>
                        <div class="text-muted">{{ Str::limit($level->description, 60) ?: '—' }}</div>
                    </td>
                    <td class="text-center">
                        @if($level->courses_count > 0)
                            <span class="badge-modern badge bg-info rounded-pill">
                                {{ $level->courses_count }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($level->is_active)
                            <span class="badge-modern badge bg-success">Active</span>
                        @else
                            <span class="badge-modern badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.levels.edit', $level) }}"
                               class="btn btn-sm btn-light"
                               title="Edit">
                                <i class="bi bi-pencil text-secondary"></i>
                            </a>
                            <form action="{{ route('admin.levels.destroy', $level) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-light delete-btn"
                                        title="Delete"
                                        data-name="{{ $level->name }}"
                                        @if($level->courses_count > 0) disabled @endif>
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-layers display-3 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No levels found</h5>
                        <p class="text-muted mb-3">Get started by creating your first level</p>
                        <a href="{{ route('admin.levels.create') }}" class="btn-modern btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Level
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        const name = this.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete the level <strong>"${name}"</strong>.<br>This action cannot be undone.`,
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
