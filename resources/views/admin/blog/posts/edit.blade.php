@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Blog Post')
@section('page-actions')
    <a href="{{ route('admin.blog.posts.index') }}" class="btn-modern btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Posts
    </a>
@endsection

@section('main')
<form action="{{ route('admin.blog.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xl-8">
            <x-card>
                <div class="mb-4">
                    <label for="title" class="form-label fw-medium">Post Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title', $post->title) }}"
                           placeholder="Enter an engaging title..."
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="form-label fw-medium">Slug</label>
                    <input type="text"
                           class="form-control @error('slug') is-invalid @enderror"
                           id="slug"
                           name="slug"
                           value="{{ old('slug', $post->slug) }}"
                           placeholder="auto-generated-from-title">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave empty to auto-generate from title</small>
                </div>

                <div class="mb-4">
                    <label for="excerpt" class="form-label fw-medium">Excerpt</label>
                    <textarea class="form-control @error('excerpt') is-invalid @enderror"
                              id="excerpt"
                              name="excerpt"
                              rows="3"
                              placeholder="Brief summary of the post (shown in listings)..."
                              maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Max 500 characters. Leave empty to auto-generate from content.</small>
                </div>

                <x-quill-editor
                    name="content"
                    label="Content"
                    placeholder="Write your blog post content here..."
                    height="400px"
                    :value="old('content', $post->content)"
                    required
                />

                <div class="mb-4">
                    <label for="featured_image" class="form-label fw-medium">Featured Image</label>

                    @if($post->featured_image)
                        <div class="mb-3">
                            <p class="text-muted small mb-2">Current Image:</p>
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 class="img-thumbnail"
                                 style="max-width: 300px; max-height: 200px; object-fit: cover;">
                        </div>
                    @endif

                    <input type="file"
                           class="form-control @error('featured_image') is-invalid @enderror"
                           id="featured_image"
                           name="featured_image"
                           accept="image/*"
                           onchange="previewImage(event)">
                    @error('featured_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Leave empty to keep current image. Recommended: 1200x630px (landscape). Max: 2MB.
                    </div>

                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <p class="text-muted small mb-2">New Image Preview:</p>
                        <img src="" alt="Preview" class="img-thumbnail" style="max-width: 400px; max-height: 250px; object-fit: cover;">
                    </div>
                </div>
            </x-card>

            <!-- SEO Settings -->
            <x-card title="SEO Settings" class="mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label fw-medium">Meta Title</label>
                            <input type="text"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title', $post->meta_title) }}"
                                   placeholder="SEO title (max 60 characters)"
                                   maxlength="60">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to use post title</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="focus_keywords" class="form-label fw-medium">Focus Keywords</label>
                            <input type="text"
                                   class="form-control @error('focus_keywords') is-invalid @enderror"
                                   id="focus_keywords"
                                   name="focus_keywords"
                                   value="{{ old('focus_keywords', $post->focus_keywords) }}"
                                   placeholder="e.g., tvet, revision, exam tips">
                            @error('focus_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Comma-separated keywords for SEO</small>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <label for="meta_description" class="form-label fw-medium">Meta Description</label>
                    <textarea class="form-control @error('meta_description') is-invalid @enderror"
                              id="meta_description"
                              name="meta_description"
                              rows="3"
                              placeholder="SEO description (max 160 characters)"
                              maxlength="160">{{ old('meta_description', $post->meta_description) }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave empty to use excerpt</small>
                </div>
            </x-card>
        </div>

        <div class="col-xl-4">
            <!-- Post Info -->
            <x-card title="Post Info" class="mb-4">
                <ul class="list-unstyled mb-0 small">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Author</span>
                        <span class="fw-medium">{{ $post->author->name ?? 'Unknown' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Created</span>
                        <span>{{ $post->created_at->format('M d, Y H:i') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Updated</span>
                        <span>{{ $post->updated_at->format('M d, Y H:i') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Views</span>
                        <span class="badge bg-secondary">{{ number_format($post->views_count) }}</span>
                    </li>
                </ul>
            </x-card>

            <!-- Danger Zone -->
            <x-card title="Danger Zone" class="mb-4 border-danger">
                <p class="text-muted small mb-3">Once you delete a post, there is no going back.</p>
                <button type="button" class="btn btn-outline-danger w-100 delete-btn" data-name="{{ $post->title }}">
                    <i class="bi bi-trash me-1"></i>Delete Post
                </button>
            </x-card>

            <!-- Publish Settings -->
            <x-card title="Publish Settings">
                <div class="mb-3">
                    <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status"
                            name="status"
                            required>
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="publishDateWrapper" style="{{ old('status', $post->status) == 'scheduled' ? '' : 'display: none;' }}">
                    <label for="published_at" class="form-label fw-medium">Publish Date</label>
                    <input type="datetime-local"
                           class="form-control @error('published_at') is-invalid @enderror"
                           id="published_at"
                           name="published_at"
                           value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label fw-medium">Category</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id"
                            name="category_id">
                        <option value="">No Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox"
                               class="form-check-input"
                               id="is_featured"
                               name="is_featured"
                               value="1"
                               {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            Featured Post
                        </label>
                    </div>
                    <small class="text-muted">Featured posts appear prominently on the blog page</small>
                </div>

                <div class="border-top pt-3">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Post
                        </button>
                        <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </x-card>

        </div>
    </div>
</form>

<!-- Hidden delete form -->
<form id="delete-form" action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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

// Show/hide publish date based on status
document.getElementById('status').addEventListener('change', function() {
    const publishDateWrapper = document.getElementById('publishDateWrapper');
    if (this.value === 'scheduled') {
        publishDateWrapper.style.display = 'block';
    } else {
        publishDateWrapper.style.display = 'none';
    }
});

// Delete confirmation
document.querySelector('.delete-btn').addEventListener('click', function(e) {
    e.preventDefault();
    const form = document.getElementById('delete-form');
    const name = this.dataset.name;

    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete the post <strong>"${name}"</strong>.<br><br>This action cannot be undone.`,
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
</script>
@endpush
@endsection
