<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Settings\Basic\Update;
use App\Http\Requests\Application\Group\Settings\Basic\Delete;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class BasicSettingsController extends Controller
{
    /**
     * Display basic settings page of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }
        $error='';
        return view('application.groups.settings.basic', [
            'group' => $group,
            'error'=>$error
        ]);
    }

    /**
     * Update basic settings page of the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Basic\Update $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Update the group information
        $group->update([
            'name' => $request->group_name,
            'describe' => $request->group_describe,
        ]);

        // Update the address
        $group->updateAddress('main', [
            'name' => $request->place_name ?? $request->location_name,
            'address_1' => $request->formatted_address ?? $request->location_name,
            'state' => $request->state,
            'city' => $request->city,
            'zip' => $request->postal_code,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        // Update featured image
        if ($request->group_featured_photo) {
            $group->addMediaFromRequest('group_featured_photo')->toMediaCollection('featured_photo');
        }

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('groups.settings', ['group' => $group->slug]);
    }

    /**
     * Delete the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Basic\Delete $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_view(Request $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('delete', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        return view('application.groups.settings.delete', [
            'group' => $group,
        ]);
    }

    /**
     * Delete the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Basic\Delete $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Delete $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('delete', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Update the group information
        $group->update([
            'delete_reason' => $request->delete_reason,
        ]);

        // Soft delete group
        $group->delete();

        session()->flash('alert-success', __('Group deleted'));
        return redirect()->route('home');
    }
}
