<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Topic\Store;
use App\Http\Requests\Admin\Topic\Update;
use App\Models\Topic;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopicController extends Controller
{
    /**
     * Display Super Admin Topics Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Topics
        $topics = QueryBuilder::for(Topic::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());

        return view('admin.topics.index', [
            'topics' => $topics
        ]);
    }

    /**
     * Display the Form for Creating New Topic
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $topic = new Topic();
 
        // Fill model with old input
        if (!empty($request->old())) {
            $topic->fill($request->old());
        }

        return view('admin.topics.create', [
            'topic' => $topic,
        ]);
    }

    /**
     * Store the topic in Database
     *
     * @param \App\Http\Requests\Admin\Topic\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        // Create new Topic
        Topic::create($request->validated());

        session()->flash('alert-success', __('Topic created'));
        return redirect()->route('admin.topics');
    }


    /**
     * Display the Form for Editing Topic
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Topic $topic)
    {
        // Fill model with old input
        if (!empty($request->old())) {
            $topic->fill($request->old());
        }

        return view('admin.topics.edit', [
            'topic' => $topic,
        ]);
    }

    /**
     * Update the Topic in Database
     *
     * @param \App\Http\Requests\Admin\Topic\Update $request
     * @param \App\Models\Topic $topic
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Topic $topic)
    {
        // Update the Topic
        $topic->update($request->validated());
 
        session()->flash('alert-success', __('Topic updated'));
        return redirect()->route('admin.topics');
    }

    /**
     * Delete the Topic
     *
     * @param \App\Models\Topic $topic
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Topic $topic)
    {
        // Delete topic category
        $topic->delete();

        session()->flash('alert-success', __('Topic deleted'));
        return redirect()->route('admin.topics');
    }

    /**
     * Delete the Demo Topics
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete_demo_topics(Request $request)
    {
        // Delete Demo Topics
        Topic::whereBetween('id', [1, 2058])->delete();

        session()->flash('alert-success', __('Topics Deleted'));
        return redirect()->route('admin.topics');
    }
}
