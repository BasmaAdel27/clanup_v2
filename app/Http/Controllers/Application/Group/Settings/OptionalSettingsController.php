<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Settings\Optional\Update;
use Illuminate\Http\Request;
use App\Models\Group;

class OptionalSettingsController extends Controller
{
    /**
     * Display optional settings page of the group
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
        
        return view('application.groups.settings.optional', [
            'group' => $group,
        ]);
    } 

    /**
     * Update optional settings page of the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Optional\Update $request
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
        $group->setSetting('facebook_url', $request->facebook);
        $group->setSetting('instagram_url', $request->instagram);
        $group->setSetting('twitter_url', $request->twitter);
        $group->setSetting('linkedin_url', $request->linkedin);
        $group->setSetting('website_url', $request->website);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('groups.settings.optional', ['group' => $group->slug]);
    }
}
