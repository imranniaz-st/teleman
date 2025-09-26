<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Package;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use Auth;
use DB;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Session;

class RenewController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('frontend.renew.create');
    }

    public function store(Request $request)
    {

        /**
         * Check if the user is Admin
         */
        if (Auth::user()->role == 'admin') {
            smilify('warning', 'You are not allowed to perform this action');

            return back();
        }

        // check package trial
        $package = Package::where('id', $request->package_id)
                          ->where('active', 1)
                          ->first();

        if (checkUserTrialUsed(Auth::user()->id) != 'true' || $package->trial != 1) {
            DB::transaction(function () use ($request) {
                // subscribe
                $subscription = Subscription::where('user_id', Auth::user()->id)
                                            ->first();

                // PaymentHistory
                $paymentHistory = new PaymentHistory;
                $paymentHistory->user_id = Auth::user()->id;
                $paymentHistory->invoice = invoiceNumber();
                $paymentHistory->domain = Auth::user()->email;
                $paymentHistory->subscription_id = $subscription->id;
                $paymentHistory->package_id = $request->package_id;
                $paymentHistory->amount = getPackagePrice($request->package_id);
                $paymentHistory->trx_id = generateRandomString();
                $paymentHistory->payment_gateway = env('PAYMENT_GATEWAY');
                $paymentHistory->payment_status = 'pending';
                $paymentHistory->start_at = packageStartEndDate($request->package_id)['start_date'];
                $paymentHistory->end_at = packageStartEndDate($request->package_id)['end_date'];
                $paymentHistory->save();

                $request->session()->put('renew_subscription_details', $paymentHistory); // session for subscription

                // session invoice
                $invoiceSession = Session::put('invoice', $paymentHistory->invoice);

                // Invoice PDF
                $pdf = PDF::loadView('frontend.success.attachment_invoice', [
                    'details' => $paymentHistory,
                ])->save(invoice_path($paymentHistory->invoice));

                // InvoiceMail
                Mail::to(Auth::user()->email)->queue(new InvoiceMail($paymentHistory));
            }, 5);

            activity(Auth::user()->name, 'has renew the subscription');

            if (isThisPackageIsFree($request->package_id) == true) {
                return redirect()->route('renew.subscriber.success');
            } else {
                return redirect()->route('frontend.payment.gateways');
            }
        } else {
            smilify('warning', 'You completed the trial period');

            return back();
        }
    }

    /**
     * success
     */
    public function success()
    {
        $user_details = Session::get('renew_subscription_details');

        return view('frontend.success.renew', compact('user_details'));
    }

    //ENDS HERE
}
