@extends('layouts.frontend')

@section('title', $category->name . ' - Blog - TVET Revision')
@section('description', $category->description ?: 'Read articles about ' . $category->name . ' on TVET Revision Blog.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb fe-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>
        <div class="text-center" style="padding: 1.5rem 0 2rem;">
            <h1 class="fe-hero-title" style="font-size: 2.25rem;">{{ $category->name }}</h1>
            @if($category->description)
                <p class="fe-hero-subtitle mx-auto">{{ $category->description }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="fe-section">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <!-- Posts Grid -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0" style="font-size: 1.1rem;">
                        <span class="badge rounded-pill me-2" style="background: var(--fe-primary);">{{ $posts->total() }}</span>
                        Article{{ $posts->total() !== 1 ? 's' : '' }}
                    </h4>
                    <a href="{{ route('blog.index') }}" class="fe-btn" style="background: var(--fe-bg); color: var(--fe-text-secondary); font-size: 0.8rem; padding: 0.4rem 0.8rem; border: 1px solid var(--fe-border);">
                        <i class="bi bi-arrow-left me-1"></i>All Articles
                    </a>
                </div>

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
                                    <h6 class="fe-card-title mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none" style="color: var(--fe-text);">{{ Str::limit($post->title, 55) }}</a>
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
                        <h5>No articles in this category yet</h5>
                        <p>Check back soon for new content</p>
                        <a href="{{ route('blog.index') }}" class="fe-btn fe-btn-primary">View All Articles</a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
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

                <!-- Categories -->
                <div class="fe-blog-sidebar-card">
                    <h5 class="fe-blog-sidebar-title"><i class="bi bi-folder"></i> Categories</h5>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('blog.index') }}" class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none" style="color: var(--fe-text-secondary); font-size: 0.9rem;">
                            All Articles
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.category', $cat->slug) }}" class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none" style="background: {{ $category->id === $cat->id ? 'var(--fe-primary-light)' : 'transparent' }}; color: {{ $category->id === $cat->id ? 'var(--fe-primary)' : 'var(--fe-text-secondary)' }}; font-size: 0.9rem; font-weight: {{ $category->id === $cat->id ? '600' : '400' }};">
                                {{ $cat->name }}
                                <span class="badge rounded-pill" style="background: {{ $category->id === $cat->id ? 'var(--fe-primary)' : 'var(--fe-border)' }}; color: {{ $category->id === $cat->id ? '#fff' : 'var(--fe-text-secondary)' }}; font-size: 0.7rem;">{{ $cat->published_posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- CTA -->
                <div class="fe-cta-card">
                    <i class="bi bi-mortarboard display-4 mb-3 d-block" style="opacity: 0.9;"></i>
                    <h5 class="fw-bold mb-2">Ready to Study?</h5>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.8); margin-bottom: 1rem;">Access thousands of TVET past exam questions and ace your exams.</p>
                    @auth
                        <a href="{{ route('learn.index') }}" class="fe-btn fe-btn-white w-100">
                            <i class="bi bi-arrow-right me-2"></i>Start Learning
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="fe-btn fe-btn-white w-100">
                            <i class="bi bi-person-plus me-2"></i>Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
