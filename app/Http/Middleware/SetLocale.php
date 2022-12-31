<?php 

namespace App\Http\Middleware;

use Closure;
use App\Services\Language\Drivers\Translation;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // Share languages with all views
        $languages = $this->translation->allLanguages();
        view()->share('languages', $languages);

        if (Auth::check()) {
            app()->setlocale(Auth::user()->locale);
            return $next($request);
        }

        if (session()->has('locale')) {
            app()->setlocale(session()->get('locale'));
        }

        return $next($request);
    }
}