<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'can:admin'], 'prefix' => 'dashboard'], function () {
    Route::get('language', [LanguageController::class, 'langIndex'])->name('language.index');
    Route::post('language/store', [LanguageController::class, 'langStore'])->name('language.store');
    Route::get('language/destroy/{id}/{code?}', [LanguageController::class, 'langDestroy'])->name('language.destroy');
    Route::get('language/translate/{id}/{code?}', [LanguageController::class, 'translate_create'])->name('language.translate');
    Route::post('language/translate/store', [LanguageController::class, 'translate_store'])->name('language.translate.store');
    Route::get('language/default/{id}/{code?}', [LanguageController::class, 'defaultLanguage'])->name('language.default');
});

Route::post('language/change', [LanguageController::class, 'languagesChange'])->name('language.change');