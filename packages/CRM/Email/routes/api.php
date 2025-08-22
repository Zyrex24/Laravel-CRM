<?php

use Illuminate\Support\Facades\Route;
use CRM\Email\Http\Controllers\Api\EmailApiController;
use CRM\Email\Http\Controllers\Api\EmailTemplateApiController;
use CRM\Email\Http\Controllers\Api\EmailTrackingApiController;

/*
|--------------------------------------------------------------------------
| CRM Email API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for the CRM Email package. These routes are
| loaded by the EmailServiceProvider within a group which contains
| the "api" middleware group.
|
*/

Route::prefix('api/crm/email')->name('api.crm.email.')->middleware(['api', 'auth:sanctum'])->group(function () {
    
    // Email API routes
    Route::apiResource('emails', EmailApiController::class);
    Route::post('emails/{email}/send', [EmailApiController::class, 'send'])->name('emails.send');
    Route::post('emails/{email}/schedule', [EmailApiController::class, 'schedule'])->name('emails.schedule');
    Route::get('emails/{email}/thread', [EmailApiController::class, 'thread'])->name('emails.thread');
    Route::get('emails/search', [EmailApiController::class, 'search'])->name('emails.search');
    Route::get('emails/statistics', [EmailApiController::class, 'statistics'])->name('emails.statistics');
    
    // Email template API routes
    Route::apiResource('templates', EmailTemplateApiController::class);
    Route::post('templates/{template}/duplicate', [EmailTemplateApiController::class, 'duplicate'])->name('templates.duplicate');
    Route::post('templates/{template}/render', [EmailTemplateApiController::class, 'render'])->name('templates.render');
    Route::get('templates/search', [EmailTemplateApiController::class, 'search'])->name('templates.search');
    
    // Email tracking API routes
    Route::get('tracking/{email}', [EmailTrackingApiController::class, 'show'])->name('tracking.show');
    Route::get('tracking/{email}/events', [EmailTrackingApiController::class, 'events'])->name('tracking.events');
    
});
