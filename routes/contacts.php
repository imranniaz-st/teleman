<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified']], function () {
    /**
     * contacts
     */
    Route::get('/contacts', [ContactController::class, 'index'])->name('dashboard.contact.index');
    Route::get('/get/contacts', [ContactController::class, 'getContactsAjax'])->name('dashboard.contact.ajax'); // ajax api
    Route::post('/contact/store', [ContactController::class, 'store'])->name('dashboard.contact.store');
    Route::get('/contact/{id}/edit/{slug?}', [ContactController::class, 'show'])->name('dashboard.contact.show');
    Route::post('/contact/{id}/update/{slug?}', [ContactController::class, 'update'])->name('dashboard.contact.update');
    Route::get('/contact/{id}/delete/{slug?}', [ContactController::class, 'destroy'])->name('dashboard.contact.delete');

    Route::get('/find/contact', [ContactController::class, 'find_contact_by_number'])->name('dashboard.contact.find'); // api
    
    Route::get('/contact/search', [ContactController::class, 'searchContacts'])->name('dashboard.contact.search'); // api

    /**
     * group
     */
    Route::get('/groups', [ContactController::class, 'group_index'])->name('dashboard.contact.group.index');
    Route::post('/group/store', [ContactController::class, 'group_store'])->name('dashboard.contact.group.store');
    Route::get('/group/{id}/edit/{slug?}', [ContactController::class, 'group_show'])->name('dashboard.contact.group.show');
    Route::post('/group/{id}/update/{slug?}', [ContactController::class, 'group_update'])->name('dashboard.contact.group.update');
    Route::get('/group/{id}/delete/{slug?}', [ContactController::class, 'group_destroy'])->name('dashboard.contact.group.destroy');

    Route::get('/group/assign/{group_id}/{group_slug}', [ContactController::class, 'group_assign'])->name('dashboard.contact.group.assign');
    Route::post('/group/assign/{group_id}/{group_slug}/store', [ContactController::class, 'group_assign_store'])->name('dashboard.contact.group.assign.store');
    Route::get('/group/assign/all/contacts/{group_id}/{group_slug}/store', [ContactController::class, 'assign_all_contacts'])->name('dashboard.assign.all.contacts');
    Route::get('/group/assign/remove/contacts/{group_id}/{group_slug}/store', [ContactController::class, 'assign_remove_contacts'])->name('dashboard.assign.remove.contacts');

    /**
     * CSV Export Import
     */
    Route::get('/contact/export', [ContactController::class, 'export'])->name('dashboard.contact.export');
    Route::post('/contact/import', [ContactController::class, 'import'])->name('dashboard.contact.import');
    Route::get('/check/import/status', [ContactController::class, 'check_import_status'])->name('dashboard.contact.import.status');
});
