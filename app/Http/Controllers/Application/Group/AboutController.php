<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;

class AboutController extends Controller
{
    /**
     * Display about page of the group
     *
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // Log visit
        $group->visit(auth()->user(), $group);

        // Upcoming (2)
        $events_count = $group->events()->upcoming()->count();
        $upcoming_events = $group->events()->upcoming()->take(2)->get();

        // Photos Count<
        $photos = $group->media()->latest()->take(8)->get();

        // Discussions Count
        $discussions = $group->discussions()->latest()->take(2)->get();

        // Related Topics Count
        $topics_count = $group->topics()->count();

        // Members (10)
        $members = $group->members()->latest()->take(9)->get();

        // Organizer
        $organizer = $group->createdBy;

        return view('application.groups.about', [
            'group' => $group,
            'events_count' => $events_count,
            'upcoming_events' => $upcoming_events,
            'photos' => $photos,
            'discussions' => $discussions,
            'topics_count' => $topics_count,
            'member_count' => $group->member_count,
            'members' => $members,
            'organizer' => $organizer,
        ]);
    } 
}
