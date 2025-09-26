<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\PackageSupportedCountry;
use Illuminate\Http\Request;
use Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.packages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.packages.create');
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

        try {
            $request->validate([
                'name' => 'required|unique:packages',
                'slug' => 'unique:packages,slug',
                'price' => 'required|numeric',
                'range' => 'required|numeric',
                'range_type' => 'required',
                'feature_id' => 'required',
                'twilio_call_costs_id' => 'required',
                'credit' => 'required|numeric',
                'credit' => 'required|numeric',
            ], [
                'name.required' => 'Package name is required',
                'name.unique' => 'Package name already exists',
                'slug.unique' => 'Package slug already exists',
                'price.required' => 'Package price is required',
                'price.numeric' => 'Package price must be numeric',
                'range.required' => 'Package range is required',
                'range.numeric' => 'Package range must be numeric',
                'range_type.required' => 'Package range type is required',
                'feature_id.required' => 'Package feature is required',
                'twilio_call_costs_id.required' => 'Package Country is required',
                'credit.required' => 'Package credit is required',
                'credit.numeric' => 'Package credit must be numeric',
            ]);

            $package = new Package;
            $package->name = $request->name;
            $package->slug = Str::slug($request->name);
            $package->emails = null;
            $package->sms = null;
            $package->credit = $request->credit;
            $package->range = $request->range;
            $package->range_type = $request->range_type;
            $package->price = $request->price;
            if ($request->active == 1) {
                $package->active = 1;
            } else {
                $package->active = 0;
            }
            if ($request->trial == 1) {
                $package->trial = 1;
            } else {
                $package->trial = 0;
            }
            $package->feature_id = json_encode($request->feature_id);

            if ($package->save()) {

                // PackageSupportedCountry
                foreach ($request->twilio_call_costs_id as $country) {
                    $package_supported_country = new PackageSupportedCountry;
                    $package_supported_country->package_id = $package->id; // package id
                    $package_supported_country->twilio_call_costs_id = $country; // twilio call costs id
                    $package_supported_country->save();
                }
                // PackageSupportedCountry::ENDS
            }

            activity($package->name, 'new package added');
            smilify('success', 'Package created successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something happened!');

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $slug)
    {
        $package = Package::where('id', $id)
                          ->with('supported_countries')
                          ->first();

        return view('backend.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'unique',
                'price' => 'required|numeric',
                'range' => 'required|numeric',
                'credit' => 'required|numeric',
                'range_type' => 'required',
                'feature_id' => 'required',
                'twilio_call_costs_id' => 'required',
            ], [
                'name.required' => 'Package name is required',
                'price.required' => 'Package price is required',
                'price.numeric' => 'Package price must be numeric',
                'range.required' => 'Package range is required',
                'range.numeric' => 'Package range must be numeric',
                'credit.required' => 'Package credit is required',
                'creditcredit.numeric' => 'Package credit must be numeric',
                'range_type.required' => 'Package range type is required',
                'feature_id.required' => 'Package feature is required',
                'twilio_call_costs_id.required' => 'Package Country is required',
                'emails.required' => 'Package emails is required',
            ]);

            $package = Package::where('id', $id)
                              ->first();
            $package->name = $request->name;
            $package->slug = Str::slug($request->name);
            $package->emails = null;
            $package->sms = null;
            $package->credit = $request->credit;
            $package->range = $request->range;
            $package->range_type = $request->range_type;
            $package->price = $request->price;
            if ($request->active == 1) {
                $package->active = 1;
            } else {
                $package->active = 0;
            }
            if ($request->trial == 1) {
                $package->trial = 1;
            } else {
                $package->trial = 0;
            }
            $package->feature_id = json_encode($request->feature_id);
            $package->save();

            if ($package->save()) {

                // destroy all package supported country
                PackageSupportedCountry::where('package_id', $package->id)
                                        ->delete();

                // PackageSupportedCountry
                foreach ($request->twilio_call_costs_id as $country) {
                    $package_supported_country = new PackageSupportedCountry;
                    $package_supported_country->package_id = $package->id; // package id
                    $package_supported_country->twilio_call_costs_id = $country; // twilio call costs id
                    $package_supported_country->save();
                }
                // PackageSupportedCountry::ENDS
            }

            activity($package->name, 'package updated');
            smilify('success', 'Package updated successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something happened!');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $slug)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }
        //  check user already purchased this package
        $package = Package::where('id', $id)
                          ->first();
        $purchased = Subscription::where('package_id', $id)
                            ->count();
        if ($purchased > 0) {
            smilify('warning', 'You can not delete this package, because it is already purchased by users');

            return back();
        } else {
            $package->delete();
            activity($package->name, 'package deleted');
            smilify('success', 'Package deleted successfully');

            return back();
        }
    }

    // change Status
    public function changeStatus($id, $slug)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }
        $package = Package::where('id', $id)
                          ->first();
        if ($package->active == 1) {
            $package->active = 0;
        } else {
            $package->active = 1;
        }
        $package->save();
        activity($package->name, 'feature status changed');
        smilify('success', 'Package status changed successfully');

        return back();
    }

    // ENDS HERE
}
