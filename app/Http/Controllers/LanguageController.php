<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Str;
use App\Models\Demo;
use File;
use Artisan;

class LanguageController extends Controller
{
    
    /**
     * It returns the view of the language page.
     */
    public function langIndex()
    {
        $languages = Language::get();
        return view('backend.settings.language.language',compact('languages'));
    }


    /**
     * It creates a new language and stores it in the database.
     * 
     * @param Request request The request object.
     */
    public function langStore(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $request->validate([
            'code' => ['required', 'unique:languages'],
            'name' => ['required', 'unique:languages'],
            'image' => ['required', 'unique:languages']
        ],[
            'code.required'=>'Code is required',
            'name.required'=>'Name is Required',
            'image.required'=>'Image is required'
        ]);

        $lan = new Language;
        $lan->code =Str::lower(str_replace(' ','_',$request->code));
        $lan->name = $request->name;
        $lan->image = $request->image;
        $lan->save();

        File::put(base_path('/resources/lang/' . $lan->code . '.json'),'{}');

        smilify('success', translate('Language Created Successfully'));
        return back();
    }

    /**
     * It deletes a language from the database.
     * 
     * @param id The id of the language you want to delete.
     * 
     * @return A view with a success message.
     */
    public function langDestroy($id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $lang = Language::where('id', $id)->forceDelete();

        activity(translate('Language ' . $lang->name . ' deleted'));

        smilify('success', translate('Language Deleted Successfully'));

        return back();
    }

    /**
     * A function that is used to translate the language.
     * 
     * @param id The id of the language you want to translate
     */
    public function translate_create($id)
    {
        $lang = Language::findOrFail($id);
        return view('backend.settings.language.translate',compact('lang'));
    }

    /**
     * It saves the translation data to the JSON file.
     * 
     * @param Request request The request object.
     */
    public function translate_store(Request $request)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }

        $language = Language::findOrFail($request->id);

        //check the key have translate data
        $data = openJSONFile($language->code);
        foreach ($request->translations as $key => $value) {
            $data[$key] = $value;
        }

        //save the new keys translate data
        saveJSONFile($language->code, $data);
        return back()->with('success',translate('Translation has been saved.'));
    }

    /**
     * It changes the locale of the application to the one specified in the request
     * 
     * @param Request request The request object.
     * 
     * @return The user is being redirected back to the previous page.
     */
    public function languagesChange(Request $request)
    {
        session(['locale' => $request->code]);
        Artisan::call('optimize:clear');
        return back();
    }

    /**
     * It changes the default language of the application.
     * 
     * @param id The id of the language you want to set as default.
     * 
     * @return A redirect to the previous page with a success message.
     */
    public function defaultLanguage($id)
    {

        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');
            return back();
        }
        
        $language = Language::findOrFail($id);
        overWriteEnvFile('DEFAULT_LANGUAGE', $language->code);
        return redirect()->back()->with('success', translate('Successful.'));
    }

    //ENDS
}