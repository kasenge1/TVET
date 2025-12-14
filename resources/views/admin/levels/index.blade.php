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
    {{-- Course Filter --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.levels.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="course" class="form-label">Filter by Course</label>
                <select name="course" id="course" class="form-select" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('course'))
            <div class="col-auto">
                <a href="{{ route('admin.levels.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Clear Filter
                </a>
            </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th width="60">Order</th>
                    <th>Course</th>
                    <th>Level</th>
                    <th>Description</th>
                    <th class="text-center">Units</th>
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
                        <div class="fw-medium">{{ $level->course->title ?? '—' }}</div>
                    </td>
                    <td>
                        <div class="fw-medium">{{ $level->name }}</div>
                        @if($level->slug)
                            <small class="text-muted">{{ $level->slug }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="text-muted">{{ Str::limit($level->description, 50) ?: '—' }}</div>
                    </td>
                    <td class="text-center">
                        @if($level->units_count > 0)
                            <a href="{{ route('admin.units.index', ['level' => $level->id]) }}"
                               class="badge-modern badge bg-info rounded-pill text-decoration-none">
                                {{ $level->units_count }}
                            </a>
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
                            <a href="{{ route('admin.units.create', ['level' => $level->id]) }}"
                               class="btn btn-sm btn-light"
                               title="Add Unit">
                                <i class="bi bi-plus-lg text-success"></i>
                            </a>
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
                                        @if($level->units_count > 0) disabled @endif>
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
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

    @if($levels->hasPages())
    <div class="mt-4">
        {{ $levels->withQueryString()->links() }}
    </div>
    @endif
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
