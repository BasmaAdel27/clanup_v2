<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Show the page.
     *
     * @param \App\Models\Page $page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Page $page)
    {
        if (!$page->is_active) return redirect()->route('home');

        // Log visit
        $page->visit(auth()->user());

        return view('application.static.pages.show', [
            'page' => $page,
        ]);
    }
}
