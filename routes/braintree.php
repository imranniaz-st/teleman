<?php

use App\Mail\DomainInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/hosted/payment', function () {

    $gateway = new Braintree\Gateway([ // Braintree\Gateway
        'environment' => env('BT_ENVIRONMENT'),
        'merchantId' => env('BT_MERCHANT_ID'),
        'publicKey' => env('BT_PUBLIC_KEY'),
        'privateKey' => env('BT_PRIVATE_KEY'),
    ]); // Create a new instance of the Braintree gateway

    $token = $gateway->ClientToken()->generate(); // Generate a client token

    return view('frontend.gateways.braintree.index', [
        'token' => $token,
    ]);
})->name('braintree.index'); // Braintree > Index

// checkout
Route::post('/payment/checkout', function (Request $request) {

    $gateway = new Braintree\Gateway([ // Braintree\Gateway
        'environment' => env('BT_ENVIRONMENT'),
        'merchantId' => env('BT_MERCHANT_ID'),
        'publicKey' => env('BT_PUBLIC_KEY'),
        'privateKey' => env('BT_PRIVATE_KEY'),
    ]); // Create a new instance of the Braintree gateway

    // invoice --------------------------------------------------------------------------------------------
    $invoice = Session::get('invoice'); // Get invoice from session
    $payment = PaymentHistory::where('invoice', $invoice)->first(); // Get payment history
    // customer info
    $user = User::where('id', $payment->user_id)->first(); // Get user
    // invoice::end ---------------------------------------------------------------------------------------

    $nonce = $request->payment_method_nonce;

    $result = $gateway->transaction()->sale([ // Braintree\Result\Successful or Braintree\Result\Error
        'amount' => getPayableAmountFromInvoice(Session::get('invoice'), 'USD'), // Amount in cents
        'paymentMethodNonce' => $nonce,
        'customer' => [ // Braintree\Customer
            'firstName' => $user->name,
            'email' => $user->email,
        ],
        'options' => [ // Braintree\Transaction\Options
            'submitForSettlement' => true,
        ],
    ]);

    if ($result->success) { // If transaction is successful
        $transaction = $result->transaction;

        // DB::store --------------------------------------------------------------------------------------------

        /**
         * DB::Storing to Subscription Table
         */
        $subscription = Subscription::where('id', $payment->subscription_id)->first(); // Get subscription
        $subscription->package_id = $payment->package_id;
        $subscription->emails = getPackageItems($payment->package_id);
        $subscription->sms = getPackageBranch($payment->package_id);
        $subscription->start_at = packageStartEndDate($payment->package_id)['start_date'];
        $subscription->end_at = packageStartEndDate($payment->package_id)['end_date'];
        $subscription->active = 1;
        $subscription->payment_status = 'paid';
        $subscription->payment_gateway = $transaction->creditCard['cardType'];
        $subscription->amount = $payment->amount;
        $subscription->save();

        /**
         * DB::Storing to PaymentHistory Table
         */
        $paymentHistory = PaymentHistory::where('id', $payment->id)->first(); // Get payment history
        $paymentHistory->payment_status = $subscription->payment_status;
        $paymentHistory->payment_gateway = $subscription->payment_gateway;
        $paymentHistory->save();

        /**
         * Generate PDF
         */
        if (Session::has('subscription_details')) { // If subscription details is set in session
            $subscription_details = Session::get('subscription_details');
        }

        if (Session::has('renew_subscription_details')) { // If subscription details is set in session
            $subscription_details = Session::get('renew_subscription_details');
        }

        // Invoice PDF
        $pdf = PDF::loadView('frontend.success.attachment_invoice', [ // Load view
            'details' => $subscription_details,
        ])->save(invoice_path($subscription_details->invoice)); // Save PDF to file

        // DOmain Invoice PDF
        $pdf = PDF::loadView('frontend.success.domain_attachment_invoice', [ // Load view
            'details' => $subscription_details,
        ])->save(domain_invoice_path($subscription_details->invoice)); // Save PDF to file

        // InvoiceMail
        Mail::to($user->email)->queue(new InvoiceMail(['paymentHistory' => $subscription_details])); // Send email to user

        // DomainInvoiceMail
        Mail::to($user->email)->queue(new DomainInvoiceMail(['paymentHistory' => $subscription_details])); // Send email to user
        // DB::store END ------------------------------------------------------------------------------------------------

        return redirect()->route('renew.subscriber.success'); // success blade
    } else {
        $errorString = ''; // Initialize error string

        foreach ($result->errors->deepAll() as $error) { // Loop through all errors
            $errorString .= 'Error: '.$error->code.': '.$error->message."\n"; // Add error to string
        }

        $paymentHistory = PaymentHistory::where('subscription_id', $payment->subscription_id)->first(); // Get payment history
        $paymentHistory->payment_status = 'canceled';
        $paymentHistory->payment_gateway = $transaction->creditCard['cardType'];
        $paymentHistory->save();

        return redirect()->route('renew.subscriber.failed'); // canceled blade
    }
})->name('braintree.checkout'); // Braintree > Checkout
//Braintree END
