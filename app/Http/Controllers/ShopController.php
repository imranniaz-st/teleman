<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\ItemLimitCount;
use Illuminate\Support\Facades\Artisan;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.shops.index');
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

       /* This is validating the request. */
        $this->validate($request, [
            'country' => 'required',
            'phone' => 'required|max:255|unique:shops',
            'credit_cost' => 'required|min:1|numeric',
        ], [
            'phone.required' => 'Please enter your phone number',
            'phone.unique' => 'This phone number is already registered',
        ]);

        /* Removing the spaces from the phone number. */
        $spaceless_number = str_replace(' ', '', $request->phone);

       /* This is checking if the phone number has a plus sign. If it does, it will return the number.
       If it doesn't, it will add a plus sign to the number. */
        if (str_contains($spaceless_number, '+')) {
            $number = $spaceless_number;
        }else {
            $number = '+' . $spaceless_number;
        }

        /* This is creating a new shop and saving it to the database. */
        $shop = new Shop;
        $shop->country = $request->country;
        $shop->phone = $number;
        $shop->credit_cost = $request->credit_cost;
        $shop->released = 1; // true
        $shop->confirmed = 0; // false
        $shop->save();

        smilify('success', 'New phone number added');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        /* This is updating the shop. */
        $shop = Shop::where('id', $id)->first();
        $shop->country = $request->country;
        $shop->phone = str_replace(' ', '', $request->phone);
        $shop->credit_cost = $request->credit_cost;
        $shop->released = 1; // true
        $shop->save();

        smilify('success', $shop->phone . ' number updated');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        /* Getting the shop from the database. */
        $shop = Shop::where('id', $id)->first();

        /* This is checking if the shop has a user id. If it does, it will return a warning message. */
        if ($shop->purchased_user_id) {
            smilify('warning', 'This number is belongs to ' . getUserInfo($shop->purchased_user_id)->name);
            return back();
        }

        /* Deleting the shop from the database. */
        $shop->delete();

        smilify('success', 'Phone number deleted');
        return back();
    }

    /**
     * It checks if the user has already purchased the number, if not, it checks if the user has enough
     * credit to purchase the number, if yes, it deducts the credit from the user and saves the number
     * to the user.
     * 
     * @param id The id of the phone number you want to purchase.
     */
    public function purchase($id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        /* Getting the phone number from the database. */
        $number = Shop::where('id', $id)->first();

        /* Checking if the user has already purchased the number. If yes, it will return a warning
        message. */
        if (check_purchased_user($number->phone) == true) {
            smilify('error', 'Already purchased');
            return back();
        }

        /* Checking if the user has enough credit to purchase the number. If the user doesn't have
        enough credit, it will return a warning message. */
        if (str_replace(',', '', user_current_credit(auth()->id())) < $number->credit_cost) {
            smilify('error', 'insufficient balance');
            return back();
        }

        /* Checking if the number is available. If it is, it will deduct the credit from the user and
        save the number to the user. If it isn't, it will return a warning message. */
        if ($number) {
            /* Getting the user credit from the database. */
            $user_credit = ItemLimitCount::where('user_id', auth()->id())->first();
            /* Deducting the credit from the user. */
            $user_credit->credit = $user_credit->credit - $number->credit_cost;

            /* Checking if the user credit is saved. If it is, it will save the number to the user. */
            if ($user_credit->save()) {
                $number->purchased_user_id = auth()->id();
                $number->start_at = null;
                $number->end_at = null;
                $number->confirmed = 0;
                $number->save();
            }

        }else {
            /* Returning a warning message and redirecting the user back to the previous page. */
            smilify('error', 'Phone number is unavailable.');
            return back();
        }

        Artisan::call('optimize:clear');

        /* Returning a success message and redirecting the user back to the previous page. */
        smilify('success', $number->phone . ' Ordered successfully');
        return back();
    }

    /**
     * It returns a view called `backend.shops.purchased`
     * 
     * @return A view called purchased.blade.php
     */
    public function purchased_numbers()
    {
        return view('backend.shops.purchased');
    }

    /**
     * It checks if the user has purchased the number, if yes, it will revoke the number
     * 
     * @param id The id of the shop.
     * 
     * @return the view of the page.
     */
    public function revoke_number($id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        /* Checking if the user is an admin, if yes, it will get the phone number from the database. If
        not, it will get the phone number from the database and check if the user has purchased the
        number. */
        if (is_admin(auth()->id())) {
            /* Getting the phone number from the database. */
            $shop = Shop::where('id', $id)
                        ->first();
        }else {
            /* Getting the phone number from the database. */
            $shop = Shop::where('id', $id)
                        ->where('purchased_user_id', auth()->id())
                        ->first();
        }

        /* Checking if the user has already purchased the number. If yes, it will revoke the number. */
        if (check_purchased_user($shop->phone) == true) {
            $shop->purchased_user_id = null;
            $shop->start_at = null;
            $shop->end_at = null;
            $shop->confirmed = 0; // false

            if ($shop->save()) {
                $provider = Provider::where('phone', $shop->phone)->delete();
            }

            smilify('success', $shop->phone . ' revoked');
            return back();
        }else {
            smilify('error', 'Please purchase the number first.');
            return back();
        }
    }

    public function renew_number($id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        /* Getting the phone number from the database. */
        $number = Shop::where('id', $id)
                      ->where('purchased_user_id', auth()->id())
                      ->first();

        /* Checking if the number is available. If it is, it will deduct the credit from the user and
        save the number to the user. If it isn't, it will return a warning message. */
        if ($number) {

            /* Checking if the user has enough credit to purchase the number. If the user doesn't have
            enough credit, it will return a warning message. */
            if (str_replace(',', '', user_current_credit(auth()->id())) < $number->credit_cost) {
                smilify('error', 'insufficient balance');
                return back();
            }

            /* Checking if the user has already purchased the number. If yes, it will return a warning
            message. */
            if (check_purchased_user($number->phone) == true) {
            
                /* Getting the user credit from the database. */
                $user_credit = ItemLimitCount::where('user_id', auth()->id())->first();
                /* Deducting the credit from the user. */
                $user_credit->credit = $user_credit->credit - $number->credit_cost;

                /* Checking if the user credit is saved. If it is, it will save the number to the user. */
                if ($user_credit->save()) {
                    $number->start_at = Carbon::now();
                    $number->renew = 1; // true
                    $number->save();
                }

            }else {
                /* Returning a warning message and redirecting the user back to the previous page. */
                smilify('error', 'Please purchase the number first.');
                return back();
            }

        }else {
            /* Returning a warning message and redirecting the user back to the previous page. */
            smilify('error', 'Phone number is unavailable.');
            return back();
        }

        /* Returning a success message and redirecting the user back to the previous page. */
        smilify('success', $number->phone . ' renew successfully');
        return back();
    }

    /**
     * It checks if the number is available, if it is, it will deduct the credit from the user and save
     * the number to the user. If it isn't, it will return a warning message
     * 
     * @param id The id of the phone number.
     * 
     * @return the number.
     */
    public function accept($id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }
        
        /* Getting the phone number from the database. */
        $number = Shop::where('id', $id)
                      ->first();

        /* Checking if the number is available. If it is, it will deduct the credit from the user and
                save the number to the user. If it isn't, it will return a warning message. */
        if ($number) {

            /* Setting the start_at, end_at and confirmed to 1. */
            $number->start_at = Carbon::now();
            $number->end_at = Carbon::now()->addMonth();
            $number->confirmed = 1; // true
            $number->renew = 0; // true
            $number->save();

        }else {
            /* Returning a warning message and redirecting the user back to the previous page. */
            smilify('error', 'Phone number is unavailable.');
            return back();
        }

        /* Returning a success message and redirecting the user back to the previous page. */
        smilify('success', $number->phone . ' confirmed successfully');
        return back();
    }

    /**
     * It returns the view of new_order.
     * 
     * @return A view called new_order.blade.php
     */
    public function new_orders()
    {
        return view('backend.shops.new_order');
    }

    /**
     * It returns the view of the page.
     * 
     * @return A view called 'backend.shops.renew_order'
     */
    public function renew_orders()
    {
        return view('backend.shops.renew_order');
    }

    public function configurable()
    {
        return view('backend.shops.configurable');
    }
    // ENDS
}
