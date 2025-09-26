<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThirdParty;
use App\Models\Demo;
use App\Models\Group;
use App\Models\Contact;
use Auth;
use Str;

class PerfexController extends Controller
{
    // index
    public function index()
    {
        $perfex = ThirdParty::where('application_name', 'perfex')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if (!$perfex) {
            session()->forget('perfex');
        }

        return view('addons.perfex.index', compact('perfex'));
    }
    // index
    public function store(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $perfex = ThirdParty::where('application_name', 'perfex')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if ($perfex) {
            $update_perfex = ThirdParty::where('user_id', Auth::user()->id)->first();
            $update_perfex->user_id = Auth::id();
            $update_perfex->application_name = 'perfex';
            $update_perfex->application_url = $request->application_url;
            $update_perfex->user_email = $request->user_email;
            $update_perfex->save();
        }else {
            $new_perfex = new ThirdParty;
            $new_perfex->user_id = Auth::id();
            $new_perfex->application_name = 'perfex';
            $new_perfex->application_url = $request->application_url;
            $new_perfex->user_email = $request->user_email;
            $new_perfex->save();
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

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $perfex = ThirdParty::where('application_name', 'perfex')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        /* Getting the email and url from the database. */
        $email = $perfex->user_email;
        $url = $perfex->application_url;

        /* Concatenating the url and email to form a complete url. */
        $api_url = $perfex->application_url . '/teleman_token?email=' . $email;

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

        /* This is a way of getting the token from the response. */
        $collect = collect();

        /* This is a way of getting the token from the response. */
        foreach(json_decode($response, true) as $key => $res)
        {
            $collect->push($res);
        }

        /* This is a way of getting the token from the response. */
        if ($collect[0] == 'success') {
            $perfex->user_token =  $collect[2];
            $perfex->save();
        }else {
            smilify('error', $collect[1]);
            return back();
        }

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
        session()->forget('perfex');

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $perfex = ThirdParty::where('application_name', 'perfex')
                            ->where('user_id', Auth::user()->id)
                            ->first();

        /* Getting the email and url from the database. */
        $email = $perfex->user_email;
        $url = $perfex->application_url;
        $token = $perfex->user_token;

        /* Concatenating the url and email to form a complete url. */
        $api_url = $perfex->application_url . '/teleman?email=' . $email . '&token=' . $token;

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
            $name = $contact['firstname'] . ' ' . $contact['lastname'];
            $phonenumber = $contact['phonenumber'];

            $demo = new Demo;
            $demo->name = $name;
            $demo->phonenumber = $phonenumber;
            
            $collect->push($demo);
        }

        /* Storing the data in a session. */
        $session = session()->put('perfex', $collect);

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
        foreach (session('perfex') as $contact) {

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
                $email->reference = 'perfex crm';
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
