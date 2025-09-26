<?php

namespace App\Http\Controllers;

use Str;
use Artisan;
use App\Models\SaasContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EditorController extends Controller
{
    /**
     * frontendJsonEditor
     */
    public function frontendJsonEditor(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $cidExists = SaasContent::where('cid', $request->cid)->exists();

        if ($cidExists) {
            // Invalidate the existing cache
            Cache::forget('saas_content_' . $request->cid);

            // Update the existing record
            $data = SaasContent::where('cid', $request->cid)->first();
            $data->text = $request->text;
        } else {
            // Create a new record
            $data = new SaasContent;
            $data->cid = $request->cid;
            $data->text = $request->text;
        }

        $data->save();

        // Update the cache with the new data
        Cache::put('saas_content_' . $request->cid, $data->text, now()->addMinutes(60));

        Artisan::call('cache:clear');

        return response()->json($data);
    }

    /**
     * frontendJsonupload
     */
    public function frontendJsonupload(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $folderPath = public_path('frontend/saas_content/uploads/');
        $image_parts = explode(';base64,', $request->text);
        $image_type_aux = explode('image/', $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath.uniqid().'.'.$image_type;
        file_put_contents($file, $image_base64);

        $imageName = Str::after($file, 'uploads/');

        $cid = SaasContent::where('cid', $request->cid)->exists();

        if ($cid != null) {
            $data = SaasContent::where('cid', $request->cid)->first();
            $data->cid = $request->cid;
            $data->text = $imageName;
        } else {
            $data = new SaasContent;
            $data->cid = $request->cid;
            $data->text = $imageName;
        }
        $data->save();

        return response()->json(['success' => true]);
    }
    //ENDS HERE
}
