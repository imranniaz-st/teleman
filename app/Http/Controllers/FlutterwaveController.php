<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Artisan;
use Auth;
use Hash;
use Mail;
use PDF;
use Str;
use Session;
use KingFlamez\Rave\Facades\Rave as Flutterwave;


class FlutterwaveController extends Controller
{
    /**
     * Backend Interface
     */
    public function index()
    {
        return view('backend.payment_gateways.flutterwave.create');
    }

    /**
     * Backend Interface
     */
    public function store(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        overWriteEnvFile('FLW_PUBLIC_KEY', $request->flutterwave_client_id);
        overWriteEnvFile('FLW_SECRET_KEY', $request->flutterwave_secret);
        overWriteEnvFile('FLW_SECRET_HASH', $request->flutterwave_hash);
        overWriteEnvFile('FLW_CURRENCY', $request->FLW_CURRENCY);

        if ($request->FLUTTERWAVE == 1) {
            overWriteEnvFile('FLUTTERWAVE', 'YES');
        } else {
            overWriteEnvFile('FLUTTERWAVE', 'NO');
        }

        activity('Flutterwave', 'updated flutterwave payment gateway settings');
        smilify('success', 'Flutterwave payment gateway settings updated successfully');

        Artisan::call('optimize:clear');

        return back();
    }

    /**
     * Initialize Rave payment process
     *
     * @return void
     */
    public function initialize()
    {
        $subscription_details = Session::get('renew_subscription_details');

        // invoice --------------------------------------------------------------------------------------------
        $invoice = Session::get('invoice');
        $paymentHistory = PaymentHistory::where('invoice', $invoice)->first();
        // customer info
        $user = User::where('id', $paymentHistory->user_id)->first();
        // invoice::end ---------------------------------------------------------------------------------------

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $flw_currency = env('FLW_CURRENCY', 'NGN');

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => getPayableAmountFromInvoice($invoice, $flw_currency),
            'email' => $user->email,
            'tx_ref' => $reference,
            'currency' => env('FLW_CURRENCY', $flw_currency),
            'redirect_url' => route('rave.callback'),
            'customer' => [
                'email' => $user->email,
                'phone_number' => $user->phone,
                'name' => $user->name,
            ],

            'customizations' => [
                'title' => Str::upper(PackageDetails($paymentHistory->package_id)->name).' Plan',
                'description' => $invoice . ' on ' . Carbon::now()->toDateTimeString(),
            ],
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            return redirect()->route('renew.subscriber.failed'); // canceled blade
        }

        return redirect($payment['data']['link']);
    }

    /**
     * Obtain Rave callback information
     *
     * @return void
     */
    public function callback()
    {
        $status = request()->status;

        //if payment is successful
        if ($status == 'successful') {
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

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
            $subscription->payment_gateway = 'flutterwave';
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
            Mail::to(getUserInfo($subscription->user_id)->email)->queue(new InvoiceMail(['paymentHistory' => $subscription_details]));

            // DomainInvoiceMail
            Mail::to(getUserInfo($subscription->user_id)->email)->queue(new DomainInvoiceMail(['paymentHistory' => $subscription_details]));
            // DB::store END ------------------------------------------------------------------------------------------------

            activity('Flutterwave', 'updated flutterwave payment gateway settings');
            smilify('success', 'Payment successful');
            return redirect()->route('renew.subscriber.success'); // success blade
        // SUCCESSFULL PAYMENT::END-------------------------------------------------------------------------------
        } elseif ($status == 'cancelled') {
            
            $paymentHistory = PaymentHistory::where('subscription_id', $payment->subscription_id)->first();
            $paymentHistory->payment_status = 'canceled';
            $paymentHistory->payment_gateway = 'flutterwave';
            $paymentHistory->save();

            smilify('success', 'Something went wrong');
            return redirect()->route('renew.subscriber.failed'); // canceled blade
        } else {
            smilify('success', 'Something went wrong');
            return redirect()->route('renew.subscriber.failed'); // canceled blade
        }
    }
    //ENDS
}