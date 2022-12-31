<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Discussion\Store;
use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\Group;

class DiscussionController extends Controller
{
    /**
     * Display the Group's Discussions Page
     *
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // Log visit
        $group->visit(auth()->user(), $group);
        
        $discussions = $group->discussions()->latest()->paginate();

        return view('application.groups.discussions', [
            'group' => $group,
            'discussions' => $discussions,
        ]);
    } 

    /**
     * Store the Group Discussion
     *
     * @param  App\Http\Requests\Application\Group\Discussion\Store $request
     * @param  \App\Models\Group                                    $group
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request, Group $group)
    {
        // Authorize user
        if ($request->user()->cant('create', [Discussion::class, $group])) {
            return redirect()->to(route('groups.discussions', ['group' => $group->slug]) . '#discussions');
        }

        // Create discussion
        Discussion::create([
            'group_id' => $group->id,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => clean($request->content),
        ]);

        return redirect()->to(route('groups.discussions', ['group' => $group->slug]) . '#discussions');
    }

    /**
     * Display the Group Discussions Details Page
     *
     * @param  \App\Models\Group $group
     * @param  \App\Models\Discussion $discussion
     * 
     * @return \Illuminate\Http\Response
     */
    public function details(Group $group, Discussion $discussion)
    {
        // Log visit
        $discussion->visit(auth()->user(), $group);

        $comments = $discussion->comments()->latest()->paginate(50);

        return view('application.groups.discussions.details', [
            'group' => $group,
            'discussion' => $discussion,
            'comments' => $comments,
        ]);
    } 

    /**
     * Delete the Group Discussion
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Discussion $discussion
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, Group $group, Discussion $discussion)
    {
        // Authorize user
        if ($request->user()->cant('delete', $discussion)) {
            return redirect()->to(route('groups.discussions', ['group' => $group->slug]) . '#discussions');
        }

        // Delete discussion
        $discussion->delete();

        return redirect()->to(route('groups.discussions', ['group' => $group->slug]) . '#discussions');
    } 
}
