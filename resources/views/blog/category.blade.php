@extends('layouts.frontend')

@section('title', $category->name . ' - Blog - TVET Revision')
@section('description', $category->description ?: 'Read articles about ' . $category->name . ' on TVET Revision Blog.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container position-relative">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-white opacity-75">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="lead opacity-90 mb-0">{{ $category->description }}</p>
                @endif
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <span class="badge bg-primary me-2">{{ $posts->total() }}</span>
                        Article{{ $posts->total() !== 1 ? 's' : '' }}
                    </h4>
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>All Articles
                    </a>
                </div>

                @if($posts->count() > 0)
                    <div class="row g-4">
                        @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
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
                                    @if($post->is_featured)
                                        <span class="badge bg-warning text-dark mb-2"><i class="bi bi-star-fill me-1"></i>Featured</span>
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
                            {{ $posts->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-newspaper display-1 text-muted opacity-50"></i>
                        <h5 class="text-muted mt-3">No articles in this category yet</h5>
                        <p class="text-muted">Check back soon for new content</p>
                        <a href="{{ route('blog.index') }}" class="btn btn-primary">View All Articles</a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
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

                <!-- Categories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="bi bi-folder me-2"></i>Categories</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('blog.index') }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                All Articles
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('blog.category', $cat->slug) }}"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $category->id === $cat->id ? 'active' : '' }}">
                                    {{ $cat->name }}
                                    <span class="badge bg-{{ $category->id === $cat->id ? 'light text-primary' : 'secondary' }} rounded-pill">{{ $cat->published_posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-mortarboard display-4 mb-3"></i>
                        <h5 class="fw-bold mb-2">Ready to Study?</h5>
                        <p class="opacity-90 small mb-3">Access thousands of TVET past exam questions and ace your exams.</p>
                        @auth
                            <a href="{{ route('learn.index') }}" class="btn btn-light w-100">
                                <i class="bi bi-arrow-right me-2"></i>Start Learning
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light w-100">
                                <i class="bi bi-person-plus me-2"></i>Get Started Free
                            </a>
                        @endauth
                    </div>
                </div>
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

    @media (max-width: 767.98px) {
        .display-5 {
            font-size: 1.75rem;
        }

        .card-title {
            font-size: 0.95rem;
        }
    }
</style>
@endpush
