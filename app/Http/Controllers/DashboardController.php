<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivationMail;
use App\Mail\GenerateNewPasswordEmail;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Str;
use Artisan;

class DashboardController extends Controller
{
    // index
    public function index()
    {
        Artisan::call('optimize:clear');
        generate_user_slug(auth()->id());

        if (is_agent(auth()->id())) {
            return redirect()->route('dialer.index');
        }

        return view('backend.dashboard.index');
    }

    /**
     * emailVerificationCode
     */
    public function emailVerificationCode()
    {
        return $this->emailVerificationWithCode();
    }

    /**
     * emailVerificationCodeResend
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emailVerificationCodeResend()
    {
        $code = Str::random(6);
        $user = User::where('id', Auth::user()->id)->first();
        $user->otp = $code;
        $user->save();
        Mail::to(Auth::user()->email)->send(new AccountActivationMail($code));

        return view('auth.verify');
    }

    /**
     * emailVerification user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emailVerificationWithCode()
    {
        if (Auth::user()->restriction == 1) {
            return view('auth.verify');
        } else {
            return redirect()->route('backend');
        }
    }

    /**
     * emailVerificationMatch user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emailVerificationMatch(Request $request)
    {
        $verify = User::where('id', Auth::user()->id)
                    ->where('otp', $request->otp)
                    ->exists();
        if ($verify) {
            $update_user = User::where('id', Auth::user()->id)
                                ->where('otp', $request->otp)
                                ->first();
            $update_user->restriction = 0;
            $update_user->save();
            smilify('success', 'Your Account is Activated');

            return redirect()->route('backend');
        } else {
            smilify('error', 'Invalid activation code. A new activation code already sent to your email.');

            return back()->with('error', 'Invalid activation code. A new activation code already sent to your email.');
        }
    }

    /**
     * GENERATE NEW PASSWORD
     */
    public function generateNewPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ], [
            'email.required' => 'Please enter your email address.',
        ]);

        $verify = User::where('email', $request->email)
                    ->exists();

        if ($verify) {
            $code = Str::random(6);
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($code);
            $user->otp = $code;
            $user->save();
            Mail::to($request->email)->send(new GenerateNewPasswordEmail($code));
            smilify('success', 'A new password is sent to your email');

            return redirect()->route('login');
        } else {
            smilify('error', 'Email address not found.');

            return back();
        }
    }

    /**
     * developer_feedback
     */
    public function developer_feedback()
    {
        return view('backend.developer_feedback.index');
    }

    //ENDS HERE
}
