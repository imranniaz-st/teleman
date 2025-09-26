<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alert;
use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\Demo;
use App\Models\EmailSMSLimitRate;
use App\Models\PlanPurchased;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSentLimitPlan;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\ItemLimitCount;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Str;
use Mail;
use PayPal\Api\Payment;
use Paystack;
use PDF;
use Redirect;
use URL;
use Artisan;
use Session;

class SquadController extends Controller
{

    /**
     * Backend Interface
     */
    public function index()
    {
        return view('backend.payment_gateways.squad.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        try {
            overWriteEnvFile('SQUAD_PUBLIC_KEY', $request->squad_public_key);
            overWriteEnvFile('SQUAD_SECRET_KEY', $request->squad_secret_key);
            overWriteEnvFile('SQUAD_CURRENCY', $request->squad_currency);

            if ($request->PAYSTACK == 1) {
                overWriteEnvFile('SQUAD', 'YES');
            } else {
                overWriteEnvFile('SQUAD', 'NO');
            }
            
            activity('Squad', 'updated squad payment gateway settings');
            smilify('success', 'Squad payment gateway settings updated successfully');

            Artisan::call('optimize:clear');

            return back();
        } catch (\Throwable $th) {
            smilify('success', 'Something went wrong');
            return redirect()->route('frontend');
        }

        
    }

    /**
     * The function checks if the session has the subscription details, if it does, it gets the
     * subscription details from the session and stores it in a variable
     * 
     * @return the redirect to the route `renew.subscriber.success` which is a blade file.
     */
    public function payment_success()
    {

        try {
            /* The function checks if the session has the subscription details, if it does, it gets the
            subscription details from the session and stores it in a variable */
            if (Session::has('subscription_details')) {
                $subscription_details = Session::get('subscription_details');
            }

            /* Checking if the session has the subscription details, if it does, it gets the
            subscription details from the session and stores it in a variable */
            if (Session::has('renew_subscription_details')) {
                $subscription_details = Session::get('renew_subscription_details');
            }

            /* Getting the transaction id, amount and currency from the subscription details. */
            $tran_id = $subscription_details->trx_id;
            $amount = $subscription_details->amount;
            $currency = env('SQUAD_CURRENCY', 'USD');

            /* Getting the payment details from the payment history table. */
            $payment_details = PaymentHistory::where('trx_id', $tran_id)->first();

            //Check order status in order table against the transaction id or order id.
            $subscription = Subscription::where('id', $payment_details->subscription_id)->first();
            $subscription->package_id = $payment_details->package_id;
            $subscription->credit = package_credit($payment_details->package_id);
            $subscription->emails = null;
            $subscription->sms = null;
            $subscription->start_at = packageStartEndDate($payment_details->package_id)['start_date'];
            $subscription->end_at = packageStartEndDate($payment_details->package_id)['end_date'];
            $subscription->active = 1;
            $subscription->payment_status = 'paid';
            $subscription->payment_gateway = 'squad';
            $subscription->amount = $payment_details->amount;
            $subscription->save();

            $payment_details->payment_status = 'paid';
            $payment_details->payment_gateway = 'squad';
            $payment_details->save();

            // increment user credit
            $addition_credit = ItemLimitCount::where('user_id', $subscription->user_id)->first();
            $addition_credit->all_time_credit = $subscription->credit + $addition_credit->all_time_credit;
            $addition_credit->credit = $addition_credit->credit + $subscription->credit;
            $addition_credit->save();

            if (Session::has('subscription_details')) {
                /* Storing the payment details in the session. */
                session()->put('subscription_details', $payment_details);
                /* Getting the subscription details from the session. */
                $user_details = Session::get('subscription_details');
            }

            if (Session::has('renew_subscription_details')) {
                /* Storing the payment details in the session. */
                session()->put('renew_subscription_details', $payment_details);
                /* Getting the subscription details from the session. */
                $user_details = Session::get('renew_subscription_details');
            }

            //SEND SMS
            $user_info = User::where('id', $user_details->user_id)->first();

            /* Logging the activity of the user. */
            activity($user_info->name, 'paid '.price($amount).' via Squad Pay.');

            /* Redirecting the user to the route `renew.subscriber.success` which is a blade file. */
            return redirect()->route('renew.subscriber.success'); // success blade
        } catch (\Throwable $th) {
            smilify('success', 'Something went wrong');
            return redirect()->route('frontend');
        }
        
    }

    /**
     * The function is called when the user cancels the payment
     */
    public function payment_cancel()
    {

        try {
            /* The function checks if the session has the subscription details, if it does, it gets the
                subscription details from the session and stores it in a variable */
            if (Session::has('subscription_details')) {
                $subscription_details = Session::get('subscription_details');
            }

            /* Checking if the session has the subscription details, if it does, it gets the
            subscription details from the session and stores it in a variable */
            if (Session::has('renew_subscription_details')) {
                $subscription_details = Session::get('renew_subscription_details');
            }
            
            /* Getting the transaction id from the session. */
            $tran_id = $subscription_details->trx_id;

            /* Updating the payment status to canceled. */
            $user_details = PaymentHistory::where('trx_id', $tran_id)->first();
            $user_details->payment_status = 'canceled';
            $user_details->payment_gateway = 'squad';
            $user_details->save();

            /* Redirecting the user to the route `renew.subscriber.failed` which is a blade file. */
            return redirect()->route('renew.subscriber.failed'); // canceled blade
        } catch (\Throwable $th) {
            smilify('success', 'Something went wrong');
            return redirect()->route('frontend');
        }
        
    }
    //ENDS
}
