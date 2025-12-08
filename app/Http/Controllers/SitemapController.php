<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\Unit;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $baseUrl = config('app.url');

        // Main sitemap
        $content .= '<sitemap>';
        $content .= '<loc>' . $baseUrl . '/sitemap-pages.xml</loc>';
        $content .= '<lastmod>' . now()->toW3cString() . '</lastmod>';
        $content .= '</sitemap>';

        // Courses sitemap
        $content .= '<sitemap>';
        $content .= '<loc>' . $baseUrl . '/sitemap-courses.xml</loc>';
        $content .= '<lastmod>' . now()->toW3cString() . '</lastmod>';
        $content .= '</sitemap>';

        // Blog sitemap
        $content .= '<sitemap>';
        $content .= '<loc>' . $baseUrl . '/sitemap-blog.xml</loc>';
        $content .= '<lastmod>' . now()->toW3cString() . '</lastmod>';
        $content .= '</sitemap>';

        $content .= '</sitemapindex>';

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function pages()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $baseUrl = config('app.url');

        // Static pages with priorities
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/courses', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/about', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/faq', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/privacy-policy', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/terms-of-service', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($pages as $page) {
            $content .= '<url>';
            $content .= '<loc>' . $baseUrl . $page['url'] . '</loc>';
            $content .= '<lastmod>' . now()->toW3cString() . '</lastmod>';
            $content .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $content .= '<priority>' . $page['priority'] . '</priority>';
            $content .= '</url>';
        }

        $content .= '</urlset>';

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function courses()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $baseUrl = config('app.url');

        // Published courses
        $courses = Course::where('is_published', true)
            ->select('slug', 'updated_at')
            ->get();

        foreach ($courses as $course) {
            $content .= '<url>';
            $content .= '<loc>' . $baseUrl . '/courses/' . $course->slug . '</loc>';
            $content .= '<lastmod>' . $course->updated_at->toW3cString() . '</lastmod>';
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.8</priority>';
            $content .= '</url>';
        }

        $content .= '</urlset>';

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function blog()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $baseUrl = config('app.url');

        // Blog categories
        $categories = BlogCategory::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();

        foreach ($categories as $category) {
            $content .= '<url>';
            $content .= '<loc>' . $baseUrl . '/blog/category/' . $category->slug . '</loc>';
            $content .= '<lastmod>' . $category->updated_at->toW3cString() . '</lastmod>';
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.6</priority>';
            $content .= '</url>';
        }

        // Published blog posts
        $posts = BlogPost::published()
            ->select('slug', 'updated_at')
            ->get();

        foreach ($posts as $post) {
            $content .= '<url>';
            $content .= '<loc>' . $baseUrl . '/blog/' . $post->slug . '</loc>';
            $content .= '<lastmod>' . $post->updated_at->toW3cString() . '</lastmod>';
            $content .= '<changefreq>monthly</changefreq>';
            $content .= '<priority>0.7</priority>';
            $content .= '</url>';
        }

        $content .= '</urlset>';

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
