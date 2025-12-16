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
                    <select class="form-select form-select-lg @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $selectedCourse?->id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="level_id" class="form-label fw-medium">Level <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg @error('level_id') is-invalid @enderror" id="level_id" name="level_id" required>
                        <option value="">Select a level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id', $selectedLevel?->id) == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Levels are loaded based on the selected course</small>
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
                    <strong>Level:</strong> Select the level within the course (e.g., Level 3, Level 4)
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Title:</strong> Be clear and descriptive about the topic
                </li>
                <li>
                    <i class="bi bi-info-circle text-info me-2"></i>
                    <strong>Note:</strong> Exam period is set when adding questions, not at unit level
                </li>
            </ul>
        </x-card>

        @if($selectedCourse)
            <x-card title="Selected Course" class="mt-4">
                <h6>{{ $selectedCourse->title }}</h6>
                @if($selectedLevel)
                    <p class="text-muted mb-0">{{ $selectedLevel->name }}</p>
                @endif
            </x-card>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const levelSelect = document.getElementById('level_id');

    courseSelect.addEventListener('change', function() {
        const courseId = this.value;
        levelSelect.innerHTML = '<option value="">Loading...</option>';

        if (!courseId) {
            levelSelect.innerHTML = '<option value="">Select a level</option>';
            return;
        }

        fetch('/admin/api/courses/' + courseId + '/levels')
            .then(response => response.json())
            .then(levels => {
                levelSelect.innerHTML = '<option value="">Select a level</option>';
                levels.forEach(level => {
                    const option = document.createElement('option');
                    option.value = level.id;
                    option.textContent = level.name;
                    levelSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading levels:', error);
                levelSelect.innerHTML = '<option value="">Error loading levels</option>';
            });
    });
});
</script>
@endpush
