@extends('layouts.frontend')

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . 'Blog - TVET Revision')
@section('description', 'Read helpful articles about TVET education, study tips, exam preparation strategies, and career guidance.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">
                    @if($currentCategory)
                        {{ $currentCategory->name }}
                    @else
                        TVET Revision Blog
                    @endif
                </h1>
                <p class="lead opacity-90 mb-4">
                    @if($currentCategory && $currentCategory->description)
                        {{ $currentCategory->description }}
                    @else
                        Study tips, exam strategies, career guidance, and insights for TVET students
                    @endif
                </p>

                <!-- Search Form -->
                <form action="{{ route('blog.index') }}" method="GET" class="row g-2 justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-lg"
                                   placeholder="Search articles..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-light btn-lg" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Posts Grid -->
            <div class="col-lg-8">
                @if(request('search'))
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            Search results for "{{ request('search') }}"
                            <span class="badge bg-secondary ms-2">{{ $posts->total() }}</span>
                        </h4>
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg me-1"></i>Clear
                        </a>
                    </div>
                @elseif(!$currentCategory)
                    <h4 class="fw-bold mb-4">Latest Articles</h4>
                @endif

                @if($posts->count() > 0)
                    <div class="row g-4">
                        @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift position-relative">
                                @if($post->is_featured)
                                    <div class="featured-ribbon">Featured</div>
                                @endif
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                         class="card-img-top"
                                         alt="{{ $post->title }}"
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="bi bi-file-earmark-text text-muted display-4"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    @if($post->category)
                                        <span class="badge bg-info bg-opacity-10 text-info mb-2">{{ $post->category->name }}</span>
                                    @endif
                                    <h6 class="card-title fw-bold mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark stretched-link">
                                            {{ Str::limit($post->title, 55) }}
                                        </a>
                                    </h6>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($post->excerpt_or_content, 80) }}</p>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($posts->hasPages())
                        <div class="mt-5">
                            {{ $posts->withQueryString()->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-newspaper display-1 text-muted opacity-50"></i>
                        <h5 class="text-muted mt-3">No articles found</h5>
                        <p class="text-muted">
                            @if(request('search'))
                                Try a different search term
                            @else
                                Check back soon for new content
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('blog.index') }}" class="btn btn-primary">View All Articles</a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="bi bi-folder me-2"></i>Categories</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('blog.index') }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !$currentCategory ? 'active' : '' }}">
                                All Articles
                                <span class="badge bg-primary rounded-pill">{{ $posts->total() }}</span>
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('blog.category', $category->slug) }}"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $currentCategory && $currentCategory->id === $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                    <span class="badge bg-secondary rounded-pill">{{ $category->published_posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Popular Posts -->
                @if($popularPosts->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="bi bi-fire text-danger me-2"></i>Popular Posts</h5>
                        <div class="d-flex flex-column gap-3">
                            @foreach($popularPosts as $popular)
                            <a href="{{ route('blog.show', $popular->slug) }}" class="text-decoration-none">
                                <div class="d-flex gap-3 align-items-start">
                                    @if($popular->featured_image)
                                        <img src="{{ asset('storage/' . $popular->featured_image) }}"
                                             class="rounded flex-shrink-0"
                                             width="60"
                                             height="50"
                                             alt="{{ $popular->title }}"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width: 60px; height: 50px;">
                                            <i class="bi bi-file-text text-muted"></i>
                                        </div>
                                    @endif
                                    <div style="min-width: 0;">
                                        <h6 class="mb-1 text-dark fw-medium" style="line-height: 1.3;">{{ Str::limit($popular->title, 45) }}</h6>
                                        <small class="text-muted"><i class="bi bi-eye me-1"></i>{{ number_format($popular->views_count) }} views</small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Featured Ribbon */
    .featured-ribbon {
        position: absolute;
        top: 12px;
        right: -8px;
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #000;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 12px;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        border-radius: 3px 0 0 3px;
    }

    .featured-ribbon::after {
        content: '';
        position: absolute;
        right: 0;
        bottom: -8px;
        border-top: 8px solid #b86e00;
        border-right: 8px solid transparent;
    }

    @media (max-width: 767.98px) {
        .display-5 {
            font-size: 1.75rem;
        }

        .lead {
            font-size: 0.95rem;
        }

        .card-title {
            font-size: 0.95rem;
        }

        .card-text {
            font-size: 0.8rem;
        }

        .featured-ribbon {
            font-size: 0.6rem;
            padding: 3px 8px;
        }
    }
</style>
@endpush
