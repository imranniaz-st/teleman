<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Twilio\Rest\Client;
use DB;

class MessageController extends Controller
{

    /**
     * It returns a view called `backend.messages.index`
     * 
     * @return The view file located at resources/views/backend/messages/index.blade.php
     */
    public function index()
    {
        return view('backend.messages.index');
    }

    /**
     * It returns a view called `message` from the `backend.messages` folder
     * 
     * @param user_number The user number of the user you want to send the message to.
     * 
     * @return A view called message.blade.php
     */
    public function message($my_number, $user_number = null)
    {
        /* Checking if the user number is not null. If it is not null, it calls the `message_as_seen`
        function. */
        if ($user_number != null) {
            message_as_seen($user_number, $my_number);
        }
        return view('backend.messages.message', compact('user_number', 'my_number'));
    }
    /**
     * It receives an incoming message, saves it to the database, and returns a response to the sender
     * 
     * @param Request request The incoming request from Twilio.
     */
    public function processIncomingMessage(Request $request)
    {
        try {
            $sender = $request->input('From');
            $recipient = $request->input('To');
            $content = $request->input('Body');
            $sentAt = now();

            $user_id = find_user_id_by_number($recipient);

            deduct_credit_by_using_sms(sms_cost_for_per_sms(get_country_code_from_number($recipient)), $user_id);

            // Save message to the database
            DB::table('messages')->insert([
                'sender' => $sender,
                'recipient' => $recipient,
                'content' => $content,
                'user_number' => $sender,
                'my_number' => $recipient,
                'sent_at' => $sentAt,
                'seen' => 0,
                'user_id' => $user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * It returns a JSON response of all the messages in the database, ordered by the time they were
     * sent
     * 
     * @return A JSON object containing all the messages in the database.
     */
    public function show($my_number, $user_number = null)
    {

        /* Checking if the user number is not null. If it is not null, it calls the `message_as_seen`
        function. */
        if ($user_number != null) {
            message_as_seen($user_number, $my_number);
        }

        $messages = conversations($user_number, $my_number);
        return response()->json($messages);
    }

    /* A function that sends a message to a recipient. */
    public function send(Request $request, $user_number, $my_number)
    {
        // Get the message content and recipient phone number from the request
        $content = $request->content;
        $sentAt = now();

        $accountSid = user_active_provider_info()->account_sid;
        $authToken  = user_active_provider_info()->auth_token;
        $phone_number = $my_number;


        if ($request->hasFile('file')) {
            $mms = fileUpload($request->file, 'mms');
        }

        // Send the message using the Twilio API
        $twilio = new Client($accountSid, $authToken);

        /* This code block is checking if the variable `` is set. If it is set, it means that a
        file has been uploaded and the message should be sent as a multimedia message (MMS). In that
        case, the code creates a message using the Twilio API and includes the media URL of the
        uploaded file. */
        if (isset($mms)) {
            /* Sending a message to the recipient using the Twilio API. */
            $message = $twilio->messages->create(
                $user_number,
                [
                    'from' => $phone_number,
                    "mediaUrl" => [url('/') . '/' . $mms]
                ]
            );
        }else {
            /* Sending a message to the recipient using the Twilio API. */
            $message = $twilio->messages->create(
                $user_number,
                [
                    'from' => $phone_number,
                    'body' => $content,
                ]
            );
        }

        // Return a response indicating success or failure
        if ($message->sid) {

            deduct_credit_by_using_sms(sms_cost_for_per_sms(get_country_code_from_number($phone_number)), auth()->id());

            // Save message to the database
            DB::table('messages')->insert([
                'sender' => $phone_number,
                'recipient' => $user_number,
                'content' => ($content === null || $content === '') ? null : $content,
                'mms' => $mms ?? null,
                'user_number' => $user_number,
                'my_number' => $my_number,
                'sent_at' => $sentAt,
                'seen' => 1,
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function compose_new_message(Request $request, $my_number)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }
        
        try {

            // Get the message content and recipient phone number from the request
            $content = $request->content;
            $to = $request->phone == null ? $request->new_phone : $request->phone;
            $sentAt = now();

            if ($to == null && $content == null) {
                smilify('warning', 'Please enter a phone number and message content');
                return back();
            }

            $accountSid = user_active_provider_info()->account_sid;
            $authToken  = user_active_provider_info()->auth_token;
            $phone_number = $my_number;

            // Send the message using the Twilio API
            $twilio = new Client($accountSid, $authToken);

            /* Sending a message to the recipient using the Twilio API. */
            $message = $twilio->messages->create(
                $to,
                [
                    'from' => $phone_number,
                    'body' => $content,
                ]
            );

            // Return a response indicating success or failure
            if ($message->sid) {

                deduct_credit_by_using_sms(sms_cost_for_per_sms(get_country_code_from_number($to)), auth()->id());

                // Save message to the database
                DB::table('messages')->insert([
                    'sender' => $phone_number,
                    'recipient' => $to,
                    'content' => $content,
                    'user_number' => $to,
                    'my_number' => $my_number,
                    'sent_at' => $sentAt,
                    'seen' => 1,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                smilify('success', 'Message sent successfully.');
                return back();
            } else {
                smilify('error', 'Something went wrong.');
                return back();
            }
        } catch (\Throwable $th) {
                smilify('error', 'Something went wrong.');
                return back();
        }

        
    }

    /**
     * This PHP function fetches unseen messages for a specific user and returns them as a JSON
     * response.
     * 
     * @param Request request  is an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request. It contains information about the request such as the HTTP method,
     * headers, and input data. In this function, it is used to retrieve any input data that may have
     * been sent with the request.
     * @param my_number The parameter "my_number" is likely a column in the "messages" table that
     * stores the phone number of the user who sent or received the message. The function is using this
     * parameter to filter the messages and retrieve only those messages that belong to the user with
     * the specified phone number.
     * 
     * @return a JSON response containing all the messages that match the given criteria (where
     * my_number is equal to , user_id is equal to the authenticated user's ID, and seen is
     * equal to 0), sorted by sent_at in descending order.
     */
    public function messages_ajax_fetch($my_number)
    {
        $messages = new_message_found($my_number);
        return response()->json($messages);
    }

    /**
     * The function refreshMessages returns the rendered view 'backend.messages.list' with the variable
     * 'my_number' passed to it.
     * 
     * @param my_number The parameter `` is a variable that represents a phone number. It is
     * passed to the `refreshMessages` function as an argument.
     * 
     * @return the rendered view 'backend.messages.list' with the variable 'my_number' passed to it.
     */
    public function refreshMessages($my_number)
    {
        return view('backend.messages.list', ['my_number' => $my_number])->render();
    }

    /**
     * The function deletes a message from the database based on the user's number and returns a
     * success message.
     * 
     * @param my_number The parameter "my_number" represents the number associated with the message
     * that needs to be deleted.
     * @param user_number The user_number parameter is the number of the user whose message is being
     * deleted.
     * 
     * @return the result of the `back()` function.
     */
    public function destroy($my_number, $user_number)
    {
        // Delete the message from the database
        DB::table('messages')->where('user_number', $user_number)->where('my_number', $my_number)->delete();

        smilify('success', 'Message deleted successfully.');
        return back();
    }
    //ENDS
}
