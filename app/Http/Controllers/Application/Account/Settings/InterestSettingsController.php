<?php

namespace App\Http\Controllers\Application\Account\Settings;

use App\Http\Controllers\Controller;

class InterestSettingsController extends Controller
{
    /**
     * Show the account interests settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('application.account.settings.interests');
    }
}
