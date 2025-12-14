@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create New Course')
@section('page-actions')
    <a href="{{ route('admin.courses.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Courses
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Course Title <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control form-control-lg @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           placeholder="e.g., Electrical Installation Level 4"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="code" class="form-label fw-medium">Course Code</label>
                    <input type="text"
                           class="form-control @error('code') is-invalid @enderror"
                           id="code"
                           name="code"
                           value="{{ old('code') }}"
                           placeholder="e.g., EI-L4">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Optional unique identifier for the course</small>
                </div>

                <x-quill-editor
                    name="description"
                    label="Description"
                    placeholder="Brief description of the course"
                    height="250px"
                />


                <div class="mb-4">
                    <label for="thumbnail" class="form-label fw-medium">Course Thumbnail</label>
                    <input type="file"
                           class="form-control @error('thumbnail') is-invalid @enderror"
                           id="thumbnail"
                           name="thumbnail"
                           accept="image/*"
                           onchange="previewImage(event)">
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Recommended:</strong> 800x450px (16:9 ratio) or 600x400px (3:2 ratio). Max: 2MB.
                        <br>
                        <small class="text-muted">Images will be automatically cropped to fit the card display. Use landscape orientation for best results.</small>
                    </div>

                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <p class="text-muted small mb-2">Preview (how it will appear on course cards):</p>
                        <img src="" alt="Preview" class="img-thumbnail" style="width: 300px; height: 180px; object-fit: cover;">
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Course
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
                    <strong>Course Title:</strong> Use clear, descriptive names
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Course Code:</strong> Keep it short and memorable
                </li>
                <li class="mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Description:</strong> Highlight key topics covered
                </li>
                <li>
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    <strong>Thumbnail:</strong> Use high-quality, relevant images
                </li>
            </ul>
        </x-card>

        <x-card title="Next Steps" class="mt-4">
            <ol class="mb-0 ps-3">
                <li class="mb-2">Create the course</li>
                <li class="mb-2">Add units to organize content</li>
                <li class="mb-2">Add questions to each unit</li>
                <li>Publish when ready</li>
            </ol>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
