<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Artisan;
use App\Models\Group;
use App\Models\Contact;
use App\Models\Campaign;
use Illuminate\Support\Str;
use App\Models\GroupContact;
use Illuminate\Http\Request;
use App\Exports\ContactsExport;
use App\Imports\ContactsImport;
use App\Models\CampaignSchedule;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.contacts.index');
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
            'phone' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone number is required',
        ]);

        try {
            $contact = new Contact;
            $contact->user_id = Auth::id();
            $contact->name = $request->name;
            $contact->phone = Str::startsWith($request->phone, '+') ? $request->phone : '+'.$request->phone;
            $contact->country = $request->country;
            $contact->gender = $request->gender;
            $contact->dob = $request->dob;
            $contact->profession = $request->profession;
            $contact->save();

            /**
             * Assign to group contacts
             */
            if ($request->groups_ids != null) {
                foreach ($request->groups_ids as $group_id) {
                    $group_contact = GroupContact::where('group_id', $group_id)
                                                    ->where('contact_id', $contact->id)
                                                    ->first();
                    if ($group_contact == null) {
                        $group_contact = new GroupContact;
                        $group_contact->contact_id = $contact->id;
                        $group_contact->group_id = $group_id;
                        $group_contact->save();
                    }
                }
            }

            smilify('success', 'Contact created successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($contact_id)
    {
        $contact = Contact::where('id', $contact_id)->with('group_contacts')->first();

        return view('backend.contacts.show', compact('contact'));
    }

    /**
     * update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     */
    public function update(Request $request, $contact_id)
    {

        // validation
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'profession' => 'required',
            'groups_ids' => 'required',
        ], [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone number is required',
            'country.required' => 'Country is required',
            'gender.required' => 'Gender is required',
            'dob.required' => 'Date of birth is required',
            'profession.required' => 'Profession is required',
            'groups_ids.required' => 'Select a Group is required',
        ]);

        try {
            $contact = Contact::where('id', $contact_id)->first();
            $contact->user_id = Auth::id();
            $contact->name = $request->name;
            $contact->phone = Str::startsWith($request->phone, '+') ? $request->phone : '+'.$request->phone;
            $contact->country = $request->country;
            $contact->gender = $request->gender;
            $contact->dob = $request->dob;
            $contact->profession = $request->profession;
            $contact->save();

            /**
             * Assign to group contacts
             */
            if ($request->groups_ids != null) {
                foreach ($request->groups_ids as $group_id) {
                    $group_contact = GroupContact::where('group_id', $group_id)
                                                    ->where('contact_id', $contact_id)
                                                    ->first();
                    if ($group_contact == null) {
                        $group_contact = new GroupContact;
                        $group_contact->contact_id = $contact_id;
                        $group_contact->group_id = $group_id;
                        $group_contact->save();
                    }
                }
            }

            smilify('success', 'Contact Updated successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back();
        }
    }

    /**
     * Destroy
     */
    public function destroy($contact_id)
    {
        try {
            $contact = Contact::where('id', $contact_id)->first();
            $contact->group_contacts()->delete();
            $contact->campaign_voice()->delete();
            $contact->campaign_voice_status_log()->delete();
            $contact->delete();

            smilify('success', 'Contact deleted successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Contact can not be deleted');

            return back();
        }
    }

    /**
     * group_index
     */
    public function group_index()
    {
        return view('backend.groups.index');
    }

    /**
     * group_store
     */
    public function group_store(Request $request)
    {
        /**
         * Validation
         */
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
        ]);

        try {
            $group = new Group;
            $group->user_id = Auth::id();
            $group->name = $request->name;
            $group->description = $request->description;

            if ($request->status == 1) {
                $group->status = true;
            } else {
                $group->status = false;
            }

            $group->save();

            smilify('success', 'Group created successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back();
        }
    }

    /**
     * group_show
     */
    public function group_show($group_id)
    {
        $group = Group::where('id', $group_id)->first();

        return view('backend.groups.show', compact('group'));
    }

    /**
     * group_update
     */
    public function group_update(Request $request, $group_id)
    {
        /**
         * Validation
         */
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
        ]);
        try {
            $group = Group::where('id', $group_id)->first();
            $group->user_id = Auth::id();
            $group->name = $request->name;
            $group->description = $request->description;

            if ($request->status == 1) {
                $group->status = true;
            } else {
                $group->status = false;
            }
            $group->save();

            smilify('success', 'Group updated successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong');

            return back();
        }
    }

    /**
     * group_destroy
     */
    public function group_destroy($group_id)
    {
        try {
            $group = Group::where('id', $group_id)->first();
            $group->group_contacts()->delete();
            // make campaign group_id null
            $campaigns = Campaign::where('group_id', $group_id)->get();
            foreach ($campaigns as $campaign) {
                $campaign->group_id = null;
                $campaign->save();
            }
            // make campaign_schedules group_id null
            $campaign_schedules = CampaignSchedule::where('group_id', $group_id)->get();
            foreach ($campaign_schedules as $campaign_schedule) {
                $campaign_schedule->group_id = null;
                $campaign_schedule->save();
            }
            $group->delete();

            smilify('success', 'Group deleted successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Group can not be deleted');

            return back();
        }
    }

    /**
     * group_assign
     */
    public function group_assign($group_id, $group_slug)
    {
        $group = Group::find($group_id);

        return view('backend.groups.assign_contacts', compact('group'));
    }

    /**
     * group_assign_store
     */
    public function group_assign_store($group_id, $group_slug, Request $request)
    {

        /**
         * validation
         */
        $request->validate([
            'contact_ids' => 'required',
        ], [
            'contact_ids.required' => 'Please select at least one contact',
        ]);

        $group = Group::find($group_id);

        /**
         * Delete all contacts in this group
         */
        GroupContact::where('group_id', $group_id)->delete();

        /**
         * Store each contact in the group
         */
        foreach ($request->contact_ids as $contact) {
            $group_contact = GroupContact::where('group_id', $group_id)->where('contact_id', $contact)->first();

            if (! $group_contact) {
                $group_contact = new GroupContact;
                $group_contact->user_id = Auth::id();
                $group_contact->group_id = $group_id;
                $group_contact->contact_id = $contact;
                $group_contact->save();
            }
        }

        smilify('success', 'Contacts assigned successfully');

        return back();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts.csv');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        // Excel::import(new ContactsImport, $request->file('csv'));

        $directoryPath = public_path('uploads/csv/' . auth()->id());

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        splitCsv($request->file('csv'));

        // Artisan::call('import:csv');

        smilify('success', 'Contact imported successfully');

        return back();
    }

    /**
     * This PHP function finds contact information based on a caller's phone number and returns it in a
     * JSON response.
     * 
     * @param Request request  is an instance of the Request class, which is used to retrieve
     * data from HTTP requests. It contains information about the current request, such as the HTTP
     * method, headers, and parameters. In this function,  is used to retrieve the
     * caller_number parameter from the request.
     * 
     * @return A JSON response with the 'contact_info' key and its corresponding value, which is either
     * the information of the contact found by the 'find_contact' function or the string 'No Caller
     * data found.' if no contact is found.
     */
    public function find_contact_by_number(Request $request)
    {
        $contact_info = find_contact_by_phone($request->caller_number);

        return response()->json([
            'contact_info' => $contact_info,
       ]);
    }

    public function searchContacts(Request $request)
    {
        if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $contacts = Contact::HasAgent()
                            ->where(function($query) use ($search) {
                                $query->where('name', 'LIKE', "%$search%")
                                    ->orWhere('phone', 'LIKE', "%$search%");
                            })
                            ->orderBy('name', 'asc')
                            ->simplePaginate(50);
        } else {
            $contacts = allContacts();
        }

        return view('backend.contacts.index', compact('contacts'));
    }


    /**
     * The function assigns all contacts to a specified group, deleting any existing contacts in the
     * group and inserting new contacts in batches.
     * 
     * @param group_id The group_id parameter is the ID of the group to which you want to assign all
     * contacts.
     * 
     * @return the user back to the previous page.
     */
    public function assign_all_contacts($group_id)
    {
        $group = Group::find($group_id);

        /**
         * Delete all contacts in this group
         */
        GroupContact::where('group_id', $group_id)->delete();

        $contacts = Contact::where('user_id', Auth::id())->get();

        $groupContactData = [];

        foreach ($contacts as $contact) {
            $group_contact = GroupContact::where('group_id', $group_id)->where('contact_id', $contact->id)->first();

            if (!$group_contact) {
                $groupContactData[] = [
                    'user_id' => Auth::id(),
                    'group_id' => $group_id,
                    'contact_id' => $contact->id,
                ];
            }
        }

        // Insert the group contacts in batches
        $chunkSize = 100; // You can adjust this batch size as needed
        $groupContactChunks = array_chunk($groupContactData, $chunkSize);

        foreach ($groupContactChunks as $chunk) {
            GroupContact::insert($chunk);
        }

        smilify('success', 'All Contacts assigned successfully');

        return back();
    }

    /**
     * The function removes all contacts from a specified group and returns back to the previous page.
     * 
     * @param group_id The group_id parameter is the unique identifier of the group for which you want
     * to remove all contacts.
     * 
     * @return the user back to the previous page.
     */
    public function assign_remove_contacts($group_id)
    {
        $group = Group::find($group_id);

        /**
         * Delete all contacts in this group
         */
        GroupContact::where('group_id', $group_id)->delete();

        smilify('success', 'All Contacts removed successfully');

        return back();
    }

/**
 * The function "getContactsAjax" retrieves all contacts and returns them as a JSON response.
 * 
 * @param Request request The  parameter is an instance of the Request class, which represents
 * an HTTP request. It contains information about the request such as the request method, headers, and
 * input data. In this case, it is used to retrieve the contacts data.
 * 
 * @return a JSON response containing all contacts.
 */
    public function getContactsAjax(Request $request)
    {
        $query = $request->get('query');
        $results = Contact::where('name', 'like', '%' . $query . '%')
                        ->orWhere('phone', 'like', '%' . $query . '%')
                        ->get();

        return response()->json($results);
    }

    public function check_import_status()
    {
        return csv_import_progress();
    }
}
