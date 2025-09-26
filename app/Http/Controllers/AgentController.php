<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use DB;
use Hash;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\DepartmentAgent;

class AgentController extends Controller
{

    /**
     * INDEX
     */
    public function index()
    {
        $agents = Agent::where('user_id', Auth::id())
                       ->get();
        return view('backend.agents.index', compact('agents'));
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        /**
         * Validate the request
         */

        try {

        DB::beginTransaction(); // Start a database transaction

        $this->validate($request, [ // validation rules
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'string|max:255',
            'password' => 'required|string|min:6',
            'departments' => 'required|array',
        ],[ // validation messages
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email is too long',
            'email.unique' => 'Email is already taken',
            'phone.max' => 'Phone is too long',
            'password.required' => 'Password is required',
            'password.min' => 'Password is too short',
            'departments.required' => 'Departments is required',
        ]); // end of validation

        // create agent
        $user = new User; // create user
        $user->name = $request->name; // set name
        $user->email = $request->email; // set email
        $user->domain = $request->email; // set email
        $user->phone = $request->phone; // set email
        $user->password = Hash::make($request->password); // set password
        $user->restriction = 0; // set active
        $user->role = 'agent'; // set user type

        // create agent
        if ($user->save()) { // if agent is created
            $agent = new Agent; // create agent
            $agent->user_id = $user->id; // set user id
            $agent->assined_for_customer_id = Auth::id(); // set assined for customer id

            if ($agent->save()) {
                foreach ($request->departments as $department) {
                    $department_agent= new DepartmentAgent;
                    $department_agent->department_id = $department;
                    $department_agent->agent_id = $agent->id;
                    $department_agent->agent_user__id = $agent->user_id;
                    $department_agent->save();
                }
            }
        } // end of if agent is created

        DB::commit(); // Commit the transaction

        smilify('success', 'Agent created successfully'); // smilify success
        return back();

        } catch (\Exception $e) {
            // Something went wrong, so roll back the transaction
            DB::rollBack();

            smilify('error', $e->getMessage()); // smilify error
            return back();
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Validate the request
         */

        $this->validate($request, [ // validation rules
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'string|max:255',
            'departments' => 'required|array',
        ],[ // validation messages
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email is too long',
            'phone.max' => 'Phone is too long',
            'departments.required' => 'Departments is required',
        ]); // end of validation

        // create agent
        $user = User::where('id', $id)->first(); // create user
        $user->name = $request->name; // set name
        $user->phone = $request->phone; // set name

        if ($request->email != $user->email) { // if email is changed

            // validate
            $this->validate($request, [ // validation rules
                'email' => 'unique:users',
            ],[ // validation messages
                'email.unique' => 'Email is already taken',
            ]); // end of validation

            $user->email = $request->email; // set email
        } // end of if email is changed

        if ($request->password != '') { // if password is changed

            // validate
            $this->validate($request, [ // validation rules
                'password' => 'min:6',
            ],[ // validation messages
                'password.min' => 'Password is too short',
            ]); // end of validation

            $user->password = Hash::make($request->password); // set password
        } // end of if password is changed

        if ($user->save()) {

            // delete all departments
            DepartmentAgent::where('agent_user__id', $user->id)->delete();

            foreach ($request->departments as $department) {
                $department_agent= new DepartmentAgent;
                $department_agent->department_id = $department;
                $department_agent->agent_id = $user->agent->id;
                $department_agent->agent_user__id = $user->id;
                $department_agent->save();
            }
        }

        // show success message
        smilify('success', 'Agent has been updated successfully.'); // show success message
        return back(); // redirect to previous page
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agent = User::where('id', $id)->with('agent')->first(); // find agent
        if ($agent->agent->delete()) { // if agent is deleted
            $agent->delete(); // delete agent
        } // end of if agent is deleted
        smilify('success', 'Agent has been deleted successfully.'); // show success message
        return back(); // redirect to previous page
    }

    /**
     * Restricted the specified resource from storage.
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function restricted($id)
    {
        $agent = User::where('id', $id)->first(); // find agent
        if ($agent->restriction == 1) { // if agent is active
            $agent->restriction = 0; // set active to false
        } else { // if agent is not active
            $agent->restriction = 1; // set active to true
        } // end of if agent is not active
        $agent->save(); // save agent

        smilify('success', 'Agent has been updated successfully.'); // show success message
        return back(); // redirect to previous page
    }
    //ENDS
}
