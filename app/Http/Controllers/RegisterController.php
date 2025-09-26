<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AccountActivationMail;
use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\ItemLimitCount;
use App\Models\Package;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use App\Rules\Recaptcha;
use DB;
use Hash;
use Mail;
use PDF;
use Session;
use Str;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        if ($slug != null) {
            $package = Package::where('slug', $slug)->first();

            return view('frontend.register.create', compact('package'));
        } else {
            smilify('error', 'Please select a plan');

            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [ // validate the request
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

        // if (teleman_config('google_recaptcha') == 'YES') {
        //     $this->validate($request, [
        //         'g-recaptcha-response' => ['required', new Recaptcha()] // new rule Recaptcha
        //     ],[
        //         'g-recaptcha-response.required' => 'Please verify that you are not a robot'
        //     ]);
        // }

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
            $user->restriction = 1;
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

            /* Generating a unique slug for the user. */
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
                $subscription->active = 0;
                $subscription->payment_status = 'pending';
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
                $paymentHistory->payment_status = 'pending';
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

                //OTPMail
                $code = $user->otp;
                Mail::to($user->email)->send(new AccountActivationMail($code));
            } catch (\Throwable $th) {
                tlog($th->getMessage());
            }

            activity($user->name, 'is the new customer of '.appName());
        }, 5);

        if (isThisPackageIsFree($request->package_id) == true) {
            return redirect()->route('renew.subscriber.success');
        } else {
            return redirect()->route('frontend.payment.gateways');
        }

        // store ends here
    }

    /**
     * success
     */
    public function success()
    {

        if (Session::has('subscription_details')) {
            $user_details = Session::get('subscription_details');
        }

        if (Session::has('renew_subscription_details')) {
            $user_details = Session::get('renew_subscription_details');
        }

        Session::forget('invoice');

        return view('frontend.success.success', compact('user_details'));
    }

    /**
     * success
     */
    public function failed()
    {
        if (Session::has('subscription_details')) {
            $user_details = Session::get('subscription_details');
        }

        if (Session::has('renew_subscription_details')) {
            $user_details = Session::get('renew_subscription_details');
        }
        Session::forget('invoice');

        return view('frontend.success.failed', compact('user_details'));
    }

    /**
     * check_domain availability
     */
    public function check_domain(Request $request)
    {
        $domain = $request->domain;
        $domain_exists = User::where('domain', $domain)->first();
        if ($domain_exists) {
            return response()->json(['status' => 'error', 'message' => 'Domain already exists']);
        } else {
            return response()->json(['status' => 'success', 'message' => 'Domain available']);
        }
    }

    // ENDS HERE
}
