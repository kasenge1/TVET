@extends('layouts.frontend')

@section('title', ($post->meta_title ?: $post->title) . ' - TVET Revision Blog')
@section('description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt_or_content), 160))
@section('keywords', $post->focus_keywords ?: 'TVET, KNEC, ' . $post->category?->name)

@section('og_type', 'article')
@section('og_title', $post->meta_title ?: $post->title)
@section('og_description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt_or_content), 160))
@section('og_image', $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/og-default.png'))

@section('twitter_title', $post->meta_title ?: $post->title)
@section('twitter_description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt_or_content), 160))
@section('twitter_image', $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/og-default.png'))

@section('canonical', route('blog.show', $post->slug))

@php
$blogPostSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->meta_description ?: Str::limit(strip_tags($post->excerpt_or_content), 160),
    'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/og-default.png'),
    'author' => [
        '@type' => 'Person',
        'name' => $post->author->name ?? 'TVET Revision'
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'TVET Revision',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png')
        ]
    ],
    'datePublished' => $post->published_at->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('blog.show', $post->slug)
    ]
];
if ($post->category) {
    $blogPostSchema['articleSection'] = $post->category->name;
}
@endphp

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($blogPostSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-4">
    <div class="container position-relative">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-white opacity-75">Blog</a></li>
                @if($post->category)
                    <li class="breadcrumb-item"><a href="{{ route('blog.category', $post->category->slug) }}" class="text-white opacity-75">{{ $post->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active text-white" aria-current="page">{{ Str::limit($post->title, 30) }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Article Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                             class="card-img-top"
                             alt="{{ $post->title }}"
                             style="max-height: 450px; object-fit: cover;">
                    @endif

                    <div class="card-body p-4 p-md-5">
                        <!-- Category & Meta -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($post->category)
                                <a href="{{ route('blog.category', $post->category->slug) }}"
                                   class="badge bg-primary text-decoration-none">{{ $post->category->name }}</a>
                            @endif
                            @if($post->is_featured)
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Featured</span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="fw-bold mb-3">{{ $post->title }}</h1>

                        <!-- Post Meta -->
                        <div class="d-flex flex-wrap align-items-center text-muted mb-4 pb-4 border-bottom gap-3">
                            <div>
                                <i class="bi bi-calendar me-1"></i>
                                {{ $post->published_at->format('F d, Y') }}
                            </div>
                            <div>
                                <i class="bi bi-clock me-1"></i>
                                {{ $post->reading_time }} min read
                            </div>
                            <div>
                                <i class="bi bi-eye me-1"></i>
                                {{ number_format($post->views_count) }} views
                            </div>
                        </div>

                        <!-- Excerpt -->
                        @if($post->excerpt)
                            <div class="lead text-muted mb-4">
                                {{ $post->excerpt }}
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="blog-content">
                            {!! $post->content !!}
                        </div>

                        <!-- Share Buttons -->
                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-bold mb-3">Share this article</h6>
                            <div class="d-flex gap-2">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                                   class="btn btn-outline-primary btn-sm"
                                   target="_blank"
                                   rel="noopener">
                                    <i class="bi bi-twitter-x me-1"></i>Twitter
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                                   class="btn btn-outline-primary btn-sm"
                                   target="_blank"
                                   rel="noopener">
                                    <i class="bi bi-facebook me-1"></i>Facebook
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}&title={{ urlencode($post->title) }}"
                                   class="btn btn-outline-primary btn-sm"
                                   target="_blank"
                                   rel="noopener">
                                    <i class="bi bi-linkedin me-1"></i>LinkedIn
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}"
                                   class="btn btn-outline-success btn-sm"
                                   target="_blank"
                                   rel="noopener">
                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                    <div class="mt-5">
                        <h4 class="fw-bold mb-4">Related Articles</h4>
                        <div class="row g-4">
                            @foreach($relatedPosts as $related)
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm hover-lift">
                                        @if($related->featured_image)
                                            <img src="{{ asset('storage/' . $related->featured_image) }}"
                                                 class="card-img-top"
                                                 alt="{{ $related->title }}"
                                                 style="height: 140px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                                <i class="bi bi-file-earmark-text text-muted display-5"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold mb-2">
                                                <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none text-dark stretched-link">
                                                    {{ Str::limit($related->title, 50) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $related->published_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
                            @foreach($categories as $category)
                                <a href="{{ route('blog.category', $category->slug) }}"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $post->category && $post->category->id === $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                    <span class="badge bg-secondary rounded-pill">{{ $category->published_posts_count }}</span>
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

                <!-- Back to Blog -->
                <div class="mt-4">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to Blog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .blog-content {
        font-size: 1.05rem;
        line-height: 1.8;
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    .blog-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .blog-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .blog-content h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .blog-content p {
        margin-bottom: 1.25rem;
    }

    .blog-content ul, .blog-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }

    .blog-content li {
        margin-bottom: 0.5rem;
    }

    .blog-content blockquote {
        border-left: 4px solid #0d6efd;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background-color: #f8f9fa;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #495057;
    }

    .blog-content pre {
        background-color: #1e293b;
        color: #e2e8f0;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .blog-content code {
        background-color: #f1f5f9;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .blog-content pre code {
        background-color: transparent;
        padding: 0;
    }

    .blog-content table {
        width: 100%;
        margin-bottom: 1.5rem;
        border-collapse: collapse;
    }

    .blog-content table th,
    .blog-content table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }

    .blog-content table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    @media (max-width: 767.98px) {
        .blog-content {
            font-size: 0.95rem;
        }

        .blog-content h2 {
            font-size: 1.25rem;
        }

        .blog-content h3 {
            font-size: 1.1rem;
        }

        h1.fw-bold {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
