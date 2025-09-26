<?php

namespace App\Http\Controllers;

use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Session;
use Stripe;

class StripeController extends Controller
{
    // index
    public function index()
    {
        return view('backend.settings.stripe.setup');
    }

    // setup payment
    public function update(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        overWriteEnvFile('STRIPE_ENVIRONMENT', $request->STRIPE_ENVIRONMENT);
        overWriteEnvFile('STRIPE_KEY', $request->STRIPE_KEY);
        overWriteEnvFile('STRIPE_SECRET', $request->STRIPE_SECRET);
        if ($request->STRIPE == 1) {
            overWriteEnvFile('STRIPE', 'YES');
        } else {
            overWriteEnvFile('STRIPE', 'NO');
        }
        activity('Stripe', 'Setup done');
        smilify('success', 'Stripe setup done');

        return back();
    }

    /**
     * PAYMENT
     */
    public function stripe()
    {
        if (Session::has('invoice')) {
            return view('frontend.gateways.stripe.index');
        } else {
            smilify('success', 'Something went wrong');

            return redirect()->route('frontend');
        }
    }

    // stripePost
    public function stripePost(Request $request)
    {
        try {
        $subscription_details = Session::get('renew_subscription_details');

        // invoice --------------------------------------------------------------------------------------------
        $invoice = $request->invoice;
        $payment = PaymentHistory::where('invoice', $invoice)->first();
        // customer info
        $user = User::where('id', $payment->user_id)->first();
        // invoice::end ---------------------------------------------------------------------------------------

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge = Stripe\Charge::create([
            'amount' => $payment->amount * 100,
            'currency' => 'usd',
            'source' => $request->stripeToken,
            'description' => '[#'.$invoice.'] '.$user->name.' made a transaction of $'.$payment->amount.' for the '.appName().' '.$request->package_name.'  Plan',
        ]);

        if ($charge->paid == true) {
            // DB::store --------------------------------------------------------------------------------------------

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
            $subscription->payment_gateway = $charge->calculated_statement_descriptor;
            $subscription->amount = $payment->amount;
            $subscription->save();

            // increament user credit
            $addition_credit = ItemLimitCount::where('subscription_id', $subscription->id)->first();
            $addition_credit->all_time_credit = $subscription->credit + $addition_credit->all_time_credit;
            $addition_credit->credit = $addition_credit->credit +  $subscription->credit;
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
            Mail::to($user->email)->queue(new InvoiceMail(['paymentHistory' => $subscription_details]));

            // DomainInvoiceMail
            Mail::to($user->email)->queue(new DomainInvoiceMail(['paymentHistory' => $subscription_details]));
            // DB::store END ------------------------------------------------------------------------------------------------

            return redirect()->route('renew.subscriber.success'); // success blade
        } else {
            $errorString = '';

            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: '.$error->code.': '.$error->message."\n";
            }

            $paymentHistory = PaymentHistory::where('subscription_id', $payment->subscription_id)->first();
            $paymentHistory->payment_status = 'canceled';
            $paymentHistory->payment_gateway = $charge->calculated_statement_descriptor;
            $paymentHistory->save();

            return redirect()->route('renew.subscriber.failed'); // canceled blade
        }
        } catch (\Throwable $th) {
            smilify('success', 'Something went wrong');

            return redirect()->route('frontend');
        }
    }
    //ENDS
}
