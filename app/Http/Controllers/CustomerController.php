<?php

namespace App\Http\Controllers;

use App\Exports\PaymentHistoriesExport;
use App\Models\User;
use App\Models\PaymentHistory;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    // view profile info
    public function index()
    {
        return view('backend.dashboard.customer.profile.information');
    }

    // update basic info
    public function update(Request $req)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $validated = $req->validate([
            'name' => 'required',
        ]);

        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $req->name;
        if ($user->phone != $req->phone) {
            $validated = $req->validate([
                'phone' => 'required|unique:users',
            ]);
            $user->phone = $req->phone;
        }
        $user->save();
        smilify('success', 'Profile updated successfully');

        return back();
    }

    // view password
    public function changePassword()
    {
        return view('backend.profile.password');
    }

    // view update password
    public function updatePassword(Request $req)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $validated = $req->validate([
            'oldpassword' => 'required',
            'confirmpassword' => 'required|min:8',
            'newpassword' => 'required|min:8|required_with:confirmpassword|same:confirmpassword',
        ], [
            'oldpassword.required' => 'Old password is required',
            'newpassword.required' => 'New password is required',
            'confirmpassword.required' => 'Confirm password is required',
            'newpassword.min' => 'New password must be at least 8 characters',
            'confirmpassword.min' => 'Confirm password must be at least 8 characters',
            'newpassword.required_with' => 'New password and confirm password must be same',
            'confirmpassword.same' => 'New password and confirm password must be same',
        ]);

        $user = User::where('id', Auth::user()->id)->first();

        if (Hash::check($req->oldpassword, Auth::user()->password)) {
            $user->password = Hash::make($req->newpassword);
            $user->save();
            smilify('success', 'Password changed successfully');
            auth()->logout();

            return back();
        } else {
            smilify('error', 'Password do not match');

            return back();
        }
    }

    // billing
    public function billing()
    {
        return view('backend.dashboard.customer.profile.billing');
    }

    // billingHistory
    public function billingHistory()
    {
        return view('backend.dashboard.customer.profile.billing_history');
    }

    // invoice
    public function invoice($invoice)
    {
        try {
            return response()->download(public_path('invoice_pdf/'.$invoice.'.pdf'));
        } catch (\Throwable $th) {
            smilify('Hi,', 'No invoice found. Please check your email.');

            return back();
        }
    }

    // mySubscription
    public function mySubscription()
    {
        return view('backend.dashboard.customer.profile.my_subscriptions');
    }

    // accountReport
    public function accountReport($domain = null)
    {
        if (Auth::user()->role == 'admin') {
            $domain = User::where('domain', $domain)->first()->domain;
        } else {
            $domain = User::where('domain', Auth::user()->domain)->first()->domain;
        }

        return view('backend.dashboard.customer.profile.account_report', compact('domain'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new PaymentHistoriesExport, 'payment_histories.csv');
    }

    /**
     * billingHistoryDelete
     */
    public function billingHistoryDelete($id)
    {

        try {
            $history = PaymentHistory::where('id', $id)->first();
            $history->delete();

            smilify('success', 'Payment history deleted');
            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong.');
            return back();
        }
        
    }

    // ENDS HERE
}
