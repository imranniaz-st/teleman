<?php

namespace App\Http\Controllers;

use App\Models\DocumentKyc;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class DocumentKycController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.kyc.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $this->validate($request, [
            'document' => 'required|mimes:pdf,docx,jpg|max:2048',
        ], [
            'document.required' => 'Document file is required.',
            'document.mimes' => 'File type is invalid.',
            'document.max' => 'File size is too large.',
        ]);

        if (!user_kyc_document(Auth::id())) {
            $kyc = new DocumentKyc;
            $kyc->user_id = Auth::id();
            $kyc->documents_path = fileUpload($request->document, 'kyc_documents');
            $kyc->approval = 0;
            $kyc->seen = 0;
        }else {
            $kyc = DocumentKyc::where('user_id', Auth::id())->first();
            $kyc->documents_path = fileUpload($request->document, 'kyc_documents');
            $kyc->approval = 0;
            $kyc->created_at = Carbon::now();
            $kyc->seen = 0;
        }

        $kyc->save();
        
        smilify('success', 'Document submitted successfully');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentKyc  $documentKyc
     * @return \Illuminate\Http\Response
     */
    public function review_document($user_id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $kyc = DocumentKyc::where('user_id', $user_id)->first();
        $file_path = public_path($kyc->documents_path);
        return response()->download($file_path);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentKyc  $documentKyc
     * @return \Illuminate\Http\Response
     */
    public function approved($user_id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $kyc = DocumentKyc::where('user_id', $user_id)->first();
        $kyc->approval = 1;
        $kyc->seen = 1;
        $kyc->save();

        smilify('success', 'Approved');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentKyc  $documentKyc
     * @return \Illuminate\Http\Response
     */
    public function rejected($user_id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $kyc = DocumentKyc::where('user_id', $user_id)->first();
        $kyc->approval = 2;
        $kyc->seen = 1;
        $kyc->save();

        smilify('success', 'Rejected');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentKyc  $documentKyc
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $kyc = DocumentKyc::where('user_id', $user_id)->first();
        $kyc->delete();

        smilify('success', 'Removed');
        return back();
    }
}
