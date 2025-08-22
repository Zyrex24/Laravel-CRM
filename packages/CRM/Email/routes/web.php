<?php

use Illuminate\Support\Facades\Route;
use CRM\Email\Http\Controllers\EmailController;
use CRM\Email\Http\Controllers\EmailTemplateController;
use CRM\Email\Http\Controllers\EmailTrackingController;

/*
|--------------------------------------------------------------------------
| CRM Email Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the CRM Email package. These routes are
| loaded by the EmailServiceProvider within a group which contains
| the "web" middleware group.
|
*/

Route::prefix('crm/email')->name('crm.email.')->middleware(['web', 'auth'])->group(function () {
    
    // Email management routes
    Route::get('/', [EmailController::class, 'index'])->name('index');
    Route::get('/create', [EmailController::class, 'create'])->name('create');
    Route::post('/', [EmailController::class, 'store'])->name('store');
    Route::get('/{email}', [EmailController::class, 'show'])->name('show');
    Route::get('/{email}/edit', [EmailController::class, 'edit'])->name('edit');
    Route::put('/{email}', [EmailController::class, 'update'])->name('update');
    Route::delete('/{email}', [EmailController::class, 'destroy'])->name('destroy');
    
    // Email actions
    Route::post('/{email}/send', [EmailController::class, 'send'])->name('send');
    Route::post('/{email}/schedule', [EmailController::class, 'schedule'])->name('schedule');
    Route::post('/{email}/duplicate', [EmailController::class, 'duplicate'])->name('duplicate');
    Route::get('/{email}/preview', [EmailController::class, 'preview'])->name('preview');
    
    // Email templates
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
        Route::get('/create', [EmailTemplateController::class, 'create'])->name('create');
        Route::post('/', [EmailTemplateController::class, 'store'])->name('store');
        Route::get('/{template}', [EmailTemplateController::class, 'show'])->name('show');
        Route::get('/{template}/edit', [EmailTemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [EmailTemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [EmailTemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/duplicate', [EmailTemplateController::class, 'duplicate'])->name('duplicate');
        Route::get('/{template}/preview', [EmailTemplateController::class, 'preview'])->name('preview');
    });
    
});

// Email tracking routes (no auth required)
Route::prefix('crm/email/track')->name('crm.email.track.')->group(function () {
    Route::get('/open/{email}', [EmailTrackingController::class, 'trackOpen'])->name('open');
    Route::get('/click/{email}', [EmailTrackingController::class, 'trackClick'])->name('click');
});
