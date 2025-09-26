<?php

namespace App\Http\Controllers;

use DB;
use Artisan;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DepartmentProvider;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.departments.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // validation
        $request->validate([
            'name' => 'required',
            'provider_id' => 'required',
            'option_status' => 'required',
        ]);


        try {
            DB::beginTransaction(); // Start a database transaction

            $department = new Department;
            $department->name = $request->name;
            $department->user_id = auth()->id();

            $department->outbound = $request->option_status == 'outbound' ? true : false;
            $department->inbound = $request->option_status == 'inbound' ? true : false;
            $department->ivr = $request->option_status == 'ivr' ? true : false;
            $department->status = $request->status == null ? false : true;

            if ($department->save()) {
                foreach ($request->provider_id as $provider) {
                    $department_provider = new DepartmentProvider;
                    $department_provider->department_id = $department->id;
                    $department_provider->provider_id = $provider;
                    $department_provider->status = true;
                    $department_provider->save();
                }
            }

            Artisan::call('cache:clear');

            DB::commit(); // If everything is successful, commit the transaction

            if ($department->ivr == 'ivr') {
                return redirect()->route('dashboard.interactive.create', $department->id);
            }

        } catch (\Exception $e) {
            // Something went wrong, so roll back the transaction
            DB::rollBack();
        }

        smilify('success', 'Department created successfully');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validation
        $request->validate([
            'name' => 'required',
            'provider_id' => 'required',
            'option_status' => 'required',
        ]);

        try {
            DB::beginTransaction(); // Start a database transaction

            $department = Department::find($id);
            $department->providers()->delete();

            $department->name = $request->name;

            $department->outbound = $request->option_status == 'outbound' ? true : false;
            $department->inbound = $request->option_status == 'inbound' ? true : false;
            $department->ivr = $request->option_status == 'ivr' ? true : false;
            $department->status = $request->status == null ? false : true;

            if ($department->save()) {
                foreach ($request->provider_id as $provider) {
                    $department_provider = new DepartmentProvider;
                    $department_provider->department_id = $department->id;
                    $department_provider->provider_id = $provider;
                    $department_provider->status = true;
                    $department_provider->save();
                }
            }

            Artisan::call('cache:clear');

            DB::commit(); // If everything is successful, commit the transaction

            if ($department->ivr == 'ivr') {
                return redirect()->route('dashboard.interactive.create', $department->id);
            }

        } catch (\Exception $e) {
            // Something went wrong, so roll back the transaction
            DB::rollBack();
        }

        smilify('success', 'Department updated successfully');
        return back();
    }

    /**
     * The above function is used to delete a department and its related providers and agents, and it
     * handles any exceptions that may occur during the process.
     * 
     * @param id The parameter "id" is the unique identifier of the department that needs to be
     * deleted.
     * 
     * @return a redirect back to the previous page.
     */
    public function destroy($id)
    {
        try {
            // Begin Transaction
            DB::transaction(function () use ($id) {
                $department = Department::find($id);
                // Delete related providers
                $department->providers()->delete();
                // department agent
                $department->agents()->delete();
                // Delete department
                $department->delete();
            });

            Artisan::call('cache:clear');

            smilify('success', 'Department deleted successfully');
            // Flash success message and redirect
            return back();
        } catch (\Exception $e) {
            // Flash error message and redirect in case of an exception
            smilify('error', $e->getMessage());
            return back();
        }
    }

    public function saveJson(Request $request, $departmentId)
    {

        // Assuming you're sending JSON data and department ID from your front-end
        $jsonData = $request->input('json');
        $department = Department::find($departmentId);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $department->json = $jsonData;
        $department->save();

        return response()->json(['message' => 'JSON saved successfully']);
    }
    // ENDS
}
