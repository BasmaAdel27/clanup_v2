<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cookie;
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
    protected $redirectTo = '/home';

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
      public function showLoginForm()
    {
          $error='';
        return view('auth.login',compact('error'));
    }
    protected function authenticated(Request $request, $user)
    {

        if($user->timezone == '' || $user->timezone == null ) {
            $timezone=Session::get('timezone');
            $user->timezone = $timezone;
            $user->save();
        }

       
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
        // dd($user);
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
        if ($user->getEmail() == null){
            if($exist_user=User::where('provider_id',$user->getId())->first()){
                Auth::login($exist_user);
                return redirect($this->redirectTo);
            }

            if(User::where('provider_id',$user->getId())->exists()){
                return redirect('/login')->withErrors(['email' => __('This email is already in use!')]);
            }

             $user = User::create([
                'first_name' => $name_array[0],
                'last_name' => $name_array[1],
                'email' => $user->getId().'@facebook.com',
                'provider_id' => $user->getId(),
            ]);

        }else{
            $user = User::create([
                'first_name' => $name_array[0],
                'last_name' => $name_array[1],
                'email' => $user->getEmail(),
                'provider_id' => $user->getId(),

            ]);
        }
        $user->update([
            'provider' => $provider,
            'role' => 'user',
            'timezone'=>'Africa/Cairo',
            'email_verified_at'=>now()
        ]);

        // Set avatar
        $user->addMediaFromUrl($user->getDefaultAvatar())->toMediaCollection();

        // Login new user
        Auth::login($user);

        // Redirect user back
        // dd(Session::get('timezone'));
        return redirect($this->redirectTo);
    }
}
