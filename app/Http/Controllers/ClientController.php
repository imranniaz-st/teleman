<?php

namespace App\Http\Controllers;

use App\cPanelApi;
use App\Mail\ExpiryAlertMail;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Mail;
use Str;
use App\Mail\AccountActivationMail;
use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\Package;
use Hash;
use PDF;
use Session;

class ClientController extends Controller
{
    // index
    public function index()
    {
        return view('backend.client.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|max:255|unique:users',
            'password' => 'required|min:6',
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'This email is already registered',
            'phone.required' => 'Please enter your phone number',
            'phone.unique' => 'This phone number is already registered',
            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 6 characters',
        ]);

        DB::transaction(function () use ($request) {

            //tenant database name
            $database_name = Str::lower(env('DB_PREFIX')).$request->domain;

            // IP
            if (env('DEVELOPMENT_MODE') == 'NO') {
                $ip = $request->ip();
            } else {
                $ip = '37.111.218.174';
            }

            // new user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->otp = generateOTP();
            $user->phone = $request->phone;
            $user->rest_name = $request->rest_name;
            $user->rest_address = $request->rest_address;
            $user->role = 'customer';
            $user->restriction = 0;
            $user->password = Hash::make($request->password);
            $user->domain = $user->email;
            // country details
            $user->country = get_country_info_via_ip($ip)['country'];
            $user->country_code = get_country_info_via_ip($ip)['country_code'];
            $user->region_name = get_country_info_via_ip($ip)['region_name'];
            $user->city = get_country_info_via_ip($ip)['city'];
            $user->zip = get_country_info_via_ip($ip)['zip'];
            $user->lat = get_country_info_via_ip($ip)['lat'];
            $user->lon = get_country_info_via_ip($ip)['lon'];
            $user->timezone = get_country_info_via_ip($ip)['timezone'];
            $user->save();

            /* A helper function that generates a unique slug for the user. */
            generate_user_slug($user->id);

            // subscribe
            $subscription = new Subscription;
            $subscription->user_id = $user->id;
            $subscription->package_id = $request->package_id;
            $subscription->credit = package_credit($request->package_id);
            $subscription->emails = null;
            $subscription->sms = null;
            $subscription->domain = $user->email;
            $subscription->start_at = packageStartEndDate($request->package_id)['start_date'];
            $subscription->end_at = packageStartEndDate($request->package_id)['end_date'];
            if (isThisPackageIsFree($request->package_id) == true) {
                $subscription->active = 1;
                $subscription->payment_status = 'trial';
            } else {
                $subscription->active = 1;
                $subscription->payment_status = 'paid';
            }
            $subscription->payment_gateway = env('PAYMENT_GATEWAY');
            $subscription->amount = getPackagePrice($request->package_id);
            $subscription->save();

            // ItemLimitCount
            $itemLimitCount = new ItemLimitCount;
            $itemLimitCount->user_id = $user->id;
            $itemLimitCount->domain = $user->email;
            $itemLimitCount->subscription_id = $subscription->id;
            $itemLimitCount->credit = $subscription->credit;
            $itemLimitCount->emails = null;
            $itemLimitCount->sms = null;
            $itemLimitCount->save();

            // PaymentHistory
            $paymentHistory = new PaymentHistory;
            $paymentHistory->user_id = $user->id;
            $paymentHistory->invoice = invoiceNumber();
            $paymentHistory->domain = $user->email;
            $paymentHistory->subscription_id = $subscription->id;
            $paymentHistory->package_id = $request->package_id;
            $paymentHistory->amount = getPackagePrice($request->package_id);
            $paymentHistory->trx_id = generateRandomString();
            $paymentHistory->payment_gateway = env('PAYMENT_GATEWAY');
            if (isThisPackageIsFree($request->package_id) == true) {
                $paymentHistory->payment_status = 'trial';
            } else {
                $paymentHistory->payment_status = 'paid';
            }
            $paymentHistory->start_at = packageStartEndDate($request->package_id)['start_date'];
            $paymentHistory->end_at = packageStartEndDate($request->package_id)['end_date'];
            $paymentHistory->save();

            // session invoice
            $invoiceSession = Session::put('invoice', $paymentHistory->invoice);

            // session
            $request->session()->put('subscription_details', $paymentHistory);

            try {

                // Invoice PDF
                $pdf = PDF::loadView('frontend.success.attachment_invoice', [
                    'details' => $paymentHistory,
                ])->save(invoice_path($paymentHistory->invoice));

                // DOmain Invoice PDF
                $pdf = PDF::loadView('frontend.success.domain_attachment_invoice', [
                    'details' => $paymentHistory,
                ])->save(domain_invoice_path($paymentHistory->invoice));

                // InvoiceMail
                Mail::to($user->email)->queue(new InvoiceMail($paymentHistory));

                // DomainInvoiceMail
                Mail::to($user->email)->queue(new DomainInvoiceMail($paymentHistory));

            } catch (\Throwable $th) {
                tlog($th->getMessage());
            }

            activity($user->name, 'is the new customer of '.appName());

        }, 5);

        smilify('success', 'You have successfully registered');
        return back();

        // store ends here
    }

    // banned user restriction
    public function restriction(Request $request, $user_id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $user = User::where('role', 'customer')->find($user_id);

        if ($user->restriction == 1) {
            $user->restriction = 0;
            $user->save();
            activity($user->name, 'has been unblocked');
            smilify('success', 'User unblocked successfully');
        } else {
            $user->restriction = 1;
            $user->save();
            activity($user->name, 'is blokced');
            smilify('success', 'User blocked successfully');
        }

        return back();
    }

    // user subscribe unsubscribe
    public function subscribe_unsubscribe(Request $request, $user_id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $user = Subscription::where('user_id', $user_id)->first();

        if ($user->active == 1) {
            $user->active = 0;
            $user->save();
            activity($user->name, 'has been subscribed');
            smilify('success', 'User subscribed successfully');
        } else {
            $user->active = 1;
            $user->save();
            activity($user->name, 'is unsubscribed');
            smilify('success', 'User unsubscribed successfully');
        }

        return back();
    }

    //sendExpiryAlert
    public function sendExpiryAlert(Request $request, $domain)
    {
        $user = User::where('domain', $domain)->first();

        Mail::to($user->email)
            ->send(new ExpiryAlertMail($user));

        activity($user->name, 'expiry alert sent');
        smilify('success', 'Expiry alert sent successfully');

        return back();
    }

    // destroy
    public function destroy($user_id, $domain)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        DB::transaction(function () use ($user_id, $domain) {
            if (userRestriction($user_id) == 'true') {
                // delete related data in PaymentHistory Model
                PaymentHistory::where('user_id', $user_id)
                           ->delete();

                // delete related data in ItemLimitCount Model
                ItemLimitCount::where('user_id', $user_id)
                    ->delete();

                // delete related data in Subscription Model
                Subscription::where('user_id', $user_id)
                    ->delete();

                // delete related data in User Model
                User::where('role', 'customer')
                    ->where('id', $user_id)
                    ->delete();

                activity($domain, 'domain expiry alert sent');
                smilify('success', 'User deleted successfully');
            } else {
                smilify('error', 'Subscribed/Active user cannot be deleted');
            }
        }, 5);

        return back();
    }

    // expel
    public function expel($user_id, $domain)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        DB::transaction(function () use ($user_id, $domain) {
            // delete related data in PaymentHistory Model
            PaymentHistory::where('user_id', $user_id)
                           ->delete();

            // delete related data in ItemLimitCount Model
            ItemLimitCount::where('user_id', $user_id)
                    ->delete();

            // delete related data in Subscription Model
            Subscription::where('user_id', $user_id)
                    ->delete();

            // delete related data in User Model
            User::where('role', 'customer')
                    ->where('id', $user_id)
                    ->delete();
            // Delete Database MySQL
            if (env('DEVELOPMENT_MODE') == 'NO') {
                //tenant database name
                $database_name = env('DB_PREFIX').$domain;
                // cpanel
                $api = new cPanelApi(env('YOUR_DOMAIN'), env('CPANEL_USERNAME'), env('CPANEL_PASSWORD'));
                $api->deleteDataBaseMySQL($database_name);
            }
            activity($domain, 'domain expeled');
            smilify('success', 'User expeled successfully');
        }, 5);

        return back();
    }

    /**
     * login_as
     */
    public function login_as($user_id)
    {
        try {
            $user = User::find($user_id);
            auth()->login($user);
            smilify('succrss', 'Login as '.$user->name.' successfully');
            return redirect()->route('backend');
        } catch (\Throwable $th) {
            smilify('error', 'Login failed');
            return back()->withErrors($th->getMessage());
        }
    }

    //ENDS HERE
}
