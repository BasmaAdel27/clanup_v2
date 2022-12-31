<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\Language\Drivers\Translation;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * List all languages
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = $this->translation->allLanguages();

        return view('admin.languages.index', ['languages' => $languages]);
    }

    /**
     * Add new language
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store new language
     *
     * @return \Illuminate\Http\Redirect
     */
    public function store(Request $request)
    {
        $this->translation->addLanguage($request->name, $request->name);

        session()->flash('alert-success', __('Language added'));
        return redirect()->route('admin.languages');
    }

    /**
     * Set the language as default
     *
     * @return \Illuminate\Http\Redirect
     */
    public function set_default(Request $request)
    {
        SystemSetting::setEnvironmentValue([
            'APP_LOCALE' => $request->language
        ]);

        return redirect()->route('admin.languages');
    }
}
