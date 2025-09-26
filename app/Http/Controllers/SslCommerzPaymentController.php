<?php

namespace App\Http\Controllers;

use App\Library\Sslcommerz\SslCommerzNotification;
use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Session;

class SslCommerzPaymentController extends Controller
{
    // setup payment
    public function setup()
    {
        return view('backend.settings.sslcommerz.setup');
    }

    // setup payment
    public function update(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        overWriteEnvFile('API_DOMAIN_URL', $request->API_DOMAIN_URL);
        overWriteEnvFile('STORE_ID', $request->STORE_ID);
        overWriteEnvFile('STORE_PASSWORD', $request->STORE_PASSWORD);
        if ($request->SSL_COMMERZ == 1) {
            overWriteEnvFile('SSL_COMMERZ', 'YES');
        } else {
            overWriteEnvFile('SSL_COMMERZ', 'NO');
        }
        activity('SSL COMMERZ', 'Setup done');
        smilify('success', 'SSL COMMERZ setup done');

        return back();
    }

    // payment blade
    public function index(Request $request)
    {
        $invoice = Session::get('invoice');

        $payment = PaymentHistory::where('invoice', $invoice)->first();

        // Here you have to receive all the order data to initate the payment.
        // Let's say, your oder transaction informations are saving in a table called "orders"
        // In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        // Here you have to receive all the order data to initate the payment.
        // Let's say, your oder transaction informations are saving in a table called "orders"
        // In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        // customer info
        $user = User::where('id', $payment->user_id)->first();

        /**
         * DB::Storing to PaymentHistory Table
         */
        $paymentHistory = PaymentHistory::where('id', $payment->id)->first();
        $paymentHistory->payment_status = 'pending';
        $paymentHistory->payment_gateway = 'sslcommerz';
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

        // Store To DB into Order:END

        $post_data = [];
        $post_data['total_amount'] = getPayableAmountInBDT($invoice); // You cant not pay less than 10
        $post_data['currency'] = 'BDT';
        $post_data['tran_id'] = $paymentHistory->trx_id; // tran_id must be unique

        // CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name;
        $post_data['cus_email'] = $user->email;
        $post_data['cus_add1'] = $user->rest_address;
        $post_data['cus_add2'] = $user->rest_address;
        $post_data['cus_city'] = $user->city;
        $post_data['cus_state'] = '';
        $post_data['cus_postcode'] = $user->zip;
        $post_data['cus_country'] = $user->country;
        $post_data['cus_phone'] = $user->phone;
        $post_data['cus_fax'] = '';

        // SHIPMENT INFORMATION
        $post_data['ship_name'] = appName();
        $post_data['ship_add1'] = $user->rest_address;
        $post_data['ship_add2'] = $user->rest_address;
        $post_data['ship_city'] = $user->city;
        $post_data['ship_state'] = $user->city;
        $post_data['ship_postcode'] = $user->zip;
        $post_data['ship_phone'] = $user->phone;
        $post_data['ship_country'] = $user->country;

        $post_data['shipping_method'] = 'ssl-commerz';
        $post_data['product_name'] = 'Goods';
        $post_data['product_category'] = 'Goods';
        $post_data['product_profile'] = 'goods';

        // OPTIONAL PARAMETERS
        $post_data['value_a'] = 'ref001';
        $post_data['value_b'] = 'ref002';
        $post_data['value_c'] = 'ref003';
        $post_data['value_d'] = 'ref004';

        $sslc = new SslCommerzNotification();
        // initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (! is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = [];
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        $payment_details = PaymentHistory::where('trx_id', $tran_id)->first();

        $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

        /*
        That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
        in order table as Processing or Complete.
        Here you can also sent sms or email for successfull transaction to customer
        */

        //Check order status in order tabel against the transaction id or order id.

        $subscription = Subscription::where('id', $payment_details->subscription_id)->first();
        $subscription->package_id = $payment_details->package_id;
        $subscription->credit = package_credit($payment_details->package_id);
        $subscription->emails = null;
        $subscription->sms = null;
        $subscription->start_at = packageStartEndDate($payment_details->package_id)['start_date'];
        $subscription->end_at = packageStartEndDate($payment_details->package_id)['end_date'];
        $subscription->active = 1;
        $subscription->payment_status = 'paid';
        $subscription->payment_gateway = 'sslcommerz';
        $subscription->amount = $payment_details->amount;
        $subscription->save();

        $payment_details->payment_status = 'paid';
        $payment_details->payment_gateway = 'sslcommerz';
        $payment_details->save();

        // increament user credit
        $addition_credit = ItemLimitCount::where('subscription_id', $subscription->id)->first();
        $addition_credit->all_time_credit = $subscription->credit + $addition_credit->all_time_credit;
        $addition_credit->credit = $addition_credit->credit + $subscription->credit;
        $addition_credit->save();

        $request->session()->put('subscription_details', $payment_details);

        $user_details = Session::get('subscription_details');

        //SEND SMS
        $user_info = User::where('id', $user_details->user_id)->first();

        activity($user_info->name, 'paid '.price($amount).' via SSL COMMERZ.');

        return redirect()->route('renew.subscriber.success'); // success blade
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $user_details = PaymentHistory::where('trx_id', $tran_id)->first();
        $user_details->payment_status = 'failed';
        $user_details->payment_gateway = 'sslcommerz';
        $user_details->save();

        return redirect()->route('renew.subscriber.failed'); // canceled blade
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $user_details = PaymentHistory::where('trx_id', $tran_id)->first();
        $user_details->payment_status = 'canceled';
        $user_details->payment_gateway = 'sslcommerz';
        $user_details->save();

        return redirect()->route('renew.subscriber.failed'); // canceled blade
    }

    public function ipn(Request $request)
    {
        //Received all the payement information from the gateway
        if ($request->input('tran_id')) { //Check transation id is posted or not.
            $tran_id = $request->input('tran_id');

            //Check order status in order tabel against the transaction id or order id.

            $payment_details = PaymentHistory::where('trx_id', $tran_id)->first();

            if (Session::has('subscription_details')) {
                $request->session()->put('subscription_details', $payment_details);
                $user_details = Session::get('subscription_details');
            }

            if (Session::has('renew_subscription_details')) {
                $request->session()->put('renew_subscription_details', $paymentHistory);
                $user_details = Session::get('renew_subscription_details');
            }

            if ($payment_details->payment_status == 'pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $payment_details->amount, $payment_details->currency, $request->all());
                if ($validation == true) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('payment_histories')
                            ->where('trx_id', $tran_id)
                            ->update(['payment_status' => 'confirmed']);

                    return redirect()->route('renew.subscriber.success', compact('user_details')); // success blade
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('payment_histories')
                            ->where('trx_id', $tran_id)
                            ->update(['payment_status' => 'canceled']);

                    smilify('Validation Fail', 'Try Again');

                    return redirect()->route('renew.subscriber.failed'); // canceled blade
                }
            } elseif ($order_details->payment_status == 'confirmed') {

                    //That means Order status already updated. No need to udate database.
                smilify('Transaction is already successfully Completed', 'Thank you');

                return redirect()->route('renew.subscriber.success', compact('user_details')); // success blade
            } else {
                //That means something wrong happened. You can redirect customer to your product page.

                smilify('Invalid Transaction', 'Try Again');

                return redirect()->route('renew.subscriber.failed'); // canceled blade
            }
        } else {
            smilify('Invalid Data', 'Try Again');

            return redirect()->route('renew.subscriber.failed'); // canceled blade
        }
    }
}
