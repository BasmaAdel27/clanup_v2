<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicCategory\Store;
use App\Http\Requests\Admin\TopicCategory\Update;
use App\Models\TopicCategory;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopicCategoryController extends Controller
{
    /**
     * Display Super Admin Topic Categories Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Topic Categories
        $topic_categories = QueryBuilder::for(TopicCategory::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());

        return view('admin.topic_categories.index', [
            'topic_categories' => $topic_categories
        ]);
    }

    /**
     * Display the Form for Creating New Topic Category
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $topic_category = new TopicCategory();
 
        // Fill model with old input
        if (!empty($request->old())) {
            $topic_category->fill($request->old());
        }

        return view('admin.topic_categories.create', [
            'topic_category' => $topic_category,
        ]);
    }

    /**
     * Store the Topic Category in Database
     *
     * @param \App\Http\Requests\Admin\TopicCategory\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        // Create new Topic Category
        TopicCategory::create($request->validated());

        session()->flash('alert-success', __('Topic Category created'));
        return redirect()->route('admin.topic_categories');
    }

    /**
     * Display the Form for Editing TopicCategory
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\TopicCategory $topic_category
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TopicCategory $topic_category)
    {
        // Fill model with old input
        if (!empty($request->old())) {
            $topic_category->fill($request->old());
        }

        return view('admin.topic_categories.edit', [
            'topic_category' => $topic_category,
        ]);
    }

    /**
     * Update the Package in Database
     *
     * @param \App\Http\Requests\Admin\TopicCategory\Update $request
     * @param \App\Models\TopicCategory                     $topic_category
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, TopicCategory $topic_category)
    {
        // Update the Topic Category
        $topic_category->update($request->validated());
 
        session()->flash('alert-success', __('Topic Category updated'));
        return redirect()->route('admin.topic_categories');
    }

    /**
     * Delete the Package
     *
     * @param \App\Models\TopicCategory $topic_category
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(TopicCategory $topic_category)
    {
        // Delete topic category
        $topic_category->delete();

        session()->flash('alert-success', __('Topic Category deleted'));
        return redirect()->route('admin.topic_categories');
    }

    /**
     * Delete the Demo Topic Category
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete_demo_topic_categories(Request $request)
    {
        // Delete Demo Topic Categories
        TopicCategory::whereBetween('id', [1, 33])->delete();

        session()->flash('alert-success', __('Topic Categories Deleted'));
        return redirect()->route('admin.topic_categories');
    }
}
