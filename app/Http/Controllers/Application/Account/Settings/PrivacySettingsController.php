<?php

namespace App\Http\Controllers\Application\Account\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Account\Settings\Privacy\Update;

class PrivacySettingsController extends Controller
{
    /**
     * Show the account privacy settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('application.account.settings.privacy');
    }

    /** 
     * Update privacy settings
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        // Update settings
        $request->user()->setSettings([
            'show_groups_on_profile' => $request->show_groups_on_profile,
            'show_interests_on_profile' => $request->show_interests_on_profile,
        ]);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('account.settings.privacy');
    } 
}
