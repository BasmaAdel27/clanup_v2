<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(9);

        return view('application.static.blog.index', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tags(BlogCategory $blog_category)
    {
        $blogs = Blog::where('blog_category_id', $blog_category->id)->latest()->paginate();

        return view('application.static.blog.index', [
            'tag' => $blog_category->name,
            'blogs' => $blogs,
        ]);
    }

    /**
     * Show the blog page.
     *
     * @param \App\Models\Blog $blog
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Blog $blog)
    {
        // Log visit
        $blog->visit(auth()->user());

        // Fetch related blogs
        $related_blogs = Blog::where('id', '!=', $blog->id)->take(3)->latest()->get();

        return view('application.static.blog.show', [
            'blog' => $blog,
            'related_blogs' => $related_blogs,
        ]);
    }
}
