@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Questions Management')
@section('page-actions')
    <a href="{{ route('admin.questions.import') }}" class="btn-modern btn btn-outline-success">
        <i class="bi bi-upload me-2"></i>Import
    </a>
    <a href="{{ route('admin.questions.export', request()->query()) }}" class="btn-modern btn btn-outline-info ms-2">
        <i class="bi bi-download me-2"></i>Export
    </a>
    <a href="{{ route('admin.questions.create') }}" class="btn-modern btn btn-primary ms-2">
        <i class="bi bi-plus-circle me-2"></i>Add New Question
    </a>
@endsection

@section('main')
<!-- Filters -->
<x-card class="mb-4">
    <form method="GET" action="{{ route('admin.questions.index') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="course_filter" class="form-label">Filter by Course</label>
                <select name="course" id="course_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    @foreach(\App\Models\Course::orderBy('title')->get() as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="unit_filter" class="form-label">Filter by Unit</label>
                <select name="unit" id="unit_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">All Units</option>
                    @if(request('course'))
                        @foreach(\App\Models\Unit::where('course_id', request('course'))->orderBy('unit_number')->get() as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                Unit {{ $unit->unit_number }}: {{ $unit->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="exam_period_filter" class="form-label">Filter by Exam Period</label>
                <select name="exam_period" id="exam_period_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">All Exam Periods</option>
                    @foreach(\App\Models\ExamPeriod::ordered()->get() as $period)
                        <option value="{{ $period->id }}" {{ request('exam_period') == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Search Questions</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
        </div>
        @if(request('course') || request('unit') || request('exam_period') || request('search'))
            <div class="mt-3">
                <a href="{{ route('admin.questions.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Clear All Filters
                </a>
            </div>
        @endif
    </form>
</x-card>

<x-card>
    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="alert alert-primary d-none mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-check2-square me-2"></i>
                <span id="selectedCount">0</span> question(s) selected
            </div>
            <div class="d-flex gap-2">
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
                    <th width="70">Q#</th>
                    <th>Question</th>
                    <th>Unit</th>
                    <th>Exam Period</th>
                    <th class="text-center">Answer</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
        <tbody>
            @php
                $query = \App\Models\Question::with(['unit.course', 'examPeriod'])
                    ->whereNull('parent_question_id');

                if (request('course')) {
                    $query->whereHas('unit', function($q) {
                        $q->where('course_id', request('course'));
                    });
                }

                if (request('unit')) {
                    $query->where('unit_id', request('unit'));
                }

                if (request('exam_period')) {
                    $query->where('exam_period_id', request('exam_period'));
                }

                if (request('search')) {
                    $query->where('question_text', 'like', '%' . request('search') . '%');
                }

                // Get total count for inverted numbering
                $totalCount = (clone $query)->count();
                $questions = $query->latest()->paginate(20);

                // Calculate starting number for this page (inverted: newest shows highest number)
                // Page 1 shows items: total, total-1, total-2, ...
                // Page 2 shows items: total-20, total-21, ...
                $pageStartNumber = $totalCount - (($questions->currentPage() - 1) * $questions->perPage());
            @endphp

            @forelse($questions as $index => $question)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input question-checkbox" value="{{ $question->id }}"
                           data-name="Question {{ $question->question_number }}">
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-secondary">{{ $pageStartNumber - $index }}</span>
                        @if($question->isVideoQuestion())
                            <span class="badge bg-danger" title="Video Question"><i class="bi bi-play-circle"></i></span>
                        @endif
                        @if($question->ai_generated)
                            <span class="badge bg-info" title="AI Generated"><i class="bi bi-robot"></i></span>
                        @endif
                        @if($question->has_sub_questions)
                            <span class="badge bg-warning text-dark" title="Has sub-questions"><i class="bi bi-diagram-3"></i></span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($question->isVideoQuestion())
                        <div class="fw-medium small"><i class="bi bi-play-circle text-danger me-1"></i>Video Question</div>
                    @else
                        <div class="fw-medium small">{{ Str::limit(strip_tags($question->question_text), 60) }}</div>
                    @endif
                    <small class="text-muted">{{ $question->unit->course->code }}</small>
                    @if($question->subQuestions->count() > 0)
                        <span class="badge bg-info ms-1">{{ $question->subQuestions->count() }} sub</span>
                    @endif
                </td>
                <td>
                    <span class="small text-muted">Unit {{ $question->unit->unit_number }}</span>
                </td>
                <td>
                    @if($question->examPeriod)
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $question->examPeriod->name }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($question->answer_text)
                        <i class="bi bi-check-circle-fill text-success"></i>
                    @else
                        <i class="bi bi-x-circle text-warning"></i>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="{{ route('admin.questions.show', $question) }}"
                           class="btn btn-sm btn-light"
                           title="View">
                            <i class="bi bi-eye text-primary"></i>
                        </a>
                        <a href="{{ route('admin.questions.edit', $question) }}"
                           class="btn btn-sm btn-light"
                           title="Edit">
                            <i class="bi bi-pencil text-secondary"></i>
                        </a>
                        <form action="{{ route('admin.questions.destroy', $question) }}"
                              method="POST"
                              class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-light delete-btn"
                                    title="Delete"
                                    data-name="Question {{ $question->question_number }}"
                                    data-has-subs="{{ $question->subQuestions->count() > 0 ? 'true' : 'false' }}"
                                    data-sub-count="{{ $question->subQuestions->count() }}">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="bi bi-question-circle display-3 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No questions found</h5>
                    <p class="text-muted mb-3">Get started by creating your first question</p>
                    <a href="{{ route('admin.questions.create') }}" class="btn-modern btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Question
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</x-card>

@if($questions->hasPages())
    <div class="mt-4">
        {{ $questions->appends(request()->query())->links() }}
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
        const hasSubs = this.dataset.hasSubs === 'true';
        const subCount = this.dataset.subCount;

        let warningText = `You are about to delete <strong>"${name}"</strong>.`;
        if (hasSubs) {
            warningText += `<br><br><span class="text-danger">⚠️ This will also delete ${subCount} sub-question(s)!</span>`;
        }
        warningText += `<br><br>This action cannot be undone.`;

        Swal.fire({
            title: 'Are you sure?',
            html: warningText,
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
const questionCheckboxes = document.querySelectorAll('.question-checkbox');
const bulkActionsBar = document.getElementById('bulkActionsBar');
const selectedCountSpan = document.getElementById('selectedCount');

function updateBulkActionsBar() {
    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
    const count = checkedBoxes.length;

    selectedCountSpan.textContent = count;

    if (count > 0) {
        bulkActionsBar.classList.remove('d-none');
    } else {
        bulkActionsBar.classList.add('d-none');
    }

    // Update select all checkbox state
    if (questionCheckboxes.length > 0) {
        selectAllCheckbox.checked = count === questionCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < questionCheckboxes.length;
    }
}

selectAllCheckbox?.addEventListener('change', function() {
    questionCheckboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionsBar();
});

questionCheckboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkActionsBar);
});

function clearSelection() {
    questionCheckboxes.forEach(cb => cb.checked = false);
    selectAllCheckbox.checked = false;
    updateBulkActionsBar();
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
}

function getSelectedNames() {
    return Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.dataset.name);
}

function bulkAction(action) {
    const ids = getSelectedIds();
    const names = getSelectedNames();

    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select at least one question.', 'warning');
        return;
    }

    let title, text, confirmText, confirmColor, icon;

    switch (action) {
        case 'delete':
            title = 'Delete Questions?';
            text = `You are about to delete ${ids.length} question(s).<br><br><strong class="text-danger">This will also delete any sub-questions!</strong><br><br>This action cannot be undone.`;
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
            form.action = '{{ route("admin.questions.bulk-action") }}';

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
