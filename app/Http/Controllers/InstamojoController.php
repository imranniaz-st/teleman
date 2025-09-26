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

class InstamojoController extends Controller
{
    /**
     * Backend Interface
     */
    public function index()
    {
        return view('backend.payment_gateways.instamojo.create');
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

        overWriteEnvFile('IM_API_KEY', $request->instamojo_api_key);
        overWriteEnvFile('IM_AUTH_TOKEN', $request->instamojo_auth_token);
        overWriteEnvFile('IM_URL', $request->instamojo_url);

        if ($request->INSTAMOJO == 1) {
            overWriteEnvFile('INSTAMOJO', 'YES');
        } else {
            overWriteEnvFile('INSTAMOJO', 'NO');
        }

        activity('Instamojo', 'updated instamojo payment gateway settings');
        smilify('success', 'Instamojo payment gateway settings updated successfully');

        Artisan::call('optimize:clear');

        return back();
    }

    /**
     * PAY
     */
    public function pay(Request $request)
    {
 
        $api = new \Instamojo\Instamojo(
                env('IM_API_KEY'),
                env('IM_AUTH_TOKEN'),
                env('IM_URL')
            );

        $subscription_details = Session::get('renew_subscription_details');

        // invoice --------------------------------------------------------------------------------------------
        $invoice = Session::get('invoice');
        $paymentHistory = PaymentHistory::where('invoice', $invoice)->first();
        // customer info
        $user = User::where('id', $paymentHistory->user_id)->first();
        // invoice::end ---------------------------------------------------------------------------------------
    
        try {
            $response = $api->createPaymentRequest(array(
                "purpose" => "FIFA 16",
                "amount" => 60,
                "buyer_name" => "Prince",
                "send_email" => true,
                "email" => "mprince2k16@gmail.com",
                "phone" => "9178978897",
                "redirect_url" => route('instamojo.success')
                ));
                
                header('Location: ' . $response['longurl']);
                exit();
        }catch (Exception $e) {
            smilify('error', $e->getMessage());
            return back();
        }
    }

    /**
     * SUCCESS
     */
    public function success(Request $request)
    {
         try {
 
            $api = new \Instamojo\Instamojo(
                env('IM_API_KEY'),
                env('IM_AUTH_TOKEN'),
                env('IM_URL')
            );
    
            $response = $api->paymentRequestStatus(request('payment_request_id'));
    
            if( !isset($response['payments'][0]['status']) ) {
            dd('payment failed');
            } else if($response['payments'][0]['status'] != 'Credit') {
                dd('payment failed');
            } 
        }catch (\Exception $e) {
            dd('payment failed');
        }
        dd($response);
    }
    //ENDS
}
