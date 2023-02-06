<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Settings\Sponsor\Store;
use App\Http\Requests\Application\Group\Settings\Sponsor\Update;
use App\Models\Group;
use App\Models\GroupSponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SponsorSettingsController extends Controller
{
    /**
     * Display sponsors page of the group
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
        $auth_user=Auth::user() ;
        if (!$auth_user && !$auth_user->isOrganizerOf($group)) {

            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        $sponsors = $group->sponsors;

        return view('application.groups.settings.sponsors.index', [
            'group' => $group,
            'sponsors' => $sponsors,
        ]);
    }

    /**
     * Display create sponsor page of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Group $group)
    {
        // Check the maximum sponsor count
//        if ($request->user()->cant('store_sponsor', $group)) {
        $auth_user=Auth::user() ;
        if (!$auth_user && !$auth_user->isOrganizerOf($group)) {
            session()->flash('alert-danger', __('You have reached maximum sponsors limit'));
            return redirect()->route('groups.settings.sponsors', ['group' => $group->slug]);
        }

        $sponsor = new GroupSponsor();

        return view('application.groups.settings.sponsors.create', [
            'group' => $group,
            'sponsor' => $sponsor,
        ]);
    }


    /**
     * Store sponsor of the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Sponsor\Store $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('store_sponsor', $group)) {
        $auth_user=Auth::user() ;
        if (!$auth_user && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Save the sponsor
        $sponsor = GroupSponsor::create(array_merge($request->validated(), [
            'group_id' => $group->id,
            'created_by' => $request->user()->id,
        ]));

        // Add logo
        if ($request->logo) {
            $sponsor->addMediaFromRequest('logo')->toMediaCollection();
        }

        session()->flash('alert-success', __('Sponsor added'));
        return redirect()->route('groups.settings.sponsors', ['group' => $group->slug]);
    }

    /**
     * Display create sponsor page of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\GroupSponsor $sponsor
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group, GroupSponsor $sponsor)
    {
        // Authorize user
//        if ($request->user()->cant('update_sponsor', $group)) {
        $auth_user=Auth::user() ;
        if (!$auth_user && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        return view('application.groups.settings.sponsors.edit', [
            'group' => $group,
            'sponsor' => $sponsor,
        ]);
    }

    /**
     * Update sponsor of the group
     *
     * @param  \App\Http\Requests\Application\Group\Settings\Sponsor\Update $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\GroupSponsor $sponsor
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Group $group, GroupSponsor $sponsor)
    {
        // Authorize user
//        if ($request->user()->cant('update_sponsor', $group)) {
        $auth_user=Auth::user() ;
        if (!$auth_user && !$auth_user->isOrganizerOf($group)) {

            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Update the sponsor
        $sponsor->update($request->validated());

        // Add logo
        if ($request->logo) {
            $sponsor->addMediaFromRequest('logo')->toMediaCollection();
        }

        session()->flash('alert-success', __('Sponsor updated'));
        return redirect()->route('groups.settings.sponsors', ['group' => $group->slug]);
    }

    /**
     * Delete sponsor of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\GroupSponsor $sponsor
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Group $group, GroupSponsor $sponsor)
    {
        // Authorize user
//        if ($request->user()->cant('delete_sponsor', $group)) {
        $auth_user=Auth::user() ;
        if (!$auth_user && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Delete sponsor
        $sponsor->delete();

        session()->flash('alert-success', __('Sponsor deleted'));
        return redirect()->route('groups.settings.sponsors', ['group' => $group->slug]);
    }
}
