<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;

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

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Dashboard routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/overview', [DashboardController::class, 'overview']);
        Route::get('/pipeline', [DashboardController::class, 'pipeline']);
        Route::get('/revenue', [DashboardController::class, 'revenue']);
        Route::get('/team-performance', [DashboardController::class, 'teamPerformance']);
        Route::get('/upcoming-tasks', [DashboardController::class, 'upcomingTasks']);
        Route::get('/notifications', [DashboardController::class, 'notifications']);
        Route::get('/quick-actions', [DashboardController::class, 'quickActions']);
        Route::get('/system-health', [DashboardController::class, 'systemHealth']);
    });

    // Contact routes
    Route::apiResource('contacts', ContactController::class);
    Route::get('contacts/stats/overview', [ContactController::class, 'stats']);
    Route::post('contacts/search', [ContactController::class, 'search']);

    // Lead routes
    Route::apiResource('leads', LeadController::class);
    Route::post('leads/{id}/convert', [LeadController::class, 'convert']);
    Route::get('leads/analytics/pipeline', [LeadController::class, 'pipeline']);
    Route::get('leads/analytics/sources', [LeadController::class, 'sources']);

    // Activity routes
    Route::apiResource('activities', ActivityController::class);
    Route::post('activities/{id}/complete', [ActivityController::class, 'complete']);
    Route::get('activities/stats/overview', [ActivityController::class, 'stats']);
    Route::get('activities/upcoming', [ActivityController::class, 'upcoming']);
    Route::get('activities/overdue', [ActivityController::class, 'overdue']);

    // Email routes
    Route::get('emails', [EmailController::class, 'index']);
    Route::get('emails/{id}', [EmailController::class, 'show']);
    Route::post('emails/send', [EmailController::class, 'send']);
    Route::post('emails/{id}/reply', [EmailController::class, 'reply']);
    Route::post('emails/{id}/forward', [EmailController::class, 'forward']);
    Route::get('emails/templates', [EmailController::class, 'templates']);
    Route::get('emails/stats/overview', [EmailController::class, 'stats']);
    Route::get('emails/thread/{threadId}', [EmailController::class, 'thread']);
    Route::patch('emails/{id}/read', [EmailController::class, 'markAsRead']);

    // User management routes
    Route::apiResource('users', UserController::class);
    Route::post('users/{id}/avatar', [UserController::class, 'uploadAvatar']);
    Route::patch('users/{id}/permissions', [UserController::class, 'updatePermissions']);
    Route::patch('users/{id}/preferences', [UserController::class, 'updatePreferences']);
    Route::get('users/roles-permissions', [UserController::class, 'rolesAndPermissions']);
    Route::get('users/{id}/activity-log', [UserController::class, 'activityLog']);
});
