@extends('layouts.frontend')

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . 'Blog - TVET Revision')
@section('description', 'Read helpful articles about TVET education, study tips, exam preparation strategies, and career guidance.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container text-center" style="padding: 3.5rem 0;">
        <h1 class="fe-hero-title" style="font-size: 2.5rem;">
            @if($currentCategory)
                {{ $currentCategory->name }}
            @else
                TVET Revision Blog
            @endif
        </h1>
        <p class="fe-hero-subtitle mb-4 mx-auto" style="max-width: 550px;">
            @if($currentCategory && $currentCategory->description)
                {{ $currentCategory->description }}
            @else
                Study tips, exam strategies, career guidance, and insights for TVET students
            @endif
        </p>

        <!-- Search Form -->
        <form action="{{ route('blog.index') }}" method="GET" class="row g-2 justify-content-center">
            <div class="col-md-6">
                <div class="position-relative">
                    <input type="text" name="search" class="fe-search-input w-100" style="padding-right: 3rem; background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.2); color: #fff;" placeholder="Search articles..." value="{{ request('search') }}">
                    <button type="submit" class="position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: rgba(255,255,255,0.7); cursor: pointer;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Main Content -->
<section class="fe-section">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <!-- Posts Grid -->
            <div class="col-lg-8">
                @if(request('search'))
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0" style="font-size: 1.1rem;">
                            Search results for "{{ request('search') }}"
                            <span class="badge rounded-pill ms-2" style="background: var(--fe-primary-light); color: var(--fe-primary);">{{ $posts->total() }}</span>
                        </h4>
                        <a href="{{ route('blog.index') }}" class="fe-btn" style="background: var(--fe-bg); color: var(--fe-text-secondary); font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                            <i class="bi bi-x-lg me-1"></i>Clear
                        </a>
                    </div>
                @elseif(!$currentCategory)
                    <h4 class="fw-bold mb-4" style="font-size: 1.25rem;">Latest Articles</h4>
                @endif

                @if($posts->count() > 0)
                    <div class="row g-4">
                        @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="fe-blog-card">
                                @if($post->is_featured)
                                    <div class="fe-featured-ribbon">Featured</div>
                                @endif
                                <div class="fe-blog-card-img">
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100" style="background: var(--fe-bg);">
                                            <i class="bi bi-file-earmark-text" style="font-size: 2.5rem; color: var(--fe-text-muted);"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="fe-blog-card-body">
                                    @if($post->category)
                                        <span class="badge rounded-pill mb-2" style="background: #f0fdfa; color: #0d9488; font-size: 0.7rem;">{{ $post->category->name }}</span>
                                    @endif
                                    <h6 class="fe-card-title mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none" style="color: var(--fe-text);">
                                            {{ Str::limit($post->title, 55) }}
                                        </a>
                                    </h6>
                                    <p class="fe-card-desc mb-3">{{ Str::limit($post->excerpt_or_content, 80) }}</p>
                                    <div style="font-size: 0.75rem; color: var(--fe-text-muted); margin-top: auto;">
                                        <i class="bi bi-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($posts->hasPages())
                        <div class="mt-5 d-flex justify-content-center">
                            <nav class="fe-pagination">{{ $posts->withQueryString()->links() }}</nav>
                        </div>
                    @endif
                @else
                    <div class="fe-empty-state">
                        <i class="bi bi-newspaper d-block"></i>
                        <h5>No articles found</h5>
                        <p>
                            @if(request('search'))
                                Try a different search term
                            @else
                                Check back soon for new content
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('blog.index') }}" class="fe-btn fe-btn-primary">View All Articles</a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories -->
                <div class="fe-blog-sidebar-card">
                    <h5 class="fe-blog-sidebar-title"><i class="bi bi-folder"></i> Categories</h5>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('blog.index') }}" class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none" style="background: {{ !$currentCategory ? 'var(--fe-primary-light)' : 'transparent' }}; color: {{ !$currentCategory ? 'var(--fe-primary)' : 'var(--fe-text-secondary)' }}; font-size: 0.9rem; font-weight: {{ !$currentCategory ? '600' : '400' }};">
                            All Articles
                            <span class="badge rounded-pill" style="background: {{ !$currentCategory ? 'var(--fe-primary)' : 'var(--fe-border)' }}; color: {{ !$currentCategory ? '#fff' : 'var(--fe-text-secondary)' }}; font-size: 0.7rem;">{{ $posts->total() }}</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}" class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none" style="background: {{ $currentCategory && $currentCategory->id === $category->id ? 'var(--fe-primary-light)' : 'transparent' }}; color: {{ $currentCategory && $currentCategory->id === $category->id ? 'var(--fe-primary)' : 'var(--fe-text-secondary)' }}; font-size: 0.9rem; font-weight: {{ $currentCategory && $currentCategory->id === $category->id ? '600' : '400' }};">
                                {{ $category->name }}
                                <span class="badge rounded-pill" style="background: {{ $currentCategory && $currentCategory->id === $category->id ? 'var(--fe-primary)' : 'var(--fe-border)' }}; color: {{ $currentCategory && $currentCategory->id === $category->id ? '#fff' : 'var(--fe-text-secondary)' }}; font-size: 0.7rem;">{{ $category->published_posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Posts -->
                @if($popularPosts->count() > 0)
                <div class="fe-blog-sidebar-card">
                    <h5 class="fe-blog-sidebar-title"><i class="bi bi-fire" style="color: var(--fe-danger);"></i> Popular Posts</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($popularPosts as $popular)
                        <a href="{{ route('blog.show', $popular->slug) }}" class="text-decoration-none">
                            <div class="d-flex gap-3 align-items-start">
                                @if($popular->featured_image)
                                    <img src="{{ asset('storage/' . $popular->featured_image) }}" class="flex-shrink-0" width="56" height="44" alt="{{ $popular->title }}" style="object-fit: cover; border-radius: 6px;">
                                @else
                                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 56px; height: 44px; background: var(--fe-bg); border-radius: 6px;">
                                        <i class="bi bi-file-text" style="color: var(--fe-text-muted);"></i>
                                    </div>
                                @endif
                                <div style="min-width: 0;">
                                    <h6 class="mb-1 fw-semibold" style="line-height: 1.3; font-size: 0.85rem; color: var(--fe-text);">{{ Str::limit($popular->title, 45) }}</h6>
                                    <small style="color: var(--fe-text-muted); font-size: 0.7rem;"><i class="bi bi-eye me-1"></i>{{ number_format($popular->views_count) }} views</small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
