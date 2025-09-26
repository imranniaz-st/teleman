<?php

namespace App\Http\Controllers;

use App\Models\Mojsms;
use Illuminate\Http\Request;

class MojsmsController extends Controller
{
    public function index()
    {
        $mojsms = Mojsms::where('user_id', auth()->id())->first();
        return view('addons.mojsms.index', compact('mojsms'));
    }

    public function store(Request $request)
    {
        // update or create
        $mojsms = Mojsms::updateOrInsert(
            ['user_id' => auth()->id()], // Column to check for existing records
            [
                'bearer_token' => $request->bearer_token, 
                'sender_id' => $request->sender_id
            ]
        );

        smilify('success', 'MojSMS configured successfully');
        return back();
    }
    // ENDS
}
