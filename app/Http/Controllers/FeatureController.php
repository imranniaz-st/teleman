<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Str;

class FeatureController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.features.create');
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
                'name' => 'required|unique:features,name',
                'slug' => 'unique:features,slug',
            ], [
                'name.required' => 'Feature name is required',
                'name.unique' => 'Feature name already exists',
                'slug.unique' => 'Feature slug already exists',
            ]);

            $feature = new Feature;
            $feature->name = $request->name;
            $feature->slug = Str::slug($request->name);
            if ($request->active == 1) {
                $feature->active = 1;
            } else {
                $feature->active = 0;
            }
            $feature->save();

            activity($feature->name, 'new feature added');
            smilify('success', 'Feature created successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something happened!');

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $slug)
    {
        $feature = Feature::where('id', $id)
                          ->first();

        return view('backend.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feature  $feature
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
                'name' => 'required|unique:features,name,'.$id,
                'slug' => 'unique:features,slug,'.$id,
            ], [
                'name.required' => 'Feature name is required',
                'name.unique' => 'Feature name already exists',
                'slug.unique' => 'Feature slug already exists',
            ]);

            $feature = Feature::where('id', $id)
                              ->first();
            $feature->name = $request->name;
            $feature->slug = Str::slug($request->name);
            if ($request->active == 1) {
                $feature->active = 1;
            } else {
                $feature->active = 0;
            }
            $feature->save();

            activity($feature->name, 'feature updated');
            smilify('success', 'Feature updated successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something happened!');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $slug)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }
        // delete
        $feature = Feature::where('id', $id)
                          ->first();
        $feature->delete();
        activity($feature->name, 'feature deleted');
        smilify('success', 'Feature deleted successfully');

        return back();
    }

    // change Status
    public function changeStatus($id, $slug)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $feature = Feature::where('id', $id)
                          ->first();
        if ($feature->active == 1) {
            $feature->active = 0;
        } else {
            $feature->active = 1;
        }
        $feature->save();
        activity($feature->name, 'feature status changed');
        smilify('success', 'Feature status changed successfully');

        return back();
    }
}
