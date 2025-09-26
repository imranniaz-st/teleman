<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Models\Campaign;
use App\Models\CampaignSchedule;
use App\Models\CampaignVoice;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PackageSupportedCountry;
use App\Models\TwilioCallCost;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        generate_user_slug(auth()->id());
        return view('backend.campaigns.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /**
         * validation
         */
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'group_id' => 'required',
            'provider' => 'required',
        ], [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'group_id.required' => 'Groups is required',
            'provider.required' => 'Provider is required',
        ]);

        try {
            $file_name = rand(1000, 9999);
            $xml = '/public/voices/'.$file_name.'.xml';

            $campaign = new Campaign;
            $campaign->user_id = Auth::user()->id;
            $campaign->name = $request->name;
            $campaign->description = $request->description;
            $campaign->group_id = $request->group_id;
            $campaign->provider = $request->provider;
            $campaign->say = $request->say;
            $campaign->expectation = $request->expectation;

            if ($request->hasFile('audio')) {
                $campaign->audio = env('APP_URL').'/'.audioUpload($request->audio, '/audio');
            } else {
                $campaign->audio = $request->audio_url;
            }

            createUserXMLfile($campaign->say, $campaign->audio, $file_name);
            $campaign->xml = $xml;

            if ($request->status == 1) {
                $campaign->status = 1;
            } else {
                $campaign->status = 0;
            }

            $campaign->save();

            smilify('success', 'Campaign created successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back();
        }
    }

    /**
     * start_campaign
     */
    public function start_campaign($campaign_id, $slug = null)
    {
        $campaign = Campaign::where('id', $campaign_id)->first();

        if (check_balance($campaign->user_id) == false) {
            smilify('error', 'Insufficient balance');

            return back();
        }

        /**
         * check has group and provider
         */
        if ($campaign->group_id == null || $campaign->provider == null) {
            smilify('error', 'Campaign has no group or provider');

            return back();
        }

        /**
         * Check Hourly quota
         */
        if (check_quota_hourly($campaign->user_id, $campaign->provider) == 'crossed') {
            smilify('warning', 'Hourly quota crossed');

            return back();
        }

        /**
         * Check Twilio Connection
         */
        if (check_twilio_connection(account_sid($campaign->provider)) == false) {
            smilify('error', 'Twilio Connection Failed. Please check your Twilio Account');

            return back();
        }

        $start = new CampaignSchedule;
        $start->user_id = Auth::id();
        $start->campaign_id = $campaign_id;
        $start->group_id = $campaign->group_id;
        $start->provider = $campaign->provider;
        $start->say = $campaign->say;
        $start->audio = $campaign->audio;
        $start->xml = $campaign->xml;
        $start->start_at = Carbon::now();
        $start->status = 'PENDING';
        $start->save();

        smilify('success', 'Campaign Started successfully');

        return back();
    }

    /**
     * dev_make_call
     */
    public function dev_make_call($campaign_id, $slug)
    {
        try {
            $campaign = CampaignSchedule::where('campaign_id', $campaign_id)
                                        ->with('contacts')
                                        ->first();

            if ($campaign == null) {
                smilify('error', 'No Campaign is scheduled');
                return back();
            }

            if (check_balance($campaign->user_id) == false) {
                smilify('error', 'Insufficient balance');

                return back();
            }

            $check_campaign_validity = Campaign::where('id', $campaign_id)->first();

            /**
             * check has group and provider
             */
            if ($check_campaign_validity->group_id == null || $check_campaign_validity->provider == null) {
                smilify('error', 'Campaign has no group or provider');

                return back();
            }

            /**
             * Check Hourly quota
             */
            if (check_quota_hourly($check_campaign_validity->user_id, $check_campaign_validity->provider) == 'crossed') {
                smilify('warning', 'Hourly quota crossed');

                return back();
            }

            /**
             * Check Contacts
             */
            if ($campaign->contacts->count() == 0) {
                smilify('error', 'No Contacts found');

                return back();
            }

            /**
             * Check Twilio Connection
             */
            if (check_twilio_connection(account_sid($check_campaign_validity->provider)) == false) {
                smilify('error', 'Twilio Connection Failed. Please check your Twilio Account');

                return back();
            }

            foreach ($campaign->contacts->take(1) as $camp) {
                if (check_quota_hourly($campaign->user_id, $campaign->provider) == 'crossed') {
                    smilify('warning', 'Hourly quota crossed');

                    return back();
                }

                twilio_calling($campaign->provider,
                            phone_number($camp->contact_id),
                            true,
                            campaign_audio($campaign_id),
                            $campaign->user_id);
                quota_log_store($campaign->provider, $camp->user_id, $camp->contact_id, phone_number($camp->contact_id));
            }

            smilify('success', 'Calling successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id)
    {
        $campaign = Campaign::where('id', $campaign_id)->first();

        return view('backend.campaigns.show', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $campaign_id)
    {
        /**
         * validation
         */
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'group_id' => 'required',
            'provider' => 'required',
        ], [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'group_id.required' => 'Groups is required',
            'provider.required' => 'Provider is required',
        ]);

        try {
            $file_name = rand(1000, 9999);
            $xml = '/public/voices/'.$file_name.'.xml';

            $campaign = Campaign::where('id', $campaign_id)->first();
            $campaign->user_id = Auth::user()->id;
            $campaign->name = $request->name;
            $campaign->description = $request->description;
            $campaign->group_id = $request->group_id;
            $campaign->provider = $request->provider;
            $campaign->say = $request->say;
            $campaign->expectation = $request->expectation;

            // update campaign_schedules group_id
            if ($campaign->group_id) {
                CampaignSchedule::where('campaign_id', $campaign_id)->update(['group_id' => $request->group_id]);
            }

            // update campaign_schedules provider_id
            if ($campaign->provider) {
                CampaignSchedule::where('campaign_id', $campaign_id)->update(['provider' => $request->provider]);
            }

            if ($request->hasFile('audio')) {
                $campaign->audio = env('APP_URL').'/'.audioUpload($request->audio, '/audio');
            } else {
                $campaign->audio = $request->audio_url;
            }

            createUserXMLfile($campaign->say, $campaign->audio, $file_name);
            $campaign->xml = $xml;

            if ($request->status == 1) {
                $campaign->status = 1;
            } else {
                $campaign->status = 0;
            }

            $campaign->save();

            smilify('success', 'Campaign updated successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete campaign related datas
        $campaign = Campaign::where('id', $id)->first();
        if ($campaign) {
            $campaign->campaign_schedules()->delete();
            $campaign->campaign_voices()->delete();
            $campaign->campaign_voice_status_logs()->delete();
            $campaign->delete();
        }

        smilify('success', 'Campaign deleted successfully');

        return back();
    }

    /**
     * VOICE CAMPAIGN
     */
    public function voice_campaign_index()
    {
        return view('backend.campaigns.voice.index');
    }

    /**
     * VOICE CAMPAIGN
     */
    public function voice_campaign($campaign_id)
    {
        $campaign = Campaign::where('id', $campaign_id)->first();

        /**
         * check has group and provider
         */
        if ($campaign->group_id == null || $campaign->provider == null) {
            smilify('error', 'Campaign has no group or provider');

            return back();
        }

        /**
         * Check Twilio Connection
         */
        if (check_twilio_connection(account_sid($campaign->provider)) == false) {
            smilify('error', 'Twilio Connection Failed. Please check your Twilio Account');

            return back();
        }

        return view('backend.campaigns.voice.call', compact('campaign_id'));
    }

    /**
     * This PHP function updates or adds a lead's status in a voice campaign and logs the status
     * change.
     * 
     * @param Request request  is an instance of the Request class, which contains the data
     * sent in the HTTP request. It is used to retrieve input data, such as form data or query
     * parameters, and to access the HTTP headers and cookies. In this function,  is used to
     * retrieve the campaign ID, phone number
     * 
     * @return a JSON response with either a success message indicating that the status was updated
     * successfully or that a new lead was added successfully.
     */
    public function voice_campaign_lead(Request $request)
    {

        /* The above code is using the Laravel Eloquent ORM to query the CampaignVoice model to check
        if there is a record that matches the following conditions: */
        $check = CampaignVoice::where('user_id', Auth::id())
                                ->where('campaign_id', $request->campaign_id)
                                ->where('contact_id', $request->phone)
                                ->where('phone', $request->number)
                                ->first();

        /* The above code is a PHP script that updates the status of a campaign voice based on the
        provided parameters. If the `` variable is true, it updates the existing campaign
        voice with the provided `campaign_id`, `phone`, `number`, and `status` parameters. If the
        `` variable is false, it creates a new campaign voice with the provided parameters and
        saves it to the database. In both cases, it logs the campaign voice status using the
        `voice_campaign_status_log` function and returns a JSON response indicating success or
        failure. */
        if ($check) {
            $voice = CampaignVoice::where('user_id', Auth::id())
                                    ->where('campaign_id', $request->campaign_id)
                                    ->where('contact_id', $request->phone)
                                    ->where('phone', $request->number)
                                    ->update(['status' => $request->status]);

            /* The above code is calling a function named `voice_campaign_status_log` and passing three
            parameters to it: `->campaign_id`, `->phone`, and `->status`.
            The purpose of this function is not clear from the given code snippet. */
            voice_campaign_status_log($request->campaign_id, $request->phone, $request->status);

            return response()->json(['success' => 'Status updated successfully']);
        } else {
            $voice = new CampaignVoice;
            $voice->user_id = Auth::id();
            $voice->campaign_id = $request->campaign_id;
            $voice->contact_id = $request->phone;
            $voice->phone = $request->number;
            $voice->status = $request->status;
            $voice->save();

            /* The above code is calling a function named `voice_campaign_status_log` and passing three
            parameters to it: `->campaign_id`, `->contact_id`, and `->status`. The
            purpose and functionality of the `voice_campaign_status_log` function is not provided in
            the given code snippet. */
            voice_campaign_status_log($voice->campaign_id, $voice->contact_id, $voice->status);

            return response()->json(['success' => 'Lead Added Successfully']);
        }
    }

    /**
     * leads
     */
    public function leads()
    {
        return view('backend.campaigns.leads');
    }

    /**
     * leads_details
     */
    public function leads_details($campaign_id)
    {
        return view('backend.campaigns.leads_details', compact('campaign_id'));
    }

    /**
     * leads export
     */
    public function leads_export($campaign_id)
    {
        /* The above code is calling a function named `store_leads_export_history` and passing a
        variable `` as its parameter. The purpose of the function is not clear from the
        given code snippet. */
        store_leads_export_history($campaign_id);

        /* The above code is using the PHPExcel library to download a CSV file of leads data from a
        specific campaign identified by the `` variable. The `LeadsExport` class is
        responsible for generating the data to be exported, and the `Excel::download()` method is
        used to initiate the download of the CSV file with the filename "leads.csv". */
        return Excel::download(new LeadsExport($campaign_id), 'leads.csv');
    }

    /**
     * This function sends an SMS message using Twilio API and deducts credit from the user's account
     * based on the cost of the SMS.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * contains the data sent in the HTTP request. It is used to retrieve the phone number, campaign
     * ID, and message from the request.
     * 
     * @return a JSON response with a status and message indicating the success or failure of sending
     * an SMS message.
     */
    public function send_sms(Request $request)
    {

        if (demo()) {
            return response()->json([
                'status' => 'warning',
                'message' => 'This feature is disabled in demo mode'
            ]);
        }

        try {

            /* The above code is checking the balance of the authenticated user using the
            `check_balance()` function. If the balance is insufficient, it returns a JSON response
            with an error status and message. */
            if (check_balance(Auth::id()) == false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance'
                ]);
            }

            /**
             * Phone Number
             */
            $phone = phone_number($request->phone_number);

            // check the $request->phone has + sign or not
            if (substr($phone, 0, 1) != '+') {
                $phone = '+'.$phone;
            }

            // check country code exists in package
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $NumberProto = $phoneUtil->parse($phone, null);

            // Check country code exists in TwilioCallCost
            $check_TwilioSms_Cost = TwilioCallCost::where('code', $NumberProto->getCountryCode())
                                                    ->with('twilio_sms_cost')
                                                    ->first();

            /* The above code is checking if the variable `` is false. If it is
            false, it returns a JSON response with an error message stating that the number is
            unsupported. */
            if (!$check_TwilioSms_Cost) { // if !$check_TwilioCall_Cost
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unsupported number.',
                ]);
            }

            /* The above code is calling a function named `twilioSendSMS` with three parameters:
            `->campaign_id`, `phone_number(->phone_number)`, and
            `->message`. The `phone_number` function is likely used to format the phone
            number in a specific way. This code is likely sending an SMS message using the Twilio
            API. */
            twilioSendSMS($request->campaign_id, phone_number($request->phone_number), $request->message);

            /* The above code is calling a function named `CampaignSmsStatusLog` with four parameters:
            `campaign_id`, `phone_number`, `Auth::id()`, and `getUserInfo(Auth::id())->name`, and
            `->message`. The purpose of this function is not clear from the given code
            snippet. */
            CampaignSmsStatusLog($request->campaign_id, 
                                $request->phone_number, 
                                Auth::id(), 
                                getUserInfo(Auth::id())->name, 
                                $request->message);

            /* The above code is calling a function named `store_to_messages` and passing four
            parameters to it: `phone_number`, `message`, `user_id`, and `campaign_id`. The purpose
            of this function is not clear from the given code snippet. It is likely that this
            function is storing the message and related information in a database or some other
            storage medium. The `auth()->id()` function is used to get the ID of the currently
            authenticated user. */
            store_to_messages($request->phone_number, $request->message, auth()->id(), $request->campaign_id);

            /* The above code is a comment in PHP language. It is not executable code. It appears to be
            describing a function call to deduct credit by using SMS and passing a cost value as a
            parameter. However, without the actual implementation of the function and the context in
            which it is being used, it is difficult to determine the exact purpose of this code. */
            deduct_credit_by_using_sms($check_TwilioSms_Cost->twilio_sms_cost->teleman_sms_cost);

            /* The above code is returning a JSON response with a success status and a message
            indicating that a message has been sent successfully to a phone number specified in the
            request. The phone number is formatted using a helper function called "phone_number". */
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully to ' . phone_number($request->phone_number)
            ]);

        } catch (\Throwable $th) {
            /* The above code is returning a JSON response with an error status and a message that
            contains the error message from the caught exception (). */
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    //ENDS
}
