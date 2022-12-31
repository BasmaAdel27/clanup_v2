<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Group;
use App\Models\Plan;
use Illuminate\Http\Request;

class StartController extends Controller
{
    /**
     * Display the Start Group Landing Page
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Authorize user
        if ($request->user() && $request->user()->can('create', Group::class)) {
            return redirect()->route('start.create');
        }
       
        $organizer_help_blogs = Blog::findByBlogCategorySlug('organizer-help')->latest()->take(3)->get();
        $most_cheap_plan = Plan::orderBy('price', 'asc')->first();

        return view('application.groups.start.landing', [
            'organizer_help_blogs' => $organizer_help_blogs,
            'most_cheap_plan' => $most_cheap_plan,
        ]);
    }

    /**
     * Display the Start Group Create Form
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Redirect user to organizer plan settings if the limit has been reached
        if ($request->user()->cant('create', Group::class)) {
            session()->flash('alert-danger', __('Please ugrade your plan to start more groups.'));
            return redirect()->route('account.organizer');
        }

        return view('application.groups.start.create');
    } 
}
