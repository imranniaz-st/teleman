<?php

namespace App\Http\Controllers;

use App\Models\CallHistory;
use Illuminate\Http\Request;
use Log;

class QueueController extends Controller
{
    /**
     * The function updates the availability status of an agent.
     * 
     * @param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and input data. In this case, it is used to retrieve the "status" value from the
     * request.
     * 
     * @return the result of the `updateAgentAvailability` function, which is being passed the
     * authenticated user's ID (`auth()->id()`) and the status value from the request
     * (`->status`).
     */
    public function agent_status_update(Request $request)
    {
        updateAgentAvailability(auth()->id(), $request->status);
    }

    /**
     * The function "get_queue_list" returns the queue for a given number.
     * 
     * @param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve information from the HTTP request made to the server. It contains data such as the
     * request method, headers, and any parameters or data sent in the request. In this case, it is
     * being used to retrieve the value
     * 
     * @return the result of the `getQueue` function, which is being called with the value of
     * `->my_number` as an argument.
     */
    public function get_queue_list(Request $request)
    {
        return getQueue($request->my_number);
    }

    /**
     * The function `download_all_recordings()` downloads all recordings from a database and stores
     * them in a specified folder if they do not already exist.
     */
    public function download_all_recordings()
    {
        $histories = CallHistory::all();
        $destinationFolder = public_path('/vc'); // Change 'your_folder_name' to the folder name where you want to store the file.
        
        // Check if the directory exists, if not, create it.
        if (!\File::isDirectory($destinationFolder)) {
            \File::makeDirectory($destinationFolder, 0775, true); // 0775 is the permission, true is for recursive creation
        }

        /* The `foreach` loop is iterating over each element in the `` array. */
        foreach ($histories as $history) {
            /* The code block is checking if the `record_file` property of each `CallHistory` object is
            not null. If it is not null, it creates a file name using the `caller_uuid` property and
            the extension `.mp3`. */
            if ($history->record_file != null) {
                $fileName = 'recording_' . $history->caller_uuid . '.mp3'; // Change '.mp3' to the appropriate file extension if required.
                if (!file_exists($destinationFolder . DIRECTORY_SEPARATOR . $fileName)) {
                    storeFileFromURL($history->caller_uuid, $history->record_file, 'vc');
                }
            }
        }

        smilify('success', 'Recordings downloaded successfully');
        return back();
    }
    //ENDS
}
