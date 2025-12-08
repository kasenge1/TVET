<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->with(['category', 'author'])
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->paginate(6);

        $featuredPosts = BlogPost::published()
            ->featured()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $categories = BlogCategory::active()
            ->withCount(['publishedPosts'])
            ->ordered()
            ->get();

        $currentCategory = $request->category
            ? BlogCategory::where('slug', $request->category)->first()
            : null;

        // Popular posts for sidebar
        $popularPosts = BlogPost::published()
            ->with('category')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        return view('blog.index', compact('posts', 'featuredPosts', 'categories', 'currentCategory', 'popularPosts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        $post->incrementViews();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function ($query) use ($post) {
                $query->where('category_id', $post->category_id);
            })
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $categories = BlogCategory::active()
            ->withCount(['publishedPosts'])
            ->ordered()
            ->get();

        // Popular posts for sidebar (excluding current post)
        $popularPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->with('category')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'categories', 'popularPosts'));
    }

    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = BlogPost::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(6);

        $categories = BlogCategory::active()
            ->withCount(['publishedPosts'])
            ->ordered()
            ->get();

        // Popular posts for sidebar
        $popularPosts = BlogPost::published()
            ->with('category')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        return view('blog.category', compact('category', 'posts', 'categories', 'popularPosts'));
    }
}
