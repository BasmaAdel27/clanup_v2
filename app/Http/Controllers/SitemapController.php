<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Event;
use App\Models\Group;
use App\Models\Page;
use App\Models\Topic;
use App\Models\TopicCategory;

class SitemapController extends Controller
{
    /**
     * Display a listing of the sitemaps.
     */
    public function index()
    {
        return response(view('application.static.sitemap.sitemap', [
            'now' => now()->toAtomString(),
        ]))->header('Content-Type', 'application/xml');
    }

    /**
     * Display a listing of the blogs sitemap.
     */
    public function blogs()
    {
        return response(view('application.static.sitemap.sitemap_blogs', [
            'now' => now()->toAtomString(),
            'blog_tags' => BlogCategory::all(),
            'blogs' => Blog::published()->get(),
        ]))->header('Content-Type', 'application/xml');
    }

    /**
     * Display a listing of the blogs sitemap.
     */
    public function events()
    {
        return response(view('application.static.sitemap.sitemap_events', [
            'now' => now()->toAtomString(),
            'events' => Event::with('group')->select('id', 'uid', 'updated_at', 'group_id')->published()->get(),
        ]))->header('Content-Type', 'application/xml');
    }

    /**
     * Display a listing of the blogs sitemap.
     */
    public function groups()
    {
        return response(view('application.static.sitemap.sitemap_groups', [
            'now' => now()->toAtomString(),
            'groups' => Group::select('id', 'slug', 'updated_at')->get(),
        ]))->header('Content-Type', 'application/xml');
    }

    /**
     * Display a listing of the blogs sitemap.
     */
    public function pages()
    {
        return response(view('application.static.sitemap.sitemap_pages', [
            'now' => now()->toAtomString(),
            'pages' => Page::select('id', 'slug', 'updated_at')->published()->get(),
        ]))->header('Content-Type', 'application/xml');
    }

    /**
     * Display a listing of the blogs sitemap.
     */
    public function topics()
    {
        return response(view('application.static.sitemap.sitemap_topics', [
            'now' => now()->toAtomString(),
            'topic_categories' => TopicCategory::select('id', 'updated_at')->get(),
            'topics' => Topic::select('id', 'updated_at')->get(),
        ]))->header('Content-Type', 'application/xml');
    }

    /**
     * Display a listing of the robots.txt.
     */
    public function robots()
    {
        return response(view('application.static.sitemap.robots'))->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
