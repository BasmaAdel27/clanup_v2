<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class TopicSettingsController extends Controller
{
    /**
     * Display topics settings page of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Group $group)
    {
        // Authorize user
        if ($request->user()->cant('update', $group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        return view('application.groups.settings.topics', [
            'group' => $group,
        ]);
    } 
}
