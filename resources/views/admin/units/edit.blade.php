@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Unit')
@section('page-actions')
    <a href="{{ route('admin.units.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Units
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.units.update', $unit) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="course_id" class="form-label fw-medium">Course <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg" id="course_id" name="course_id" required>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $unit->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Unit Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg"
                           id="title"
                           name="title"
                           value="{{ old('title', $unit->title) }}"
                           required>
                </div>

                <x-quill-editor
                    name="description"
                    label="Description"
                    :value="$unit->description"
                    placeholder="Brief description of what students will learn"
                    height="250px"
                />


                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Unit
                        </button>
                        <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Unit Statistics">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Questions</span>
                <span class="fw-bold">{{ $unit->questions()->count() }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Created</span>
                <span>{{ $unit->created_at->format('M d, Y') }}</span>
            </div>
        </x-card>

        <x-card title="Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-2"></i>View Unit
                </a>
                <a href="{{ route('admin.questions.create', ['unit' => $unit->id]) }}" class="btn btn-outline-success">
                    <i class="bi bi-plus-circle me-2"></i>Add Question
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
