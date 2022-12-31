<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->update(['timezone' => $request->timezone ?? '']);

        if ($request->_redirect) {
            $this->redirectTo = $request->_redirect;
        }
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        // Check if there is an existing user with this provider
        if ($existed_user = User::where(['email' => $user->getEmail(), 'provider' => $provider, 'provider_id' => $user->getId()])->first()) {
            // Login user
            Auth::login($existed_user);
            return redirect($this->redirectTo);
        }

        // First check the email is already in the database
        if (User::where(['email' => $user->getEmail()])->exists()){
            return redirect('/login')->withErrors(['email' => __('This email is already in use!')]);
        }

        // If no user found by email, create a new user
        $name_array = split_name($user->getName());
        $user = User::create([
            'first_name' => $name_array[0],
            'last_name' => $name_array[1],
            'email' => $user->getEmail(),
            'provider_id' => $user->getId(),
            'provider' => $provider,
            'role' => 'user'
        ]);

        // Set avatar 
        $user->addMediaFromUrl($user->getAvatar())->toMediaCollection();

        // Login new user
        Auth::login($user);

        // Redirect user back
        return redirect($this->redirectTo);
    }
}
