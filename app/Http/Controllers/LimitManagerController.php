<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\ItemLimitCount;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Session;
use Str;

class LimitManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $client = User::where('id', $user_id)->with(['subscription', 'item_limit_count', 'payment_histories'])->first();

        return view('backend.client.limit_manager.index', compact('client'));
    }

    /**
     * update a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        // validate
        $request->validate([
            'credit' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
        ], [
            'credit.required' => 'Please enter credit',
            'start_at.required' => 'Please select start date.',
            'end_at.required' => 'Please select end date.',
        ]);

        if ($request->add_payment == 1) {
            $request->validate([
                'amount' => 'required',
                'trx_id' => 'required',
                'payment_gateway' => 'required',
            ], [
                'amount.required' => 'Please enter amount.',
                'trx_id.required' => 'Please enter transaction id.',
                'payment_gateway.required' => 'Please select payment gateway.',
            ]);
        }

        DB::transaction(function () use ($request, $user_id) {
            $start_at = Str::replace('-', '/', $request->start_at);
            $end_at = Str::replace('-', '/', $request->end_at);

            $subscription = Subscription::where('user_id', $user_id)->first();
            $subscription->emails = null;
            $subscription->credit = $request->credit;
            $subscription->sms = null;
            $subscription->start_at = Carbon::parse($start_at);
            $subscription->end_at = Carbon::parse($end_at);

            $item_limit_count = ItemLimitCount::where('user_id', $user_id)->first();
            $item_limit_count->emails = null;
            $item_limit_count->sms = null;
            $item_limit_count->all_time_credit = $subscription->credit + $item_limit_count->all_time_credit;
            $item_limit_count->credit = $subscription->credit;

            if ($request->add_payment == 1) {
                $paymentHistory = new PaymentHistory;
                $paymentHistory->user_id = $user_id;
                $paymentHistory->invoice = invoiceNumber();
                $paymentHistory->domain = getUserInfo($user_id)->email;
                $paymentHistory->subscription_id = $subscription->id;
                $paymentHistory->package_id = null;
                $paymentHistory->amount = $request->amount;
                $paymentHistory->trx_id = $request->trx_id;
                $paymentHistory->payment_gateway = $request->payment_gateway;
                $paymentHistory->payment_status = 'paid';
                $paymentHistory->start_at = $subscription->start_at;
                $paymentHistory->end_at = $subscription->end_at;
                $paymentHistory->save();

                $subscription->active = 1;
                $subscription->payment_status = 'paid';
                $subscription->payment_gateway = $paymentHistory->payment_gateway;
                $subscription->amount = $paymentHistory->amount;

                // SENDING EMAIL
                $request->session()->put('renew_subscription_details', $paymentHistory); // session for subscription

                // session invoice
                $invoiceSession = Session::put('invoice', $paymentHistory->invoice);

                // Invoice PDF
                $pdf = PDF::loadView('frontend.success.attachment_invoice', [
                    'details' => $paymentHistory,
                ])->save(invoice_path($paymentHistory->invoice));

                // InvoiceMail
                Mail::to(getUserInfo($user_id)->email)->queue(new InvoiceMail($paymentHistory));
            }

            $subscription->save();
            $item_limit_count->save();

            activity(getUserInfo($user_id)->name, ' subscription limit updated');
        }, 5);

        smilify('success', 'Limit updated successfully');

        return back();
    }
    //ENDS HERE
}
