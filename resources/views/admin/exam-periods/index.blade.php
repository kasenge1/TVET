@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Exam Periods Management')
@section('page-actions')
    <a href="{{ route('admin.exam-periods.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Exam Period
    </a>
@endsection

@section('main')
<x-card>
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-center">Questions</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($examPeriods as $period)
                <tr>
                    <td>
                        <div class="fw-medium">{{ $period->formatted_period }}</div>
                        <small class="text-muted">{{ $period->period_key }}</small>
                    </td>
                    <td>
                        <div class="fw-medium">{{ $period->name }}</div>
                        @if($period->slug)
                            <small class="text-muted">{{ $period->slug }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="text-muted">{{ Str::limit($period->description, 50) ?: '—' }}</div>
                    </td>
                    <td class="text-center">
                        @if($period->questions_count > 0)
                            <a href="{{ route('admin.questions.index', ['exam_period' => $period->id]) }}"
                               class="badge-modern badge bg-info rounded-pill text-decoration-none">
                                {{ $period->questions_count }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($period->is_active)
                            <span class="badge-modern badge bg-success">Active</span>
                        @else
                            <span class="badge-modern badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.exam-periods.edit', $period) }}"
                               class="btn btn-sm btn-light"
                               title="Edit">
                                <i class="bi bi-pencil text-secondary"></i>
                            </a>
                            <form action="{{ route('admin.exam-periods.destroy', $period) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-light delete-btn"
                                        title="Delete"
                                        data-name="{{ $period->name }}"
                                        @if($period->questions_count > 0) disabled @endif>
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-calendar-event display-3 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No exam periods found</h5>
                        <p class="text-muted mb-3">Get started by creating your first exam period</p>
                        <a href="{{ route('admin.exam-periods.create') }}" class="btn-modern btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Exam Period
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($examPeriods->hasPages())
    <div class="mt-4">
        {{ $examPeriods->links() }}
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
            html: `You are about to delete the exam period <strong>"${name}"</strong>.<br>This action cannot be undone.`,
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
