<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpiryController extends Controller
{
    public function checkExpiry(Request $request)
    {
        // check subscription expire
        $subscription = Subscription::where('domain', trimDomain($request->domain))->first(); // live
        if ($subscription->end_at < Carbon::now()) {
            return 'NO'; // expired
        } else {
            return 'YES'; // not expired
        }
    }
    //ENDS HERE
}
