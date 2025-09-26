<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use PayPal\Api\Payment;
use Paystack;
use PDF;
use Redirect;
use URL;
use Artisan;
use Session;

class PayStackController extends Controller
{
    /**
     * Backend Interface
     */
    public function index()
    {
        return view('backend.payment_gateways.paystack.create');
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

        overWriteEnvFile('PAYSTACK_PUBLIC_KEY', $request->paystack_public_key);
        overWriteEnvFile('PAYSTACK_SECRET_KEY', $request->paystack_secret_key);
        overWriteEnvFile('PAYSTACK_PAYMENT_URL', $request->paystack_payment_url);
        overWriteEnvFile('MERCHANT_EMAIL', $request->paystack_merchant_email);
        overWriteEnvFile('MERCHANT_CURRENCY', $request->paystack_merchant_currency);

        if ($request->PAYSTACK == 1) {
            overWriteEnvFile('PAYSTACK', 'YES');
        } else {
            overWriteEnvFile('PAYSTACK', 'NO');
        }
        
        activity('Paystack', 'updated paystack payment gateway settings');
        smilify('success', 'Paystack payment gateway settings updated successfully');

        Artisan::call('optimize:clear');

        return back();
    }

    /**
     * redirectToGateway
     */
    public function redirectToGateway(Request $request)
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong. Try again later');
            return back();
        }
    }

    /**
     * handleGatewayCallback()
     */
    public function handleGatewayCallback()
    {
        try {
            $paymentDetails = Paystack::getPaymentData(); //  retrieve payment details

            if ($paymentDetails['status'] == 'success') {
                // SUCCESSFULL PAYMENT-----------------------------------------------------------------------------------
                // DB::store --------------------------------------------------------------------------------------------
                $payment = PaymentHistory::where('invoice', Session::get('invoice'))->first();
                /**
                 * DB::Storing to Subscription Table
                 */
                $subscription = Subscription::where('id', $payment->subscription_id)->first();
                $subscription->package_id = $payment->package_id;
                $subscription->credit = package_credit($payment->package_id);
                $subscription->emails = null;
                $subscription->sms = null;
                $subscription->start_at = packageStartEndDate($payment->package_id)['start_date'];
                $subscription->end_at = packageStartEndDate($payment->package_id)['end_date'];
                $subscription->active = 1;
                $subscription->payment_status = 'paid';
                $subscription->payment_gateway = 'paystack';
                $subscription->amount = $payment->amount;
                $subscription->save();

                // increament user credit
                $addition_credit = ItemLimitCount::where('subscription_id', $subscription->id)->first();
                $addition_credit->all_time_credit = $subscription->credit + $addition_credit->all_time_credit;
                $addition_credit->credit = $addition_credit->credit + $subscription->credit;
                $addition_credit->save();

                /**
                 * DB::Storing to PaymentHistory Table
                 */
                $paymentHistory = PaymentHistory::where('id', $payment->id)->first();
                $paymentHistory->payment_status = $subscription->payment_status;
                $paymentHistory->payment_gateway = $subscription->payment_gateway;
                $paymentHistory->save();

                /**
                 * Generate PDF
                 */
                if (Session::has('subscription_details')) {
                    $subscription_details = Session::get('subscription_details');
                }

                if (Session::has('renew_subscription_details')) {
                    $subscription_details = Session::get('renew_subscription_details');
                }

                // Invoice PDF
                $pdf = PDF::loadView('frontend.success.attachment_invoice', [
                    'details' => $subscription_details,
                ])->save(invoice_path($subscription_details->invoice));

                // DOmain Invoice PDF
                $pdf = PDF::loadView('frontend.success.domain_attachment_invoice', [
                    'details' => $subscription_details,
                ])->save(domain_invoice_path($subscription_details->invoice));

                // InvoiceMail
                Mail::to(getUserInfo($subscription->user_id)->email)->queue(new InvoiceMail(['paymentHistory' => $subscription_details]));

                // DomainInvoiceMail
                Mail::to(getUserInfo($subscription->user_id)->email)->queue(new DomainInvoiceMail(['paymentHistory' => $subscription_details]));
                // DB::store END ------------------------------------------------------------------------------------------------

                activity('Paystack', 'updated paystack payment gateway settings');
                smilify('success', 'Payment successful');
                return redirect()->route('renew.subscriber.success'); // success blade
            }else {
                $paymentHistory = PaymentHistory::where('subscription_id', $payment->subscription_id)->first();
                $paymentHistory->payment_status = 'canceled';
                $paymentHistory->payment_gateway = 'paystack';
                $paymentHistory->save();

                smilify('success', 'Payment canceled');
                return redirect()->route('renew.subscriber.failed'); // canceled blade
            }
        } catch (\Throwable $th) {
            $paymentHistory = PaymentHistory::where('subscription_id', $payment->subscription_id)->first();
            $paymentHistory->payment_status = 'canceled';
            $paymentHistory->payment_gateway = 'paystack';
            $paymentHistory->save();

            smilify('success', 'Payment canceled');
            return redirect()->route('renew.subscriber.failed'); // canceled blade
        }
        
    }
    //ENDS
}
