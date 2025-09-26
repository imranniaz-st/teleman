<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // user_subscription_data
    public function user_subscription_data(Request $request)
    {
        $user = User::where('role', 'customer')
                    ->where('domain', trimDomain($request->domain))
                    ->with(['subscription', 'item_limit_count', 'payment_histories:invoice,user_id,payment_status,payment_gateway,amount,created_at'])
                    ->has('subscription')
                    ->first();

        $info = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'domain' => $user->domain,
            'rest_name' => $user->rest_name,
            'rest_address' => $user->rest_address,
            'created_at' => $user->created_at->diffForHumans(),
            'subscription_name' => $user->subscription->package->name,
            'total_emails' => $user->subscription->emails,
            'total_sms' => $user->subscription->sms,
            'start_at' => $user->subscription->start_at,
            'end_at' => $user->subscription->end_at,
            'payment_status' => $user->subscription->payment_status,
            'payment_gateway' => $user->subscription->payment_gateway,
            'amount' => price($user->subscription->amount),
            'subscription_date_endin' => convertdaysToWeeksMonthsYears(userSubscriptionDateEndIn(trimDomain($request->domain))),
        ];

        return response()->json($info);
    }

    // user_emails_limit
    public function user_emails_limit(Request $request)
    {
        return userEmailsLimit(trimDomain($request->domain));
    }

    // user_email_limit_check
    public function user_email_limit_check(Request $request)
    {
        return userEmailLimitCheck(trimDomain($request->domain));
    }

    // user_email_limit_left
    public function user_email_limit_left(Request $request)
    {
        return userEmailLimitLeft(trimDomain($request->domain));
    }

    // user_email_limit_decrement
    public function user_email_limit_decrement(Request $request)
    {
        return userEmailLimitDecrement(trimDomain($request->domain));
    }

    // user_subscription_date_endin
    public function user_subscription_date_endin(Request $request)
    {
        return userSubscriptionDateEndIn(trimDomain($request->domain));
    }

    // user_payment_histories
    public function user_payment_histories(Request $request)
    {
        $userPaymentHistory = PaymentHistory::where('domain', trimDomain($request->domain))
                                        ->latest()
                                        ->get();

        $payments = collect();

        foreach ($userPaymentHistory as $payment) {
            $payments->push([
                'invoice' => $payment->invoice,
                'user_id' => $payment->user_id,
                'payment_status' => $payment->payment_status,
                'payment_gateway' => $payment->payment_gateway,
                'amount' => price($payment->amount),
                'created_at' => $payment->created_at->diffForHumans(),
            ]);
        }

        return $payments;
    }

    //restriction
    public function restriction(Request $request)
    {
        return userRestriction(trimDomain($request->domain));
    }

    /**
     * BRANCH
     */
    // user_sms_limit
    public function user_sms_limit(Request $request)
    {
        return userSmsLimitDecrement(trimDomain($request->domain));
    }

    // user_sms_limit_check
    public function user_sms_limit_check(Request $request)
    {
        return userSmsLimitCheck(trimDomain($request->domain));
    }

    // user_sms_limit_left
    public function user_sms_limit_left(Request $request)
    {
        return userSmsLimitLeft(trimDomain($request->domain));
    }

    // user_sms_limit_decrement
    public function user_sms_limit_decrement(Request $request)
    {
        return userSmsLimitDecrement(trimDomain($request->domain));
    }

    //ENDS HERE
}
