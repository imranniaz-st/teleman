<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BraintreeController extends Controller
{
    // setup payment
    public function setup()
    {
        return view('backend.settings.braintree.setup');
    }

    // setup payment
    public function update(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        overWriteEnvFile('BT_ENVIRONMENT', $request->BT_ENVIRONMENT);
        overWriteEnvFile('BT_MERCHANT_ID', $request->BT_MERCHANT_ID);
        overWriteEnvFile('BT_PUBLIC_KEY', $request->BT_PUBLIC_KEY);
        overWriteEnvFile('BT_PRIVATE_KEY', $request->BT_PRIVATE_KEY);
        if ($request->BRAINTREE == 1) {
            overWriteEnvFile('BRAINTREE', 'YES');
        } else {
            overWriteEnvFile('BRAINTREE', 'NO');
        }
        activity('Braintree', 'Setup done');
        smilify('success', 'Braintree setup done');

        return back();
    }
    //ENDS HERE
}
