<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Settings\Member\Update;
use Illuminate\Http\Request;
use App\Models\Group;

class MemberSettingsController extends Controller
{
    /**
     * Display member settings page of the group
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
        
        return view('application.groups.settings.members', [
            'group' => $group,
        ]);
    }

    /**
     * Update member settings page of the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Member\Update $request
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Group $group)
    {
        // Authorize user
        if ($request->user()->cant('update', $group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }
        
        // Update the group information
        $group->setSetting('new_members_need_approved', $request->new_members_need_approved);
        $group->setSetting('new_members_need_pp', $request->new_members_need_pp);
        $group->setSetting('allow_members_create_discussion', $request->allow_members_create_discussion);
        $group->setSetting('welcome_message', $request->welcome_message);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('groups.settings.members', ['group' => $group->slug]);
    }
}
