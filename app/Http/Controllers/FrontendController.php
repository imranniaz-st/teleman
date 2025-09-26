<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use Session;
use Spatie\Sitemap\SitemapGenerator;

class FrontendController extends Controller
{
    // index
    public function index()
    {
        if (env('FRONTEND_THEME') == 'ACTIVE') { // if ACTIVE is set to ACTIVE in .env file
            return view('frontend.'.active_theme().'.layouts.index'); // Frontend Theme
        }

        return redirect()->route('backend'); // Dashboard view

    }

    // pricing
    public function pricing()
    {
        return view('frontend.pricing.index');
    }

    // gateways
    public function gateways()
    {
        $invoice = Session::get('invoice');

        $payment = PaymentHistory::where('invoice', $invoice)->first();

        $user_details = Session::get('subscription_details');

        return view('frontend.gateways.index', compact('payment', 'user_details'));
    }

    //sitemap
    public function sitemap()
    {
        SitemapGenerator::create(env('APP_URL'))->writeToFile('public/sitemap.xml');
    }

    //ENDS HERE
}
