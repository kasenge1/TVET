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
<!-- Breadcrumb -->
<section class="fe-page-hero text-white" style="padding: 1.5rem 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb fe-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                @if($post->category)
                    <li class="breadcrumb-item"><a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Article Content -->
<section class="fe-section">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="fe-sidebar-card" style="padding: 0; overflow: hidden;">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-100" alt="{{ $post->title }}" style="max-height: 400px; object-fit: cover;">
                    @endif

                    <div style="padding: 2rem;">
                        <!-- Category & Meta -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($post->category)
                                <span class="badge rounded-pill" style="background: var(--fe-primary-light); color: var(--fe-primary); font-size: 0.7rem;">{{ $post->category->name }}</span>
                            @endif
                            @if($post->is_featured)
                                <span class="badge rounded-pill" style="background: #fffbeb; color: #d97706; font-size: 0.7rem;"><i class="bi bi-star-fill me-1"></i>Featured</span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="fw-bold mb-3" style="font-size: 1.75rem; letter-spacing: -0.02em;">{{ $post->title }}</h1>

                        <!-- Post Meta -->
                        <div class="d-flex flex-wrap align-items-center mb-4 pb-4 gap-3" style="color: var(--fe-text-muted); font-size: 0.85rem; border-bottom: 1px solid var(--fe-border);">
                            <span><i class="bi bi-calendar me-1"></i>{{ $post->published_at->format('F d, Y') }}</span>
                            <span><i class="bi bi-clock me-1"></i>{{ $post->reading_time }} min read</span>
                            <span><i class="bi bi-eye me-1"></i>{{ number_format($post->views_count) }} views</span>
                        </div>

                        <!-- Excerpt -->
                        @if($post->excerpt)
                            <div style="font-size: 1.05rem; color: var(--fe-text-secondary); line-height: 1.8; margin-bottom: 1.5rem; font-style: italic;">
                                {{ $post->excerpt }}
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="blog-content">
                            {!! $post->content !!}
                        </div>

                        <!-- Share Buttons -->
                        <div style="border-top: 1px solid var(--fe-border); padding-top: 1.5rem; margin-top: 2rem;">
                            <h6 class="fw-bold mb-3" style="font-size: 0.9rem;">Share this article</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" class="fe-social-icon" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" class="fe-social-icon" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}&title={{ urlencode($post->title) }}" class="fe-social-icon" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}" class="fe-social-icon" target="_blank" rel="noopener" style="border-color: #25d366; color: #25d366;"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                    <div class="mt-5">
                        <h4 class="fw-bold mb-4" style="font-size: 1.2rem;">Related Articles</h4>
                        <div class="row g-4">
                            @foreach($relatedPosts as $related)
                                <div class="col-md-4">
                                    <div class="fe-card">
                                        <div class="fe-card-img">
                                            @if($related->featured_image)
                                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}">
                                            @else
                                                <div class="fe-card-img-placeholder"><i class="bi bi-file-earmark-text"></i></div>
                                            @endif
                                        </div>
                                        <div class="fe-card-body">
                                            <h6 class="fe-card-title">
                                                <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none" style="color: var(--fe-text);">{{ Str::limit($related->title, 50) }}</a>
                                            </h6>
                                            <small style="color: var(--fe-text-muted); font-size: 0.75rem;">{{ $related->published_at->format('M d, Y') }}</small>
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
                        @foreach($categories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}" class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none" style="background: {{ $post->category && $post->category->id === $category->id ? 'var(--fe-primary-light)' : 'transparent' }}; color: {{ $post->category && $post->category->id === $category->id ? 'var(--fe-primary)' : 'var(--fe-text-secondary)' }}; font-size: 0.9rem; font-weight: {{ $post->category && $post->category->id === $category->id ? '600' : '400' }};">
                                {{ $category->name }}
                                <span class="badge rounded-pill" style="background: {{ $post->category && $post->category->id === $category->id ? 'var(--fe-primary)' : 'var(--fe-border)' }}; color: {{ $post->category && $post->category->id === $category->id ? '#fff' : 'var(--fe-text-secondary)' }}; font-size: 0.7rem;">{{ $category->published_posts_count }}</span>
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

                <!-- Back to Blog -->
                <div class="mt-3">
                    <a href="{{ route('blog.index') }}" class="fe-btn w-100" style="background: var(--fe-bg); color: var(--fe-text-secondary); border: 1px solid var(--fe-border);">
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
        color: var(--fe-text-secondary);
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: var(--fe-radius);
        margin: 1.5rem 0;
    }

    .blog-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: var(--fe-text);
    }

    .blog-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: var(--fe-text);
    }

    .blog-content h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        color: var(--fe-text);
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
        border-left: 4px solid var(--fe-primary);
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background: var(--fe-bg);
        border-radius: 0 var(--fe-radius-sm) var(--fe-radius-sm) 0;
        font-style: italic;
        color: var(--fe-text-secondary);
    }

    .blog-content pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1rem 1.5rem;
        border-radius: var(--fe-radius-sm);
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .blog-content code {
        background: var(--fe-bg);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .blog-content pre code {
        background: transparent;
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
        border: 1px solid var(--fe-border);
    }

    .blog-content table th {
        background: var(--fe-bg);
        font-weight: 600;
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
    }
</style>
@endpush
