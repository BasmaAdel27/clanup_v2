<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategory\Store;
use App\Http\Requests\Admin\BlogCategory\Update;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
     * Display Admin Blog Categories Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Blog Categories
        $blog_categories = BlogCategory::orderBy('id', 'desc')->paginate();

        return view('admin.blog_categories.index', [
            'blog_categories' => $blog_categories
        ]);
    }

    /**
     * Display the Form for Creating New Blog Category
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $blog_category = new BlogCategory();
 
        // Fill model with old input
        if (!empty($request->old())) {
            $blog_category->fill($request->old());
        }

        return view('admin.blog_categories.create', [
            'blog_category' => $blog_category,
        ]);
    }

    /**
     * Store the Blog Category in Database
     *
     * @param \App\Http\Requests\Admin\BlogCategory\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        // Create new Blog Category
        BlogCategory::create($request->validated());

        session()->flash('alert-success', __('Blog Category Created'));
        return redirect()->route('admin.blog_categories');
    }

    /**
     * Display the Form for Editing Blog Category
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BlogCategory $blog_category
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, BlogCategory $blog_category)
    {
        // Fill model with old input
        if (!empty($request->old())) {
            $blog_category->fill($request->old());
        }

        return view('admin.blog_categories.edit', [
            'blog_category' => $blog_category,
        ]);
    }

    /**
     * Update the Blog Category in Database
     *
     * @param \App\Http\Requests\Admin\BlogCategory\Update $request
     * @param \App\Models\BlogCategory                     $blog_category
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, BlogCategory $blog_category)
    {
        // Update the Blog Category
        $blog_category->update($request->validated());
 
        session()->flash('alert-success', __('Blog Category Updated'));
        return redirect()->route('admin.blog_categories');
    }

    /**
     * Delete the Blog Category
     *
     * @param \App\Models\BlogCategory  $blog_category
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(BlogCategory $blog_category)
    {
        // Delete blog category
        $blog_category->delete();

        session()->flash('alert-success', __(
            'Blog Category Deleted. Make sure to assign new category for blogs which have removed blog category as a category.'
        ));
        return redirect()->route('admin.blog_categories');
    }
}
