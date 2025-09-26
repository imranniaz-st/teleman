<?php

namespace App\Http\Controllers;

use Auth;
use Redirect;
use Twilio\Rest\Client;
use App\Models\Campaign;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Exports\ProvidersExport;
use App\Models\CampaignSchedule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.providers.index');
    }

    public function store(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $this->validate($request, [
            'user_id' => 'required',
            'account_sid' => 'required',
            'auth_token' => 'required',
            'phone' => 'required',
            'provider_name' => 'required',
            'hourly_quota' => 'required',
            'capability_token' => 'required',
        ], [
            'user_id.required' => 'Please assign to an user',
            'account_sid.required' => 'Account SID is required',
            'auth_token.required' => 'Auth Token is required',
            'phone.required' => 'Phone is required',
            'provider_name.required' => 'Provider Name is required',
            'hourly_quota.required' => 'Hourly Quota is required',
            'capability_token.required' => 'TwilML is required',
        ]);

        $voice_server = new Provider;
        $voice_server->user_id = $request->user_id;
        $voice_server->account_sid = $request->account_sid;
        $voice_server->auth_token = $request->auth_token;
        $voice_server->phone = $request->phone;
        $voice_server->say = $request->say;
        $voice_server->hourly_quota = $request->hourly_quota;

        $voice_server->provider_name = $request->provider_name;
        $voice_server->capability_token = $request->capability_token;

        if ($request->status == 1) {
            $voice_server->status = 1;
        } else {
            $voice_server->status = 0;
        }

        $voice_server->save();

        smilify('success', 'Provider saved successfully.');

        return back();
    }

    /**
     * Show
     */
    public function show($id)
    {
        $provider = Provider::find($id);

        return view('backend.providers.show', compact('provider'));
    }

    /**
     * Update
     */
    public function update(Request $request, $provider_id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $this->validate($request, [
            'user_id' => 'required',
            'account_sid' => 'required',
            'auth_token' => 'required',
            'phone' => 'required',
            'provider_name' => 'required',
            'hourly_quota' => 'required',
            'capability_token' => 'required',
        ], [
            'user_id.required' => 'Please assign to an user',
            'account_sid.required' => 'Account SID is required',
            'auth_token.required' => 'Auth Token is required',
            'phone.required' => 'Phone is required',
            'provider_name.required' => 'Provider Name is required',
            'hourly_quota.required' => 'Hourly Quota is required',
            'capability_token.required' => 'TwilML is required',
        ]);

        

        $voice_server = Provider::where('id', $provider_id)->first();
        $voice_server->user_id = $request->user_id;
        $voice_server->account_sid = $request->account_sid;
        $voice_server->auth_token = $request->auth_token;
        $voice_server->phone = $request->phone;
        $voice_server->say = $request->say;
        $voice_server->hourly_quota = $request->hourly_quota;

        $voice_server->provider_name = $request->provider_name;
        $voice_server->capability_token = $request->capability_token;

        if ($request->status == 1) {
            $voice_server->status = 1;
        } else {
            $voice_server->status = 0;
        }

        $voice_server->save();

        smilify('success', 'Provider saved successfully.');

        return back();
    }

    /**
     * Destroy
     */
    public function provider_destroy($id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $provider = Provider::findOrFail($id);

        if ($provider->ivr == 1) {
            smilify('error', 'Default provider cannot be deleted.');
            return back();
        }

        // Correct the field name to 'provider_id' if that's your foreign key column in the database
        $provider->campaigns()->update(['provider_id' => null]);
        $provider->campaign_schedules()->update(['provider_id' => null]);

        $provider->delete();
        smilify('success', 'Provider deleted successfully.');

        return back();
    }

    /**
     * initiateTestCall
     */
    public function initiateTestCall(Request $request, $id, $provider)
    {
        try {
            twilio_calling($id, application('test_phone'), true, demo_audio_mp3(), Auth::id());

            smilify('success', 'test call is successfully made.');
            return back();
        } catch (Exception $e) {
            smilify('error', 'Something went wrong.');

            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * accounts
     */
    public function accounts()
    {
        return view('backend.providers.accounts');
    }

    /**
     * accounts ajax
     */
    public function accounts_ajax()
    {
        return view('backend.providers.accounts_ajax_load');
    }

    /**
     * call_logs
     */
    public function call_logs($account_sid)
    {
        return view('backend.providers.call_logs', compact('account_sid'));
    }

    /**
     * call_logs
     */
    public function call_logs_ajax($account_sid)
    {
        $provider = Provider::where('user_id', Auth::id())->where('account_sid', $account_sid)->first();

        $twilio = new Client($provider->account_sid, $provider->auth_token);

        $calls = $twilio->calls
                ->read([], 20);

        return view('backend.providers.call_logs_ajax', compact('calls', 'account_sid'));
    }

    public function single_call_log($sid, $account_sid)
    {
        $provider = Provider::where('user_id', Auth::id())->where('account_sid', $account_sid)->first();

        $twilio = new Client($provider->account_sid, $provider->auth_token);
        $call = $twilio->calls($sid)
                       ->fetch();

        $recording = twilioRecording($provider->account_sid, $provider->auth_token, $sid);

        return view('backend.providers.call_log', compact('call', 'recording', 'account_sid'));
    }

    /**
     * download_recording
     */
    public function download_recording($call_sid, $account_sid)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        if (twilioRecording(provider_info($account_sid)->account_sid, provider_info($account_sid)->auth_token, $call_sid) != null) {
            return Redirect::to(twilioRecording(provider_info($account_sid)->account_sid, provider_info($account_sid)->auth_token, $call_sid));
        } else {
            smilify('error', 'No Recording Found.');

            return back();
        }
    }

    /**
     * DESTROY
     */
    public function destroy($call_sid)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $twilio = new Client('account_sid', 'auth_token');

        $call = $twilio->calls($call_sid)
                       ->delete();

        if ($call) {
            smilify('success', 'Call deleted successfully');

            return back();
        } else {
            smilify('error', 'Call Not Found!');

            return back();
        }
    }

    /**
     * export_calls_csv
     */
    public function export_calls_csv($account_sid)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        return export_calls_csv(Auth::id(), $account_sid);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }
        
        return Excel::download(new ProvidersExport, 'providers.csv');
    }

    /**
     * Set as default IVR Acitve and deactive
     */
    public function make_default($provider_id)
    {
        try {
            // Start a transaction
            DB::beginTransaction();

            // Directly update the default provider
            Provider::where('id', $provider_id)
                    ->HasAgent(Auth::id())
                    ->update(['ivr' => 1]);

            // Mass update to reset other providers
            Provider::HasAgent(Auth::id())
                    ->where('id', '!=', $provider_id)
                    ->update(['ivr' => 0]);

            // Fetch the phone number of the updated provider for the notification
            $phone = Provider::where('id', $provider_id)->value('phone');

            smilify('success', $phone . ' activated successfully.');

            // Commit the transaction
            DB::commit();

            return back();
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollback();

            // Handle the error as appropriate
            return back()->withErrors(['msg' => 'Error updating providers.']);
        }
    }


    // ENDS
}
