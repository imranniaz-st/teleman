<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThirdParty;
use App\Models\Demo;
use App\Models\Contact;
use Auth;
use Str;

class WordPressController extends Controller
{

    /**
     * INDEX 
     */
    public function index()
    {
        $wordpress = ThirdParty::where('application_name', 'wordpress')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if (!$wordpress) {
            session()->forget('wordpress');
        }

        return view('addons.woocommerce.index', compact('wordpress'));
    }

    // index
    public function store(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $wordpress = ThirdParty::where('application_name', 'wordpress')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if ($wordpress) {
            $update_wordpress = ThirdParty::where('user_id', Auth::user()->id)->first();
            $update_wordpress->user_id = Auth::id();
            $update_wordpress->application_name = 'wordpress';
            $update_wordpress->application_url = $request->application_url;
            $update_wordpress->user_email = $request->user_email;
            $update_wordpress->save();
        }else {
            $new_wordpress = new ThirdParty;
            $new_wordpress->user_id = Auth::id();
            $new_wordpress->application_name = 'wordpress';
            $new_wordpress->application_url = $request->application_url;
            $new_wordpress->user_email = $request->user_email;
            $new_wordpress->save();
        }
        
        smilify('success', 'Successfully saved');
        return back();
        
    }

    /**
     * generate_token
     */
    public function generate_token(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        try {

        $token = generate_token();

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $wordpress = ThirdParty::where('application_name', 'wordpress')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        /* This is a way of getting the token from the response. */
        $wordpress->user_token = $token;
        $wordpress->save();
       

        /* This is a way of showing a success message to the user. */
        smilify('success', 'Token Generated Successfully.');
        return back();

        } catch (\Throwable $th) {
            smilify('error', 'The connection is failed.');
            return back();
        }
    }

    /**
     * fetch_data
     */
    public function fetch_data()
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        try {
        /* Clearing the session. */
        session()->forget('woocommerce');

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $woocommerce = ThirdParty::where('application_name', 'wordpress')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        /* Getting the email and url from the database. */
        $email = $woocommerce->user_email;
        $url = $woocommerce->application_url;
        $token = $woocommerce->user_token;

        /* Concatenating the url and email to form a complete url. */
        $api_url = $woocommerce->application_url . '/wp-content/plugins/teleman-wordpress/teleman/phonebook.php?token=' . $token;

        /* This is a curl request to the perfex application. */
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $collect = collect();

        /* Getting the data from the response and storing it in variables. */
        foreach(json_decode($response, true) as $contact)
        {
            $name = $contact['name'];
            $phonenumber = $contact['phone'];

            $demo = new Demo;
            $demo->name = $name;
            $demo->phonenumber = $phonenumber;
            
            $collect->push($demo);
        }

        /* Storing the data in a session. */
        $session = session()->put('woocommerce', $collect);

        /* This is a way of showing a success message to the user. */
        smilify('success', 'Contacts Fetched');
        return back();

        } catch (\Throwable $th) {
            smilify('error', 'The connection is failed.');
            return back();
        }
    }

    /**
     * Store
     */
    public function store_to_database()
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        try {

        $counter = 0;

        /* Storing the data in the database. */
        foreach (session('woocommerce') as $contact) {
            // check if the email exists
            $check_phone_exists = Contact::HasAgent()
                                        ->where('phone', $contact->phonenumber)
                                        ->first();

            /* Checking if the email or phone number exists in the database. If it does not exist, it will store it in the database. */
            if ($check_phone_exists == null) {
                $email = new Contact;
                $email->user_id = Auth::user()->id;
                $email->name = $contact->name;
                $email->country = null;
                $email->phone = $contact->phonenumber;
                $email->reference = 'woocommerce';
                $email->save();

                $counter++;
            }
        }

        /* This is a ternary operator. It is a way of writing an if statement in one line. */
        $message = $counter . ' new ' . Str::plural('contacts', $counter) . ' stored';

        /* This is a way of showing a success message to the user. */
        smilify('success', $message);
        return back();

        } catch (\Throwable $th) {
            smilify('error', 'The connection is failed.');
            return back();
        }
         
    }
    //ENDS
}
