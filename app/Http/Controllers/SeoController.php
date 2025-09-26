<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function setup()
    {
        return view('backend.settings.seo.index');
    }

    public function update(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        if ($request->has('site_title')) {
            $system = Seo::where('name', 'site_title')->firstOrCreate();
            $system->name = 'site_title';
            $system->value = $request->site_title;
            $system->save();
        }

        if ($request->hasFile('site_thumbnail')) {
            $system = Seo::where('name', 'site_thumbnail')->firstOrCreate();
            $system->name = 'site_thumbnail';
            $system->value = fileUpload($request->site_thumbnail, 'seo');
            $system->save();
        }

        if ($request->has('site_description')) {
            $system = Seo::where('name', 'site_description')->firstOrCreate();
            $system->name = 'site_description';
            $system->value = $request->site_description;
            $system->save();
        }

        if ($request->has('site_keywords')) {
            $system = Seo::where('name', 'site_keywords')->firstOrCreate();
            $system->name = 'site_keywords';
            $system->value = $request->site_keywords;
            $system->save();
        }

        if ($request->has('site_author')) {
            $system = Seo::where('name', 'site_author')->firstOrCreate();
            $system->name = 'site_author';
            $system->value = $request->site_author;
            $system->save();
        }

        if ($request->has('site_copyright')) {
            $system = Seo::where('name', 'site_copyright')->firstOrCreate();
            $system->name = 'site_copyright';
            $system->value = $request->site_copyright;
            $system->save();
        }

        smilify('success', 'SEO settings updated successfully');
        return back();
    }
    //ENDS HERE
}
