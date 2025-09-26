<?php

use App\Http\Controllers\InstallerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'install.check', 'prefix' => 'install'], function () {
    Route::get('/welcome-to-teleman', [InstallerController::class, 'welcome'])->name('install');
    Route::get('server/permission', [InstallerController::class, 'permission'])->name('permission');
    Route::get('database/create', [InstallerController::class, 'create'])->name('create');
    Route::get('setup/database', [InstallerController::class, 'dbStore'])->name('db.setup');
    Route::get('database/check', [InstallerController::class, 'checkDbConnection'])->name('check.db');
    Route::get('setup/import/sql', [InstallerController::class, 'importSql'])->name('sql.setup');

    Route::get('import/fresh/data', [InstallerController::class, 'importFreshSql'])->name('import.fresh.data'); // upload fresh data

    Route::get('setup/admin', [InstallerController::class, 'adminCreate'])->name('admin.create');
    Route::post('setup/admin/store', [InstallerController::class, 'adminStore'])->name('admin.store');
});
