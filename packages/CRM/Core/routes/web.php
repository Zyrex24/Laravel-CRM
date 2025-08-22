<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register core web routes for the CRM application.
| These routes are loaded by the CoreServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::prefix('crm')->name('crm.')->group(function () {
    Route::get('/', function () {
        return view('core::dashboard');
    })->name('dashboard');

    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'version' => config('crm.core.version'),
        ]);
    })->name('health');
});
