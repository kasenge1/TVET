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
                    <select class="form-select form-select-lg @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $unit->course_id) == $course->id ? 'selected' : '' }}>
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
                            <option value="{{ $level->id }}" {{ old('level_id', $unit->level_id) == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Unit Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title', $unit->title) }}"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                <span class="text-muted">Level</span>
                <span class="fw-bold">{{ $unit->level->name ?? 'Not assigned' }}</span>
            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const levelSelect = document.getElementById('level_id');
    const currentLevelId = '{{ $unit->level_id }}';

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
                    if (level.id == currentLevelId) {
                        option.selected = true;
                    }
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
