<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Settings\ContentVisibility\Update;
use App\Models\Group;
use Illuminate\Http\Request;

class ContentVisibilitySettingsController extends Controller
{
    /**
     * Display content visibility settings page of the group
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
        
        return view('application.groups.settings.content_visibility', [
            'group' => $group,
        ]);
    }

    /**
     * Update content visibility settings page of the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\ContentVisibility\Update $request
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

        // if the group is already private then do nothing
        if (!$group->isClosed()) {
            $group->update([
                'group_type' => $request->content_visibility == 'private' ? Group::CLOSED : Group::OPEN,
            ]);
        }

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('groups.settings.content_visibility', ['group' => $group->slug]);
    } 
}
