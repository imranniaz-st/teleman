<?php

namespace App\Http\Controllers;

use App\Models\Provider;

class AnalyticsController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return view('backend.analytics.index');
    }

    /**
     * Index index_ajax
     */
    public function index_ajax()
    {
        return view('backend.analytics.index_ajax');
    }

    /**
     * analytic
     */
    public function analytic($account_sid, $phone )
    {
        return view('backend.analytics.analytic', compact('account_sid', 'phone'));
    }

    /**
     * analytic ajax
     */
    public function analytic_ajax($account_sid, $phone)
    {
        return view('backend.analytics.analytic_ajax', compact('account_sid', 'phone'));
    }
    //ENDS
}
