<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display {tab} Settings Page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // If the tab is currencies
        if ($request->tab == 'currencies') {
            $currencies = Currency::all();
            return view('admin.settings.' . $request->tab, ['currencies' => $currencies]);
        }

        return view('admin.settings.' . $request->tab, [
            'tab' => $request->tab
        ]);
    }

    /**
     * Update Application Settings
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $tab = $request->tab;

        switch ($tab) {
            case 'application':
                // Validate request
                $validated = $request->validate([
                    'application_name' => 'required|string|max:140',
                    'meta_description' => 'required|string|max:200',
                    'meta_keywords' => 'required|string|max:200',
                    'google_recapthca_key' => 'nullable|string',
                    'google_recapthca_secret_key' => 'nullable|string',
                ]);

                // Update favicon
                if ($request->favicon) {
                    $request->validate(['favicon' => 'image|mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024]);
                    $path = $request->favicon->storeAs('favicons', 'favicon.'.$request->favicon->getClientOriginalExtension(), 'public_dir');
                    SystemSetting::setSetting('application_favicon', '/uploads/'.$path);
                }

                // Update logo
                if ($request->logo) {
                    $request->validate(['logo' => 'image|mimes:' . config('filesystems.mimes') . ',svg|between:0,' . config('filesystems.max_size') * 1024]);
                    $path = $request->logo->storeAs('logo', 'logo.'.$request->logo->getClientOriginalExtension(), 'public_dir');
                    SystemSetting::setSetting('application_logo', '/uploads/'.$path);
                }
                break;
            case 'location':
                // Validate request
                $validated = $request->validate([
                    'google_places_api_key' => 'nullable|string',
                    'default_location_city_name' => 'nullable|string|max:255',
                    'default_location_country_name' => 'nullable|string|max:255',
                    'default_location_latitude' => 'nullable|string|max:12',
                    'default_location_longitude' => 'nullable|string|max:12',
                ]);
                break;
            case 'payment':
                // Validate request
                $validated = $request->validate([
                    'application_currency' => 'required|string',
                    'order_prefix' => 'required|string|max:5',
                    'active_payment_gateway' => 'required|string',
                    'grace_period' => 'required|integer',
                    'stripe_publishable_key' => 'nullable|string',
                    'stripe_secret_key' => 'nullable|string',
                    'stripe_webhook_secret' => 'nullable|string',
                ]);
                break;
            case 'mail':
                // Validate
                $request->validate([
                    'mail_mailer' => 'required|string',
                    'mail_host' => 'required|string',
                    'mail_port' => 'required|integer',
                    'mail_username' => 'nullable|string',
                    'mail_password' => 'nullable|string',
                    'mail_from_address' => 'required|email',
                    'mail_from_name' => 'required|string',
                    'mail_encryption' => 'nullable|string',
                ]);

                $env = [
                    'MAIL_MAILER' => $request->mail_mailer,
                    'MAIL_HOST' => $request->mail_host,
                    'MAIL_PORT' => $request->mail_port,
                    'MAIL_USERNAME' => $request->mail_username,
                    'MAIL_PASSWORD' => $request->mail_password,
                    'MAIL_ENCRYPTION' => $request->mail_encryption,
                    'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                    'MAIL_FROM_NAME' => $request->mail_from_name,
                ];

                // Update .env
                if (!SystemSetting::setEnvironmentValue($env)) {
                    session()->flash('alert-danger', __('Something went wrong'));
                    return redirect()->route('admin.settings', ['tab' => $tab]);
                }

                $validated = [];
                break;
            case 'social':
                // Validate
                $env = $request->validate([
                    'FACEBOOK_CLIENT_ID' => 'nullable|string',
                    'FACEBOOK_CLIENT_SECRET' => 'nullable|string',
                    'GOOGLE_CLIENT_ID' => 'nullable|string',
                    'GOOGLE_CLIENT_SECRET' => 'nullable|string',
                    'TWITTER_CLIENT_ID' => 'nullable|string',
                    'TWITTER_CLIENT_SECRET' => 'nullable|string',
                    'LINKEDIN_CLIENT_ID' => 'nullable|string',
                    'LINKEDIN_CLIENT_SECRET' => 'nullable|string',
                ]);

                // Update .env
                if (!SystemSetting::setEnvironmentValue($env)) {
                    session()->flash('alert-danger', __('Something went wrong'));
                    return redirect()->route('admin.settings', ['tab' => $tab]);
                }

                $validated = [];
                break;
            case 'company':
                    // Validate
                    $validated = $request->validate([
                        'company_address' => 'nullable|string',
                        'facebook_link' => 'nullable|string',
                        'twitter_link' => 'nullable|string',
                        'instagram_link' => 'nullable|string',
                        'pinterest_link' => 'nullable|string',
                        'linkedin_link' => 'nullable|string',
                        'youtube_link' => 'nullable|string',
                        'vimeo_link' => 'nullable|string',
                    ]);
                    break;
            default:
                $validated = [];
                break;
        }

        // Update each settings
        SystemSetting::setSettings($validated);

        session()->flash('alert-success', __('Settings updated'));
        return redirect()->route('admin.settings', ['tab' => $tab]);
    }

    /**
     * Enable Currency
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function currencies_enable(Request $request)
    {
        $currency = Currency::where('code', $request->code)->firstOrFail();
        $currency->enabled = true;
        $currency->save();

        return redirect()->route('admin.settings', ['tab' => 'currencies']);
    }

    /**
     * Disable Currency
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function currencies_disable(Request $request)
    { 
        $currency = Currency::where('code', $request->code)->firstOrFail();
        $currency->enabled = false;
        $currency->save();

        return redirect()->route('admin.settings', ['tab' => 'currencies']);
    }
}
