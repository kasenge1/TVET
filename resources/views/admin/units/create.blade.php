@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create New Unit')
@section('page-actions')
    <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Units
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.units.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="course_id" class="form-label fw-medium">Course <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg" id="course_id" name="course_id" required>
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $selectedCourse?->id) == $course->id ? 'selected' : '' }}>
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
                           value="{{ old('title') }}"
                           placeholder="e.g., Introduction to Electrical Theory"
                           required>
                    <small class="text-muted">Unit number will be assigned automatically</small>
                </div>

                <x-quill-editor
                    name="description"
                    label="Description"
                    placeholder="Brief description of what students will learn"
                    height="250px"
                />

                <div class="mb-4">
                    <label class="form-label fw-medium">Exam Period (Optional)</label>
                    <p class="text-muted small mb-2">When was this exam paper administered?</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <select class="form-select" id="exam_month" name="exam_month">
                                <option value="">Select Month</option>
                                @foreach(\App\Models\Unit::MONTHS as $num => $name)
                                    <option value="{{ $num }}" {{ old('exam_month') == $num ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="exam_year" name="exam_year">
                                <option value="">Select Year</option>
                                @for($year = date('Y'); $year >= 2010; $year--)
                                    <option value="{{ $year }}" {{ old('exam_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Unit
                        </button>
                        <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Tips">
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Course:</strong> Select the course this unit belongs to
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Title:</strong> Be clear and descriptive about the topic
                </li>
                <li>
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Description:</strong> Explain what students will learn in this unit
                </li>
            </ul>
        </x-card>

        @if($selectedCourse)
            <x-card title="Selected Course" class="mt-4">
                <h6>{{ $selectedCourse->title }}</h6>
                <p class="text-muted mb-0">{{ $selectedCourse->code }}</p>
            </x-card>
        @endif
    </div>
</div>
@endsection
