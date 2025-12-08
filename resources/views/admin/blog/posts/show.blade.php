@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'View Blog Post')
@section('page-actions')
    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Posts
    </a>
    <a href="{{ route('admin.blog.posts.edit', $post) }}" class="btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Post
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card>
            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="img-fluid rounded mb-4"
                     style="max-height: 400px; width: 100%; object-fit: cover;">
            @endif

            <div class="d-flex align-items-center gap-2 mb-3">
                @if($post->status === 'published')
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Published</span>
                @elseif($post->status === 'scheduled')
                    <span class="badge bg-info"><i class="bi bi-clock me-1"></i>Scheduled</span>
                @else
                    <span class="badge bg-secondary"><i class="bi bi-pencil me-1"></i>Draft</span>
                @endif

                @if($post->is_featured)
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Featured</span>
                @endif

                @if($post->category)
                    <span class="badge bg-info">{{ $post->category->name }}</span>
                @endif
            </div>

            <h2 class="fw-bold mb-3">{{ $post->title }}</h2>

            <div class="d-flex align-items-center text-muted small mb-4">
                <span class="me-3">
                    <i class="bi bi-person me-1"></i>{{ $post->author->name ?? 'Unknown' }}
                </span>
                <span class="me-3">
                    <i class="bi bi-calendar me-1"></i>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                </span>
                <span class="me-3">
                    <i class="bi bi-eye me-1"></i>{{ number_format($post->views_count) }} views
                </span>
                <span>
                    <i class="bi bi-clock me-1"></i>{{ $post->reading_time }} min read
                </span>
            </div>

            @if($post->excerpt)
                <div class="lead text-muted mb-4 pb-4 border-bottom">
                    {{ $post->excerpt }}
                </div>
            @endif

            <div class="blog-content">
                {!! $post->content !!}
            </div>
        </x-card>
    </div>

    <div class="col-xl-4">
        <!-- Post Details -->
        <x-card title="Post Details">
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Status</span>
                    @if($post->status === 'published')
                        <span class="badge bg-success">Published</span>
                    @elseif($post->status === 'scheduled')
                        <span class="badge bg-info">Scheduled</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Author</span>
                    <span>{{ $post->author->name ?? 'Unknown' }}</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Category</span>
                    <span>{{ $post->category->name ?? '-' }}</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Views</span>
                    <span class="badge bg-primary rounded-pill">{{ number_format($post->views_count) }}</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Reading Time</span>
                    <span>{{ $post->reading_time }} min</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Featured</span>
                    @if($post->is_featured)
                        <span class="text-warning"><i class="bi bi-star-fill"></i> Yes</span>
                    @else
                        <span class="text-muted">No</span>
                    @endif
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Created</span>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Published</span>
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y H:i') : '-' }}</span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span class="text-muted">Last Updated</span>
                    <span>{{ $post->updated_at->format('M d, Y H:i') }}</span>
                </li>
            </ul>
        </x-card>

        <!-- SEO Info -->
        <x-card title="SEO Information" class="mt-4">
            <div class="mb-3">
                <label class="form-label text-muted small">Meta Title</label>
                <p class="mb-0">{{ $post->meta_title ?: $post->title }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small">Meta Description</label>
                <p class="mb-0 small">{{ $post->meta_description ?: $post->excerpt_or_content }}</p>
            </div>
            <div>
                <label class="form-label text-muted small">Slug</label>
                <p class="mb-0"><code>{{ $post->slug }}</code></p>
            </div>
        </x-card>

        <!-- Actions -->
        <x-card title="Actions" class="mt-4">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.blog.posts.edit', $post) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Post
                </a>
                @if($post->status === 'published')
                    <form action="{{ route('admin.blog.posts.unpublish', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-eye-slash me-2"></i>Unpublish
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.blog.posts.publish', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i>Publish Now
                        </button>
                    </form>
                @endif
                @if($post->isPublished())
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-2"></i>View on Site
                    </a>
                @endif
            </div>
        </x-card>
    </div>
</div>

@push('styles')
<style>
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .blog-content h2, .blog-content h3, .blog-content h4 {
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .blog-content p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }

    .blog-content ul, .blog-content ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .blog-content blockquote {
        border-left: 4px solid #667eea;
        padding-left: 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #6c757d;
    }
</style>
@endpush
@endsection
