<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Group;
use App\Models\Integration;

class IntegrationSettingsController extends Controller
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
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Get active integrations
        // TODO: Check if the group has the integration enabled on their plan
        $integrations = Integration::where('is_active', true)->get();

        return view('application.groups.settings.integrations.index', [
            'group' => $group,
            'integrations' => $integrations,
        ]);
    }

    /**
     * Display integration settings page of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Get integration
        $integration = Integration::where('slug', $request->route('integration'))->first();

        return view('application.groups.settings.integrations.details', [
            'group' => $group,
            'integration' => $integration,
        ]);
    }

    /**
     * Update integration settings of the group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function details_update(Request $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        // Get integration
        $integration = Integration::where('slug', $request->route('integration'))->first();
        // Call Integration Class
        $class_name = Str::studly($integration->slug);

        $integration_class = "App\Services\Integrations\\$class_name\Index";

        $instance = new $integration_class();
        $instance->update_settings($request, $group);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('groups.settings.integrations', ['group' => $group->slug]);
    }
}
