<?php

namespace App\Http\Controllers\Application\Account\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Account\Settings\Details\Update as DetailsUpdate;
use App\Http\Requests\Application\Account\Settings\Social\Update as SocialUpdate;
use App\Http\Requests\Application\Account\Settings\Password\Update as PasswordUpdate;
use App\Http\Requests\Application\Account\Settings\Address\Update as AddressUpdate;
use Illuminate\Support\Facades\Hash;

class GeneralSettingsController extends Controller
{
    /**
     * Show the account general settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('application.account.settings.general.index');
    }

    /**
     * Show the account general settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function details()
    {
        return view('application.account.settings.general.details');
    }

    /**
     * Update basic settings
     *
     * @param  \App\Http\Requests\Application\Account\Settings\Details $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function details_update(DetailsUpdate $request)
    {
        $user = $request->user();

        // Update User
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'timezone' => $request->timezone,
            'username' => $request->username,
        ]);

        // Update profile
        $user->setSettings([
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'bio' => $request->bio,
        ]);

        // Upload to image to server
        if ($request->profile_picture) {
            $user->addMediaFromRequest('profile_picture')->toMediaCollection();
        }

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('account.settings.general');
    }

    /**
     * Show the account general settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function address()
    {
        return view('application.account.settings.general.address');
    }

    /**
     * Show the account general settings page.
     * 
     * @param  \App\Http\Requests\Application\Account\Settings\Address $request
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function address_update(AddressUpdate $request)
    {
        $user = $request->user();

        // Update profile
        $user->setSettings([
            'city' => $request->city,
            'country' => $request->country,
            'hometown' => $request->hometown,
        ]);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('account.settings.general');
    }

    /**
     * Show the account general settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function social()
    {
        return view('application.account.settings.general.social');
    }

    /**
     * Show the account general settings page.
     * 
     * @param  \App\Http\Requests\Application\Account\Settings\Social $request
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function social_update(SocialUpdate $request)
    {
        $user = $request->user();

        // Update profile
        $user->setSettings([
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
        ]);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('account.settings.general');
    }

    /**
     * Show the account general settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function password()
    {
        return view('application.account.settings.general.password');
    }

    /**
     * Show the account general settings page.
     *
     * @param  \App\Http\Requests\Application\Account\Settings\Password $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function password_update(PasswordUpdate $request)
    {
        // Get user
        $user = $request->user();
        
        // Update Password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Update profile
        $user->setSetting('last_password_update', now());

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('account.settings.general');
    }

    // Verify Email
    public function verify_email()
    {
        $user = auth()->user();
        $user->sendEmailVerificationNotification();
        session()->flash('alert-success', __('Verification email sent'));
        return redirect()->route('account.settings.general');
    }
}
