<?php

use App\Http\Controllers\DepartmentController;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified'], 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/delete/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('departments.update');
});