<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{

    public function __construct()
    {
        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data = array( // data to send to Google
            'secret'   => env('GOOGLE_RECAPTCHA_SECRET'), // your secret key
            'response' => $value // the key given in the form
        ); // data to send to Google

        try { // try to send post request to google recaptcha server
            $verify = curl_init(); // initialize curl
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify"); // set the url
            curl_setopt($verify, CURLOPT_POST, true); // tell curl you want to post something
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data)); // add POST data
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false); // disable SSL verification
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true); // return the response
            $response = curl_exec($verify); // execute
            return json_decode($response)->success; // return true or false
        } catch (\Exception $e) { // if curl fails
            return false; // return false
        } // end try

    }

    public function message()
    {
        return 'ReCaptcha verification failed.';
    }
}