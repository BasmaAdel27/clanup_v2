<?php

namespace App\Http\Controllers\Application\Account\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Account\Settings\Notification\Update;
use App\Services\Notification\NotificationType;

class NotificationSettingsController extends Controller
{
    /**
     * Show the account notification settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $types = NotificationType::whereNotNull('display_text')->get();
        $settings = auth()->user()->notificationSettings;

        $types->each(function ($type) use ($settings) {
            $setting = $settings->find($type->id);
            $type->status = $setting ? $setting->pivot->status : $type->status;
        });

        return view('application.account.settings.notification_settings', [
            'types' => $types,
        ]);
    }

    /**
     * Update notification settings
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        // Update user notification settings
        $settings = [];
        foreach ($request->type as $key => $value) {
            $settings[$key] = ['status' => $value];
        }
        $request->user()->notificationSettings()->syncWithoutDetaching($settings);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('account.settings.notifications');
    } 
}
