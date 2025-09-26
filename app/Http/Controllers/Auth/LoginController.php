<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Artisan;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [ // validate the request
            $this->username() => 'required', // username is required
            'password' => 'required', // password is required
        ],[
            $this->username().'.required' => 'Please enter your email or phone number', // username is required
            'password.required' => 'Please enter your password', // password is required
        ]);

        Artisan::call('cache:clear');

        // if (teleman_config('google_recaptcha') == 'YES') { // if google recaptcha is enabled
        //     $this->validate($request, [ // validate the request
        //         'g-recaptcha-response' => ['required', new Recaptcha()] // new rule Recaptcha
        //     ],[
        //         'g-recaptcha-response.required' => 'Please verify that you are not a robot' // custom error message
        //     ]);
        // } // ends

    }
}
