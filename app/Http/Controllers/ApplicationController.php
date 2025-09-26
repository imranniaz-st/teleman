<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CustomCssScript;
use Illuminate\Http\Request;
use Artisan;

class ApplicationController extends Controller
{
    public function setup()
    {
        return view('backend.settings.application.index');
    }

    public function update(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        if ($request->has('site_name')) {
            $system = Application::where('name', 'site_name')->firstOrCreate();
            $system->name = 'site_name';
            $system->value = $request->site_name;
            $system->save();
            overWriteEnvFile('APP_NAME', $system->value);
        }

        if ($request->has('site_email')) {
            $system = Application::where('name', 'site_email')->firstOrCreate();
            $system->name = 'site_email';
            $system->value = $request->site_email;
            $system->save();
        }

        if ($request->has('site_phone')) {
            $system = Application::where('name', 'site_phone')->firstOrCreate();
            $system->name = 'site_phone';
            $system->value = $request->site_phone;
            $system->save();
        }

        if ($request->has('test_phone')) {
            $system = Application::where('name', 'test_phone')->firstOrCreate();
            $system->name = 'test_phone';
            $system->value = $request->test_phone;
            $system->save();
        }

        if ($request->has('site_facebook')) {
            $system = Application::where('name', 'site_facebook')->firstOrCreate();
            $system->name = 'site_facebook';
            $system->value = $request->site_facebook;
            $system->save();
        }

        if ($request->has('site_instagram')) {
            $system = Application::where('name', 'site_instagram')->firstOrCreate();
            $system->name = 'site_instagram';
            $system->value = $request->site_instagram;
            $system->save();
        }

        if ($request->has('site_twitter')) {
            $system = Application::where('name', 'site_twitter')->firstOrCreate();
            $system->name = 'site_twitter';
            $system->value = $request->site_twitter;
            $system->save();
        }

        if ($request->has('site_youtube')) {
            $system = Application::where('name', 'site_youtube')->firstOrCreate();
            $system->name = 'site_youtube';
            $system->value = $request->site_youtube;
            $system->save();
        }

        if ($request->has('site_colors')) {
            $system = Application::where('name', 'site_colors')->firstOrCreate();
            $system->name = 'site_colors';
            $system->value = $request->site_colors;
            $system->save();
        }

        if ($request->has('site_timezone')) {
            $system = Application::where('name', 'site_timezone')->firstOrCreate();
            $system->name = 'site_timezone';
            $system->value = $request->site_timezone;
            $system->save();
            overWriteEnvFile('TIMEZONE', $system->value);
        }

        if ($request->has('site_dashboard')) {
            $system = Application::where('name', 'site_dashboard')->firstOrCreate();
            $system->name = 'site_dashboard';
            $system->value = $request->site_dashboard;
            $system->save();
            overWriteEnvFile('DASHBOARD_UI', $system->value);
        }

        if ($request->has('site_frontend_theme')) {
            $system = Application::where('name', 'site_frontend_theme')->firstOrCreate();
            $system->name = 'site_frontend_theme';
            $system->value = $request->site_frontend_theme;
            $system->save();
            overWriteEnvFile('FRONTEND_THEME', $system->value);
        }

        if ($request->has('site_linkedin')) {
            $system = Application::where('name', 'site_linkedin')->firstOrCreate();
            $system->name = 'site_linkedin';
            $system->value = $request->site_linkedin;
            $system->save();
        }

        if ($request->hasFile('site_logo')) {
            $system = Application::where('name', 'site_logo')->firstOrCreate();
            $system->name = 'site_logo';
            $system->value = fileUpload($request->site_logo, 'application');
            $system->save();
        }

        if ($request->hasFile('site_dark_logo')) {
            $system = Application::where('name', 'site_dark_logo')->firstOrCreate();
            $system->name = 'site_dark_logo';
            $system->value = fileUpload($request->site_dark_logo, 'application');
            $system->save();
        }

        if ($request->hasFile('site_favicon')) {
            $system = Application::where('name', 'site_favicon')->firstOrCreate();
            $system->name = 'site_favicon';
            $system->value = fileUpload($request->site_favicon, 'application');
            $system->save();
        }

        if ($request->hasFile('site_gateway_supports')) {
            $system = Application::where('name', 'site_gateway_supports')->firstOrCreate();
            $system->name = 'site_gateway_supports';
            $system->value = fileUpload($request->site_gateway_supports, 'application');
            $system->save();
        }

        if ($request->hasFile('site_trailer_thumbnail')) {
            $system = Application::where('name', 'site_trailer_thumbnail')->firstOrCreate();
            $system->name = 'site_trailer_thumbnail';
            $system->value = fileUpload($request->site_trailer_thumbnail, 'trailer');
            $system->save();
        }

        if ($request->has('site_trailer_url')) {
            $system = Application::where('name', 'site_trailer_url')->firstOrCreate();
            $system->name = 'site_trailer_url';
            $system->value = $request->site_trailer_url;
            $system->save();
        }

        if ($request->has('google_recaptcha_key')) {
            $system = Application::where('name', 'google_recaptcha_key')->firstOrCreate();
            $system->name = 'google_recaptcha_key';
            $system->value = $request->google_recaptcha_key;
            $system->save();
            overWriteEnvFile('GOOGLE_RECAPTCHA_KEY', $system->value);
        }

        if ($request->has('google_recaptcha_secret_key')) {
            $system = Application::where('name', 'google_recaptcha_secret_key')->firstOrCreate();
            $system->name = 'google_recaptcha_secret_key';
            $system->value = $request->google_recaptcha_secret_key;
            $system->save();
            overWriteEnvFile('GOOGLE_RECAPTCHA_SECRET', $system->value);
        }

        if ($request->has('google_recaptcha_mode')) {
            $system = Application::where('name', 'google_recaptcha_mode')->firstOrCreate();
            $system->name = 'google_recaptcha_mode';
            $system->value = $request->google_recaptcha_mode;
            $system->save();
            overWriteEnvFile('GOOGLE_RECAPTCHA', $system->value);
        }

        if ($request->has('kyc')) {
            $system = Application::where('name', 'kyc')->firstOrCreate();
            $system->name = 'kyc';
            $system->value = $request->kyc;
            $system->save();
            overWriteEnvFile('KYC', $system->value);
        }

        if ($request->has('open_ai_key')) {
            $system = Application::where('name', 'open_ai_key')->firstOrCreate();
            $system->name = 'open_ai_key';
            $system->value = $request->open_ai_key;
            $system->save();
            overWriteEnvFile('OPENAI_API_KEY', $system->value);
        }

        Artisan::call('optimize:clear');

        smilify('success', 'Saved');
        return back();
    }

    /**
     * custom_styles_scripts
     */
    public function custom_styles_scripts()
    {
        $frontend_css = CustomCssScript::where('type', 'frontend_css')->first()->code ?? null;
        $frontend_js = CustomCssScript::where('type', 'frontend_js')->first()->code ?? null;

        $backend_css = CustomCssScript::where('type', 'backend_css')->first()->code ?? null;
        $backend_js = CustomCssScript::where('type', 'backend_js')->first()->code ?? null;
        return view('backend.settings.custom_style_script.index', compact(
            'frontend_css',
            'frontend_js',
            'backend_css',
            'backend_js'
        )); 
    }

    /**
     * custom_styles_scripts_update
     */
    public function custom_styles_scripts_update(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        if ($request->has('frontend_css')) {
            $system = CustomCssScript::where('type', 'frontend_css')->firstOrCreate();
            $system->type = 'frontend_css';
            $system->code = $request->frontend_css;
            $system->save();
        }

        if ($request->has('frontend_js')) {
            $system = CustomCssScript::where('type', 'frontend_js')->firstOrCreate();
            $system->type = 'frontend_js';
            $system->code = $request->frontend_js;
            $system->save();
        }

        if ($request->has('backend_css')) {
            $system = CustomCssScript::where('type', 'backend_css')->firstOrCreate();
            $system->type = 'backend_css';
            $system->code = $request->backend_css;
            $system->save();
        }

        if ($request->has('backend_js')) {
            $system = CustomCssScript::where('type', 'backend_js')->firstOrCreate();
            $system->type = 'backend_js';
            $system->code = $request->backend_js;
            $system->save();
        }

        smilify('success', 'Saved');
        return back();
    }
    //END HERE
}
