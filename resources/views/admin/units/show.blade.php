@extends('layouts.admin')

@section('page-header', true)
@section('page-title')
    Unit {{ $unit->unit_number }}: {{ $unit->title }}
@endsection
@section('page-actions')
    <a href="{{ route('admin.units.edit', $unit) }}" class="btn-modern btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Unit
    </a>
@endsection

@section('main')
<div class="row g-4">
    <div class="col-xl-8">
        <x-card title="Unit Information">
            <table class="table table-borderless">
                <tr>
                    <th width="150">Course:</th>
                    <td>
                        <a href="{{ route('admin.courses.show', $unit->course) }}">
                            {{ $unit->course->title }}
                        </a>
                        <span class="badge-modern badge bg-light text-dark ms-2">{{ $unit->course->code }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Unit Number:</th>
                    <td>{{ $unit->unit_number }}</td>
                </tr>
                <tr>
                    <th>Display Order:</th>
                    <td>{{ $unit->order }}</td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td>{{ $unit->description ?: 'No description provided' }}</td>
                </tr>
                <tr>
                    <th>Created:</th>
                    <td>{{ $unit->created_at->format('F d, Y') }}</td>
                </tr>
            </table>
        </x-card>

        <x-card title="Questions" class="mt-4">
            @if($unit->questions->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($unit->questions as $question)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge-modern badge bg-primary me-2">Q{{ $question->question_number }}</span>
                                        @if($question->ai_generated)
                                            <span class="badge-modern badge bg-info">AI</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">{{ Str::limit($question->question_text, 100) }}</div>
                                    @if($question->answer_text)
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> Answer provided
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            <i class="bi bi-x-circle"></i> No answer yet
                                        </small>
                                    @endif
                                </div>
                                <div class="btn-group-modern btn-group btn-group-sm">
                                    <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-question-circle display-3 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No questions yet</h5>
                    <p class="text-muted mb-3">Add questions to this unit</p>
                    <a href="{{ route('admin.questions.create', ['unit' => $unit->id]) }}" class="btn-modern btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add First Question
                    </a>
                </div>
            @endif
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Statistics">
            <div class="d-flex justify-content-between mb-3">
                <span>Total Questions</span>
                <span class="fw-bold">{{ $unit->questions->count() }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span>With Answers</span>
                <span class="fw-bold">{{ $unit->questions->where('answer_text', '!=', null)->count() }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>AI Generated</span>
                <span class="fw-bold">{{ $unit->questions->where('ai_generated', true)->count() }}</span>
            </div>
        </x-card>

        <x-card title="Quick Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.questions.create', ['unit' => $unit->id]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add Question
                </a>
                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-2"></i>Edit Unit
                </a>
                <a href="{{ route('admin.courses.show', $unit->course) }}" class="btn btn-outline-info">
                    <i class="bi bi-arrow-left me-2"></i>Back to Course
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
