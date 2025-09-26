<?php

use Carbon\Carbon;
use App\Models\Message;

if (env('DEVELOPMENT_MODE') == 'YES') { // If development mode is enabled
    Auth::routes(); // Auth > Routes
} else {
    Auth::routes(['register' => false]); // Auth > Routes
}

Route::get('/test', function(){
if (session()->has('locale')) {
    app()->setLocale(session('locale'));
}else{
    session(['locale' => config('app.locale')]);
}

return session('locale');
}); // Home > Index
