@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Course')
@section('page-actions')
    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Courses
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Course Title <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $course->title) }}" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="code" class="form-label fw-medium">Course Code</label>
                    <input type="text"
                           class="form-control"
                           id="code"
                           name="code"
                           value="{{ old('code', $course->code) }}">
                    <small class="text-muted">Optional unique identifier for the course</small>
                </div>

                <x-quill-editor
                    name="description"
                    label="Description"
                    :value="$course->description"
                    placeholder="Brief description of the course"
                    height="250px"
                />


                <div class="mb-4">
                    <label for="thumbnail" class="form-label fw-medium">Course Thumbnail</label>

                    @if($course->thumbnail_url)
                        <div class="mb-3">
                            <p class="text-muted small mb-2">Current thumbnail (as displayed on cards):</p>
                            <img src="{{ asset('storage/' . $course->thumbnail_url) }}"
                                 alt="{{ $course->title }}"
                                 class="img-thumbnail"
                                 style="width: 300px; height: 180px; object-fit: cover;">
                        </div>
                    @endif

                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" onchange="previewImage(event)">
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Recommended:</strong> 800x450px (16:9 ratio) or 600x400px (3:2 ratio). Max: 2MB.
                        <br>
                        <small class="text-muted">Images will be automatically cropped to fit the card display. Use landscape orientation for best results. Leave empty to keep current thumbnail.</small>
                    </div>

                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <p class="text-muted small mb-2">New thumbnail preview:</p>
                        <img src="" alt="Preview" class="img-thumbnail" style="width: 300px; height: 180px; object-fit: cover;">
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Course
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Course Statistics">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Units</span>
                <span class="fw-bold">{{ $course->units()->count() }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Enrollments</span>
                <span class="fw-bold">{{ $course->enrollments()->count() }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Status</span>
                <span>
                    @if($course->is_published)
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Created</span>
                <span>{{ $course->created_at->format('M d, Y') }}</span>
            </div>
        </x-card>

        <x-card title="Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-2"></i>View Course
                </a>
                @if($course->is_published)
                    <form action="{{ route('admin.courses.unpublish', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning w-100">
                            <i class="bi bi-eye-slash me-2"></i>Unpublish Course
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.courses.publish', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="bi bi-check-circle me-2"></i>Publish Course
                        </button>
                    </form>
                @endif
            </div>
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
