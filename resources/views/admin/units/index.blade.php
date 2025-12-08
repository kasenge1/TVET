@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Units Management')
@section('page-actions')
    <a href="{{ route('admin.units.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Unit
    </a>
@endsection

@section('main')
<x-card>
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Unit Title</th>
                    <th>Course</th>
                    <th class="text-center">Questions</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $startNumber = ($units->currentPage() - 1) * $units->perPage() + 1;
                @endphp
                @forelse($units as $index => $unit)
                <tr>
                    <td>
                        <span class="badge bg-secondary">{{ $startNumber + $index }}</span>
                    </td>
                    <td>
                        <div class="fw-medium">
                            @if($unit->unit_number)
                                <span class="text-primary small me-1">Unit {{ $unit->unit_number }}:</span>
                            @endif
                            {{ $unit->title }}
                        </div>
                        @if($unit->description)
                            <small class="text-muted">{{ Str::limit(strip_tags($unit->description), 60) }}</small>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.courses.show', $unit->course) }}" class="text-decoration-none">
                            {{ $unit->course->title }}
                        </a>
                        <div><span class="badge bg-light text-dark">{{ $unit->course->code }}</span></div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info rounded-pill">{{ $unit->questions_count }}</span>
                    </td>
                    <td>
                        <div class="small">{{ $unit->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.units.show', $unit) }}"
                               class="btn btn-sm btn-light"
                               title="View">
                                <i class="bi bi-eye text-primary"></i>
                            </a>
                            <a href="{{ route('admin.units.edit', $unit) }}"
                               class="btn btn-sm btn-light"
                               title="Edit">
                                <i class="bi bi-pencil text-secondary"></i>
                            </a>
                            <form action="{{ route('admin.units.destroy', $unit) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-light delete-btn"
                                        title="Delete"
                                        data-name="{{ $unit->title }}"
                                        data-questions="{{ $unit->questions_count }}">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-collection display-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No units found</h5>
                        <p class="text-muted mb-3">Create your first unit to organize course content</p>
                        <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Unit
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

@if($units->hasPages())
    <div class="mt-4">
        {{ $units->links() }}
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
        const questions = parseInt(this.dataset.questions);

        let warningText = `You are about to delete the unit <strong>"${name}"</strong>.`;

        if (questions > 0) {
            warningText += `<br><br><span class="text-danger">⚠️ Warning:</span><ul class="text-start mb-0">`;
            warningText += `<li>This will delete <strong>${questions} question(s)</strong></li>`;
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
</script>
@endpush
