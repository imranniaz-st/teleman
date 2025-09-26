<?php

namespace App\Http\Controllers;

use File;
use Artisan;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddonsController extends Controller
{

    /**
     * Addons View page 
     */
    public function index()
    {
        return view('addons.index');
    }

    /**
     * This PHP function installs an addon by uploading and extracting a zip file, and then moves the
     * extracted files to the appropriate directory.
     * 
     * @param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an incoming HTTP request. It contains information about the request such as the HTTP
     * method, headers, and any data that was sent with the request. In this function,  is used
     * to retrieve the uploaded file and its name.
     * 
     * @return a response to the client, either with a success message or an error message. The
     * specific response depends on the success or failure of the installation process.
     */
    public function install(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            return back()->with('message', 'This is demo purpose only');
        }

        try {

            // Create addons directory if it does not exist
            if (!file_exists(base_path('addons'))) {
                mkdir(base_path('addons'), 0777, true);
            }

            // Upload & extract

            if ($request->hasFile('addon_file')) {
                $file = $request->file('addon_file');
                $filename = $file->getClientOriginalName();
                $path = base_path('addons/'.$filename);
                $file->move(base_path('addons'), $filename);

                // Extract the uploaded file
                $zip = new ZipArchive;
                $res = $zip->open($path);
                if ($res === TRUE) {
                    $zip->extractTo(base_path('addons'));
                    $zip->close();

                    // Delete the extracted file
                    unlink($path);
                } else {
                    smilify('error', 'Something went wrong. Please contact to the developer thecodestudioxyz@gmail.com');
                    return back()->with('message', 'Doh! Something went wrong. Please try again later.'); // echo a message
                }
            }

            // Installation

            $path = base_path('addons/'); // path to your patch directory
            $version = file_get_contents(base_path('addons/.version')); // get the version from the .version file
            $files = scandir($path); // scan the update patch directory

            $message = '';

            foreach ($files as $athely) {
                if ($athely == '.' || $athely == '..') {
                    continue;
                } // remove . and ..

                $extension = pathinfo($athely, PATHINFO_EXTENSION); // get only zip files

                if ($extension == 'zip') {
                    $fileNameWithoutExtension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $athely); // remove extension
                        $replaceUnderScore = str_replace('@', '/', $fileNameWithoutExtension); // replace underscore with slash

                        $target_path = base_path($replaceUnderScore); // target path to move the file
                        $file = $path.$athely; // path to the file

                        $zip = new ZipArchive; // create a new zip archive object
                        $res = $zip->open($file); // open the zip file

                        if ($res > 0) { // if the zip file is opened successfully
                            $zip->extractTo($target_path); //   extract the zip file to the target path
                            $zip->close(); //   close the zip file
                            $message .= "WOOT! $file extracted to $target_path"; //  echo a message
                        } else {
                            \Artisan::call('optimize:clear'); //   clear the cache
                            $message .= "Doh! I couldn't open $athely"; //   echo a message
                        }
                }
            }

            overWriteEnvFile($version, 'YES'); // update the version in the env('VERSION') file
            Artisan::call('optimize:clear'); //   clear the cache

            smilify('success', 'Addon installed successfully');
            return back();

        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong. Please contact to the developer thecodestudioxyz@gmail.com');
            return back()->with('message', 'Doh! Something went wrong. Please try again later.'); // echo a message
        }
    }
    //ENDS
}
