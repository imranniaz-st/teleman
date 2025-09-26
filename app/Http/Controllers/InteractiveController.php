<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interactive;
use Auth;
use Log;
use File;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;
use Twilio\TwiML;

class InteractiveController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.interactive.index');
    }

    public function create()
    {
        return view('backend.interactive.create');
    }

    //ENDS
}
