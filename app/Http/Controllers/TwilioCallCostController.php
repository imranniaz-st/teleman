<?php

namespace App\Http\Controllers;

use App\Models\TwilioCallCost;
use App\Models\TwilioSmsCost;
use App\Models\PackageSupportedCountry;
use Illuminate\Http\Request;
use DB;

class TwilioCallCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.twilio_call_costs.index');
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

        $cost = new TwilioCallCost;
        $cost->country = $request->country;
        $cost->code = $request->code;
        $cost->twilio_cost = $request->twilio_cost;
        $cost->teleman_cost = $request->teleman_cost;
        $cost->teleman_cost_per_second = '$' . call_cost_minute_to_seconds($request->teleman_cost); // per second

        if ($cost->save()) {
            $sms = new TwilioSmsCost;
            $sms->twilio_call_cost_id = $cost->id;
            $sms->teleman_sms_cost = $request->teleman_sms_cost;
            $sms->save();
        }

        smilify('success', 'Call cost added successfully');
        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TwilioCallCost  $twilioCallCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $cost = TwilioCallCost::where('id', $id)->first();
        $cost->code = $request->code;
        $cost->twilio_cost = $request->twilio_cost;
        $cost->teleman_cost = $request->teleman_cost;
        $cost->teleman_cost_per_second = '$' . call_cost_minute_to_seconds($request->teleman_cost); // per second

        if ($cost->save()) {
            $sms = TwilioSmsCost::where('twilio_call_cost_id', $cost->id)->firstOrCreate();
            $sms->twilio_call_cost_id = $cost->id;
            $sms->teleman_sms_cost = $request->teleman_sms_cost;
            $sms->save();
        }

        smilify('success', 'Call cost added successfully');
        return back();
    }

    public function destroy($id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        DB::transaction(function () use ($id) {

            $twilio_call_cost = TwilioCallCost::where('id', $id)->first();
            $package_supported_country = PackageSupportedCountry::where('twilio_call_costs_id', $id)->first();
            $twilio_sms_cost = TwilioSmsCost::where('twilio_call_cost_id', $id)->first();

            if ($twilio_call_cost) {
                $twilio_call_cost->delete();
            }
            if ($package_supported_country) {
                $package_supported_country->delete();
            }
            if ($twilio_sms_cost) {
                $twilio_sms_cost->delete();
            }

        }, 5);

        smilify('success', 'Deleted Successfully');
        return back();

    }

    // ENDS
}
