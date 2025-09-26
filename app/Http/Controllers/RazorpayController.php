<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\DomainInvoiceMail;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use Illuminate\Support\Str;
use App\Mail\InvoiceMail;
use Razorpay\Api\Api;
use App\Models\User;
use Mail;
use PDF;
use Session;

class RazorpayController extends Controller
{

    /**
     * INDEX
     */

     public function index()
     {
        return view('backend.payment_gateways.razorpay.create');
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

        overWriteEnvFile('RAZORPAY_KEY', $request->paystack_public_key);
        overWriteEnvFile('RAZORPAY_SECRET', $request->paystack_secret_key);

        if ($request->PAYSTACK == 1) {
            overWriteEnvFile('RAZORPAY', 'YES');
        } else {
            overWriteEnvFile('RAZORPAY', 'NO');
        }
        
        activity('Razorpay', 'updated razorpay payment gateway settings');
        smilify('success', 'Razorpay payment gateway settings updated successfully');

        Artisan::call('optimize:clear');

        return back();
    }

    /**
     * Hostpage
     */
    public function hostpage(Request $request)
    {

        if (Session::has('invoice')) {

            $receiptId = Session::get('invoice');

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            //Create Order with user inputted amount
            $order = $api->order->create(array(
                'receipt' => $receiptId,
                'amount' => getPayableAmountFromInvoice(Session::get('invoice'), 'INR') * 100,
                'currency' => 'INR'
                )
            );

            // Return response on payment page
            $response = [
                'orderId' => $order['id'],
                'razorpayId' => env('RAZORPAY_KEY'),
                'amount' => getPayableAmountFromInvoice(Session::get('invoice'), 'INR') * 100, // Amount in the smallest currency unit
                'name' => getUserInfoFromInvoice(Session::get('invoice'))->name,
                'currency' => 'INR',
                'email' => getUserInfoFromInvoice(Session::get('invoice'))->email,
                'contactNumber' => getUserInfoFromInvoice(Session::get('invoice'))->phone,
                'address' => getUserInfoFromInvoice(Session::get('invoice'))->rest_address,
                'description' => 'Payment for invoice #' . $receiptId,
            ];
            return view('frontend.gateways.razorpay.index', compact('response'));
        } else {
            smilify('success', 'Something went wrong');

            return redirect()->route('frontend');
        }
    }


    public function MakePayment(Request $request){
        //Let's validate
        $paymentStatus = $this->ValidateOrderID(
            $request->all()['rzp_signature'],
            $request->all()['rzp_paymentid'],
            $request->all()['rzp_orderid']
        );

        $subscription_details = Session::get('subscription_details');

        // invoice --------------------------------------------------------------------------------------------
        $invoice = $subscription_details->invoice;
        $payment = PaymentHistory::where('invoice', $invoice)->first();
        // customer info
        $user = User::where('id', $payment->user_id)->first();
        // invoice::end ---------------------------------------------------------------------------------------

        if($paymentStatus == true)
        {
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
            $subscription->payment_gateway = 'rezorpay';
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
        }
        else{
            $paymentHistory = PaymentHistory::where('subscription_id', $payment->subscription_id)->first();
            $paymentHistory->payment_status = 'canceled';
            $paymentHistory->payment_gateway = 'rezorpay';
            $paymentHistory->save();

            return redirect()->route('renew.subscriber.failed'); // canceled blade
        }
    }

    private function ValidateOrderID($signature,$paymentId,$orderId)
    {
        try
        {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $attributes  = array('razorpay_signature'  => $signature,  'razorpay_payment_id'  => $paymentId ,  'razorpay_order_id' => $orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            return true;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
    
    //ENDS
}
