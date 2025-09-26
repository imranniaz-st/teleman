<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestConnectionMail;
use Mail;

class SmtpController extends Controller
{
    public function index()
    {
        return view('backend.smtp.index');
    }

    public function store(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        overWriteEnvFile('MAIL_MAILER', $request->driver);
        overWriteEnvFile('MAIL_HOST', $request->host);
        overWriteEnvFile('MAIL_PORT', $request->port);
        overWriteEnvFile('MAIL_USERNAME', $request->username);
        overWriteEnvFile('MAIL_PASSWORD', $request->password);
        overWriteEnvFile('MAIL_ENCRYPTION', $request->encryption);
        overWriteEnvFile('MAIL_FROM_ADDRESS', $request->from);
        overWriteEnvFile('MAIL_FROM_NAME', $request->from_name);

        smilify('success', 'Mail settings updated successfully');

        return back();
    }

    /**
     * The function sends a test email using the TestConnectionMail class and displays a success
     * message if the email is sent successfully, or an error message if an exception occurs.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server. It contains information about the request, such
     * as the request method, URL, headers, and any data sent with the request. In this code, the
     *  parameter is used to access the
     * 
     * @return a response using the `back()` function.
     */
    public function test(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        try {
            // send test mail from TestConnectionMail
            Mail::to($request->email)->send(new TestConnectionMail($request->email));

            smilify('success', 'Mail sent successfully');

            return back();
        } catch (\Throwable $th) {
            smilify('error', $th->getMessage());
            return back();
        }
    }
    //ENDS HERE
}
