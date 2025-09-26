<?php

namespace App\Http\Controllers;

use Auth;
use Log;
use Session;
use Carbon\Carbon;
use App\Models\Identity;
use App\Models\Provider;
use App\Models\CallHistory;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\ClientToken;

use Illuminate\Http\Request;
use App\Models\TwilioCallCost;
use Twilio\TwiML\VoiceResponse;
use App\Models\LiveCallDuration;
use Twilio\Jwt\Grants\VoiceGrant;
use App\Models\PackageSupportedCountry;

class DialerController extends Controller
{
    public function index($department = null)
    {
        generate_user_slug(auth()->id());

        return view('backend.dialer.index', compact('department')); // backend.dialer.index
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {

        if (Auth::user()->role == 'agent') { // agent
            if (check_balance(agent_owner_id()) == false) { // check_balance
                // return an error message
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have insufficient balance to make this call.',
                ]);
            }
        }else {
            if (check_balance(Auth::id()) == false) { // check_balance
                // return an error message
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have insufficient balance to make this call.',
                ]);
            }
        }
        
        if (userRoleByAuthId() != 'admin') { // Auth::user()->role
            $request->validate([ // validate
                'dialer_session_uuid' => 'required',
            ], [
                'dialer_session_uuid.required' => 'Dial Session ID is required',
            ]);

            // check the $request->phone has + sign or not
            if (substr($request->phone, 0, 1) != '+') {
                $request->phone = '+'.$request->phone;
            }

            // check country code exists in package
            $phone = $request->phone;
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            try {
                $NumberProto = $phoneUtil->parse($phone, null);

                // Check country code exists in TwilioCallCost
                $check_TwilioCall_Cost = TwilioCallCost::where('code', $NumberProto->getCountryCode())
                                                    ->first();

                if (!$check_TwilioCall_Cost) { // if !$check_TwilioCall_Cost
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unsupported number.',
                    ]);
                } 

                // check twilio_call_costs_id in package supported countries
                $check_package_supported_countries = PackageSupportedCountry::where('twilio_call_costs_id', $check_TwilioCall_Cost->id)
                                                                                    ->where('package_id', activePackage()) // activePackage()
                                                                                    ->first(); // get package_id

                    if (!$check_package_supported_countries) { // if !$check_package_supported_countries


                    $check_sessoin_id = LiveCallDuration::where('dialer_session_uuid', $request->dialer_session_uuid)
                                                        ->first(); // get dialer_session_uuid

                        if ($check_sessoin_id) { // if $check_sessoin_id
                            $check_sessoin_id->end_at = Carbon::now();
                            $check_sessoin_id->duration = $check_sessoin_id->end_at->diffInSeconds($check_sessoin_id->start_at);
                            $check_sessoin_id->total_deduction = $check_sessoin_id->duration * $check_sessoin_id->app_deduction; // per sec cost * call seconds
                            $check_sessoin_id->save();

                            live_call_deduct_credit($check_sessoin_id->user_id, $check_sessoin_id->dialer_session_uuid); // deduct credit
                        } else { // else $check_sessoin_id
                            $live_call_duration = new LiveCallDuration;
                            $live_call_duration->user_id = Auth::id();
                            $live_call_duration->phone = $request->phone;
                            $live_call_duration->dialer_session_uuid = $request->dialer_session_uuid;
                            $live_call_duration->start_at = Carbon::now();
                            $live_call_duration->app_deduction = call_cost_per_second(get_country_code_from_number($phone)); // per seconds
                            // $live_call_duration->app_deduction = call_cost_per_second('880'); // per seconds
                            $live_call_duration->save();
                        }

                    // return an success message
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Call duration has been saved.',
                    ]);


                    return response()->json([
                        'status' => 'success',
                        'message' => 'This phone number is not eligble in your package. This call will be charged extra ' . $check_TwilioCall_Cost->teleman_cost .'/min.' . '<br> To cancel this call, make the call end. Thank you.',
                    ]);
                }
                
            } catch (\libphonenumber\NumberParseException $e) { // catch NumberParseException
                // return an error message
            }

            // check country code exists in package::ENDS
            $check_sessoin_id = LiveCallDuration::where('dialer_session_uuid', $request->dialer_session_uuid)
                                                ->first(); // get dialer_session_uuid

            if ($check_sessoin_id) { // if $check_sessoin_id
                $check_sessoin_id->end_at = Carbon::now();
                $check_sessoin_id->duration = $check_sessoin_id->end_at->diffInSeconds($check_sessoin_id->start_at);
                $check_sessoin_id->total_deduction = $check_sessoin_id->duration * $check_sessoin_id->app_deduction;
                $check_sessoin_id->save();

                live_call_deduct_credit($check_sessoin_id->user_id, $check_sessoin_id->dialer_session_uuid); // deduct credit
            } else { // else $check_sessoin_id
                $live_call_duration = new LiveCallDuration;
                $live_call_duration->user_id = Auth::id();
                $live_call_duration->phone = $request->phone;
                $live_call_duration->dialer_session_uuid = $request->dialer_session_uuid;
                $live_call_duration->start_at = Carbon::now();
                $live_call_duration->app_deduction = call_cost_per_second(get_country_code_from_number($phone));
                $live_call_duration->save();
            }
            
            // return an success message
            return response()->json([
                'status' => 'success',
                'message' => 'Call duration has been saved.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Call duration has been saved.',
            ]);
        }
    }

    /**
     * dialer.country.code.exists.in.package
     */
    public function country_code_exists_in_package(Request $request)
    {

        // check the $request->phone has + sign or not
        if (substr($request->phone, 0, 1) != '+') {
            $request->phone = '+'.$request->phone;
        }

        $phone = $request->phone;
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $NumberProto = $phoneUtil->parse($phone, null);

            // Check country code exists in TwilioCallCost
            $check_TwilioCall_Cost = TwilioCallCost::where('code', $NumberProto->getCountryCode())
                                                ->first();

            /* Checking if the number is supported. */
            if (!$check_TwilioCall_Cost) { // if !$check_TwilioCall_Cost
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unsupported number.',
                ]);
            }

            if (userRoleByAuthId() != 'admin') { // Auth::user()->role
                // check twilio_call_costs_id in package supported countries
                $check_package_supported_countries = PackageSupportedCountry::where('twilio_call_costs_id', $check_TwilioCall_Cost->id)
                                                                            ->where('package_id', activePackage())
                                                                            ->first(); // get package_id
            }else {
                // check twilio_call_costs_id in package supported countries
                $check_package_supported_countries = PackageSupportedCountry::where('twilio_call_costs_id', $check_TwilioCall_Cost->id)
                                                                            ->first(); // get package_id
            }
            

            if (!$check_package_supported_countries) { // if !$check_package_supported_countries
                return response()->json([
                    'status' => 'error',
                    'message' => 'This phone number is not eligible in your package. This call will be charged extra ' . $check_TwilioCall_Cost->teleman_cost . '<br> To cancel this call, make the call end. Thank you.',
                ]);
            }
            
        } catch (\libphonenumber\NumberParseException $e) { // catch NumberParseException
            return response()->json([
                'status' => 'error',
                'message' => 'warning: ' . $e,
            ]);
        }
        
    }

    /**
     * It generates a token for the Twilio Client SDK to use to connect to Twilio
     */
    public function dialer_token()
    {
        // put your Twilio API credentials here
        
        $accountSid = user_active_provider_info()->account_sid;
        $authToken  = user_active_provider_info()->auth_token;
        $appSid     = user_active_provider_info()->capability_token;
        
        /* Generating a token for the client to use to connect to Twilio. */
        $capability = new ClientToken($accountSid, $authToken);
        $capability->allowClientOutgoing($appSid);
        $capability->allowClientIncoming(get_user_identity(auth()->id()));
        $token = $capability->generateToken(3600*12);
        $data['identity'] = get_user_identity(auth()->id());
        $data['token'] = $token;
        echo json_encode($data);
    }

    /* A Twilio function. It is used to handle the call. */
    public function handle_call(Request $request)
    {
        
        /* Taking the data from the form and storing it in the variable . */
        $data = $request->all();

        /* The above code is creating a new voice response object. */
        $response = new VoiceResponse();

        // OUTBOUND
        if (isset($data['ApplicationSid'])) {

            /* Assigning the value of the key 'ApplicationSid' to the variable . */
            $applicationSid = $data['ApplicationSid'];
            
            /* Finding the provider with the capability token that matches the applicationSid. */
            $provider = Provider::where('capability_token', $applicationSid)->first();
            
            /* Getting the phone number of the provider. */
            $twilio_number = $provider->phone;

            $response->dial($data['To'],
                [
                    'callerId' => $twilio_number,
                    'record' => 'record-from-answer-dual',
                    'action' => route('dialer.recording'),
                    'timeout' => 20,
                    'method' => 'POST'
                ]
            );

        } else {
            // INBOUND
            
            $user_id = Provider::where('phone', $data['Called'])->first()->user_id;
            $identity = Identity::where('user_id', $user_id)->first()->identity;

            $response->pause(['length' => 2]);
            
            $agentAvailable = checkAgentAvailability($user_id);

            if ($agentAvailable == 1) {

                /* The above code is calling a function named "removeFromQueue" and passing two
                arguments: ['Caller'] and ['Called']. */
                removeFromQueue($data['Caller'], $data['Called']);

                $dial = $response->dial('',
                    [
                        'record' => 'record-from-answer-dual',
                        'action' => route('dialer.recording'),
                        'timeout' => 20,
                        'method' => 'POST'
                    ]
                );
                $dial->client($identity);

            } else {
                
                /* The above code is calling the `moveToQueue` function with four parameters:
                `['Caller']`, `['Called']`, `1`, and ``. */
                moveToQueue($data['Caller'], $data['Called'], 1, $user_id);
                
                $response->say('all of our agents are currently busy, please stay on the line and we will answer your call as soon as possible.');
                $response->pause(['length' => 3]);
                $response->say('All of our agents are still busy. Your call is number ' . getCallerQueueSerialNumber($data['Called'], $data['Caller']) . ' in the queue. Please stay on the line.');
                $response->redirect(route('dialer.handle_call'), ['method' => 'POST'], ['timeout' => 3]);
            }
            
        }

        return response($response)->header('Content-Type', 'text/xml');

    }

    /**
     * This PHP function returns a view for a dialer pad with a given phone number.
     * 
     * @param my_number The parameter `` is a variable that contains a phone number. It is
     * being passed to the `dialerpad` function as an argument and then being compacted with the
     * `dialerpad` view. The compacted variable can then be accessed in the view to display the
     * 
     * @return a view called 'backend.dialer.dialerpad' with the variable  passed to it.
     */
    public function dialerpad($my_number = null)
    {
        updateAgentAvailability(auth()->id(), true); // making the agent available
        return view('backend.dialer.dialerpad', compact('my_number')); // backend.dialer.dialerpad
    }

    /**
     * The function creates a new call history record with the provided request data and returns a JSON
     * response.
     * 
     * @param Request request  is an instance of the Request class which contains the data sent
     * in the HTTP request. It is used to retrieve data from the request such as form data, query
     * parameters, and request headers. In this case, the function is using the  object to
     * retrieve data for creating a new CallHistory
     * 
     * @return A JSON response with the newly created CallHistory object and a status code of 200.
     */
    public function createCallHistory(Request $request)
    {

        /* The above code is querying the database for a CallHistory record that matches the specified
        conditions. The conditions include the authenticated user's ID, the user's identity ID, the
        user's phone number, the caller's phone number, and the caller's UUID session. If a matching
        record is found, it is returned as a CallHistory object. */
        $history = CallHistory::where([
            'user_id' => auth()->id(),
            'identity_id' => get_user_identity_id(auth()->id()),
            'caller_uuid' => $request->get_caller_uuid_session,
        ])->first();

        /* The above code is a conditional statement in PHP. It checks if the variable `` is
        truthy (i.e. not null, 0, false, or an empty string). If `` is truthy, then the code
        inside the curly braces (`{}`) will be executed. However, since the code inside the curly
        braces is commented out with ` */
        if ($history) {

            /* The above code is checking if the `pick_up_time` property of the `` object is
            null. If it is null, it sets the `pick_up_time` property to the current time using the
            `now()` function. */
            if ($history->pick_up_time == null) {
                $history->pick_up_time = now();
            }

            /* The above code is checking if the `hang_up_time` property of the `` object is
            null. If it is null, it sets the `hang_up_time` property to the current time using the
            `now()` function. */
            if ($history->hang_up_time == null) {
                $history->hang_up_time = now();
            }

            $history->status = $request->status;
            $history->save();
        } else {
            $history = CallHistory::create([
                'user_id' => auth()->id(),
                'identity_id' => get_user_identity_id(auth()->id()),
                'my_number' => $request->my_number,
                'caller_number' => $request->caller_number,
                'caller_uuid' => $request->get_caller_uuid_session,
                'pick_up_time' => now(),
                'hang_up_time' => $request->hang_up_time ?? null,
                'status' => $request->status,
            ]);
        }
        
        /* The above code is a PHP code that returns a JSON response with a variable named ``
        and a status code of 200. The content of the `` variable is not shown in the code
        snippet. */
        return response()->json($history, 200);

    }

    /**
     * The function takes a request object, retrieves the recording URL and call SID from the request,
     * and updates a record in the CallHistory table with the recording URL if a matching record is
     * found.
     * 
     * @param Request request The  parameter is an instance of the Request class in the Laravel
     * framework. It represents the HTTP request made to the server and contains information such as
     * the request method, headers, and request data.
     * 
     * @return a JSON response with the string 'success' and a status code of 200.
     */
    public function recording(Request $request)
    {

        try {
        
            $recordingUrl = $request->RecordingUrl;
            $DialCallSid = $request->DialCallSid;
            $my_number = $request->Called;
            $caller_number = $request->From;

            $add_record_to_call_history = CallHistory::where('my_number', $my_number)
                                                    ->where('caller_number', $caller_number)
                                                    ->latest()
                                                    ->first();

            if ($add_record_to_call_history) {
                $add_record_to_call_history->record_file = $recordingUrl . '.mp3' ?? null;
                $add_record_to_call_history->save();
            }

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            return response()->json('error', 201);
        }
        
    }

    /**
     * The function analyzes a call record by transcribing the text and providing an analysis result
     * using the OpenAI API.
     * 
     * @param file_name The file name of the call record that needs to be analyzed.
     * 
     * @return a view named 'backend.dialer.record_analyze_open_ai' with the variables
     * 'transcribed_text', 'analyze_call_record', and 'file_name' compacted.
     */
    public function analyze_the_call_record($file_name)
    {

        if (env('OPENAI_API_KEY') == null) {
            smilify('error', translate('OPENAI key is not configured.'));
            return back();
        }

        try {
            $transcribed_text = analyze_call_record($file_name)['transcribed_text'];
            $analyze_call_record = analyze_call_record($file_name)['analysis_result'];
            return view('backend.dialer.record_analyze_open_ai', compact('transcribed_text', 'analyze_call_record', 'file_name'));
        } catch (\Throwable $th) {
            smilify('error', $th->getMessage());
            return back();
        }
    }

    //ENDS
}
