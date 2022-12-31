<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\Store;
use App\Http\Requests\Admin\Page\Update;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display Super Admin Pages Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Pages
        $pages = Page::orderBy('order')->paginate();

        return view('admin.pages.index', [
            'pages' => $pages
        ]);
    }

    /**
     * Display the Form for Creating New Page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $page = new Page();

        // Fill model with old input
        if (!empty($request->old())) {
            $page->fill($request->old());
        }
 
        return view('admin.pages.create', [
            'page' => $page,
        ]);
    }

    /**
     * Store the page in Database
     *
     * @param \App\Http\Requests\SuperAdmin\Page\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        // Create new Page
        Page::create(array_merge($request->validated(), [
            'content' => clean($request->content),
        ]));

        session()->flash('alert-success', __('Page created'));
        return redirect()->route('admin.pages');
    }

    /**
     * Display the Form for Editing Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Page $page)
    {
        // Fill model with old input
        if (!empty($request->old())) {
            $page->fill($request->old());
        }

        return view('admin.pages.edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the Page in Database
     *
     * @param \App\Http\Requests\SuperAdmin\Page\Update $request
     * @param \App\Models\Page                          $page
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Page $page)
    {
        // Update the Page
        $page->update(array_merge($request->validated(), [
            'content' => clean($request->content),
        ]));
 
        session()->flash('alert-success', __('Page Updated'));
        return redirect()->route('admin.pages');
    }

    /**
     * Delete the Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Page $page)
    {
        // Delete page
        if ($page->is_deletable) {
            $page->delete();
        }
            
        session()->flash('alert-success', __('Page Deleted'));
        return redirect()->route('admin.pages');
    }
}
