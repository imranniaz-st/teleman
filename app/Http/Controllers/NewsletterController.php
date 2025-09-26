<?php

namespace App\Http\Controllers;

use App\Exports\NewslettersExport;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('backend.newsletters.index');
    }

    public function store(Request $request)
    {
        $newsletter = Newsletter::where('email', $request->email)->first();
        if ($newsletter) {
            return response()->json('exist', 200);
        }
        $newsletter = new Newsletter;
        $newsletter->name = $request->name;
        $newsletter->phone = $request->phone;
        $newsletter->email = $request->email;
        $newsletter->save();

        return response()->json('success', 200);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new NewslettersExport, 'payment_histories.csv');
    }
    //ENDS HERE
}
