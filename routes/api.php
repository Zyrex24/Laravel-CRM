<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// CRM Resources
Route::apiResource('contacts', ContactController::class);
Route::apiResource('leads', LeadController::class);
Route::apiResource('activities', ActivityController::class);
Route::apiResource('organizations', OrganizationController::class);

// Search endpoint
Route::get('/search', [App\Http\Controllers\Api\SearchController::class, 'search']);

// Notifications endpoints
Route::get('/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index']);
Route::patch('/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
Route::patch('/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
