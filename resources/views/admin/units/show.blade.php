@extends('layouts.admin')

@section('page-header', true)
@section('page-title')
    Unit {{ $unit->unit_number }}: {{ $unit->title }}
@endsection
@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.questions.index', ['unit' => $unit->id]) }}" class="btn-modern btn btn-outline-primary">
            <i class="bi bi-question-circle me-2"></i>View Questions
        </a>
        <a href="{{ route('admin.units.edit', $unit) }}" class="btn-modern btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit Unit
        </a>
    </div>
@endsection

@section('main')
<div class="row g-4">
    <div class="col-xl-8">
        <x-card title="Unit Information">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="150" class="text-muted">Course:</th>
                    <td>
                        <a href="{{ route('admin.courses.show', $unit->course) }}" class="text-decoration-none">
                            {{ $unit->course->title }}
                        </a>
                        <span class="badge bg-light text-dark ms-2">{{ $unit->course->code }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Unit Number:</th>
                    <td>{{ $unit->unit_number }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Display Order:</th>
                    <td>{{ $unit->order }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Created:</th>
                    <td>{{ $unit->created_at->format('F d, Y') }} <small class="text-muted">({{ $unit->created_at->diffForHumans() }})</small></td>
                </tr>
                <tr>
                    <th class="text-muted">Last Updated:</th>
                    <td>{{ $unit->updated_at->format('F d, Y') }} <small class="text-muted">({{ $unit->updated_at->diffForHumans() }})</small></td>
                </tr>
            </table>
        </x-card>

        @if($unit->description)
        <x-card title="Description" class="mt-4">
            <div class="unit-description">
                {!! $unit->description !!}
            </div>
        </x-card>
        @endif

        <!-- Questions Summary Card -->
        <x-card title="Questions" class="mt-4">
            <div class="text-center py-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-question-circle text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h2 class="mb-1">{{ $unit->questions->count() }}</h2>
                <p class="text-muted mb-4">Total Questions in this Unit</p>

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="fw-bold text-success fs-4">{{ $unit->questions->whereNotNull('answer_text')->count() }}</div>
                            <small class="text-muted">With Answers</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="fw-bold text-warning fs-4">{{ $unit->questions->whereNull('answer_text')->count() }}</div>
                            <small class="text-muted">No Answer</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="fw-bold text-info fs-4">{{ $unit->questions->where('ai_generated', true)->count() }}</div>
                            <small class="text-muted">AI Generated</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('admin.questions.index', ['unit' => $unit->id]) }}" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>View All Questions
                    </a>
                    <a href="{{ route('admin.questions.create', ['unit' => $unit->id]) }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Question
                    </a>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Quick Actions">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.questions.index', ['unit' => $unit->id]) }}" class="btn btn-primary">
                    <i class="bi bi-question-circle me-2"></i>View Questions ({{ $unit->questions->count() }})
                </a>
                <a href="{{ route('admin.questions.create', ['unit' => $unit->id]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add Question
                </a>
                <hr class="my-2">
                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-2"></i>Edit Unit
                </a>
                <a href="{{ route('admin.courses.show', $unit->course) }}" class="btn btn-outline-info">
                    <i class="bi bi-arrow-left me-2"></i>Back to Course
                </a>
            </div>
        </x-card>

        <x-card title="Course Details" class="mt-4">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded bg-primary bg-opacity-10 p-2 me-3">
                    <i class="bi bi-book text-primary"></i>
                </div>
                <div>
                    <div class="fw-medium">{{ $unit->course->title }}</div>
                    <small class="text-muted">{{ $unit->course->code }}</small>
                </div>
            </div>
            <div class="d-flex justify-content-between text-muted small">
                <span>Total Units in Course</span>
                <span class="fw-bold">{{ $unit->course->units->count() }}</span>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('styles')
<style>
.unit-description {
    line-height: 1.7;
}
.unit-description p {
    margin-bottom: 1rem;
}
.unit-description ul, .unit-description ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.unit-description h1, .unit-description h2, .unit-description h3,
.unit-description h4, .unit-description h5, .unit-description h6 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
</style>
@endpush
