<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\Store;
use App\Http\Requests\Admin\Blog\Update;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display Admin Blogs Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Blogs
        $blogs = Blog::orderBy('id', 'desc')->paginate();

        return view('admin.blogs.index', [
            'blogs' => $blogs
        ]);
    }

    /**
     * Display the Form for Creating New Blog
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $blog = new Blog();
 
        // Fill model with old input
        if (!empty($request->old())) {
            $blog->fill($request->old());
        }

        return view('admin.blogs.create', [
            'blog' => $blog,
        ]);
    }

    /**
     * Store the blog in Database
     *
     * @param \App\Http\Requests\SuperAdmin\Blog\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();

        // Create new Blog
        $blog = Blog::create(array_merge($request->validated(), [
            'content' => clean($request->content),
            'is_active' => true,
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
        ]));

        // Update featured image
        if ($request->featured_image) {
            $blog->addMediaFromRequest('featured_image')->toMediaCollection();
        }

        session()->flash('alert-success', __('Blog created'));
        return redirect()->route('admin.blogs');
    }


    /**
     * Display the Form for Editing Blog
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Blog         $blog
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Blog $blog)
    {
        // Fill model with old input
        if (!empty($request->old())) {
            $blog->fill($request->old());
        }

        return view('admin.blogs.edit', [
            'blog' => $blog,
        ]);
    }

    /**
     * Update the Blog in Database
     *
     * @param \App\Http\Requests\Admin\Blog\Update $request
     * @param \App\Models\Blog                     $blog
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Blog $blog)
    {
        // Update the Blog
        $blog->update(array_merge($request->validated(), [
            'content' => clean($request->content),
            'updated_by_id' => $request->user()->id,
        ]));

        // Update featured image
        if ($request->featured_image) {
            $blog->addMediaFromRequest('featured_image')->toMediaCollection();
        }
 
        session()->flash('alert-success', __('Blog updated'));
        return redirect()->route('admin.blogs');
    }

    /**
     * Delete the Blog
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Blog         $blog
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Blog $blog)
    {
        // Delete blog category
        $blog->delete();

        session()->flash('alert-success', __('Blog deleted'));
        return redirect()->route('admin.blogs');
    }
}
