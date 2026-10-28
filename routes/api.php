<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HeartbeatController;
use App\Http\Controllers\Api\V1\IncidentController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\MonitorController;
use App\Http\Controllers\Api\V1\SlaController;
use App\Http\Controllers\Api\V1\StatusPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes - Website Uptime Monitor SaaS Platform
|--------------------------------------------------------------------------
| Author: Imran Ahmed <me@imrandev.bd>
| Website: https://imrandev.bd
*/

Route::prefix('v1')->group(function () {
    // Public heartbeat pulse ingestion
    Route::match(['get', 'post', 'head'], '/heartbeat/{token}', [HeartbeatController::class, 'ping']);

    // Public token generation
    Route::post('/auth/token', [AuthController::class, 'token']);

    // Public monitoring probe locations
    Route::get('/locations', [LocationController::class, 'index']);

    // Authenticated API endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);

        // Monitors
        Route::get('/monitors', [MonitorController::class, 'index']);
        Route::post('/monitors', [MonitorController::class, 'store']);
        Route::get('/monitors/{id}', [MonitorController::class, 'show']);
        Route::put('/monitors/{id}', [MonitorController::class, 'update']);
        Route::delete('/monitors/{id}', [MonitorController::class, 'destroy']);
        Route::post('/monitors/{id}/check-now', [MonitorController::class, 'checkNow']);
        Route::get('/monitors/{id}/checks', [MonitorController::class, 'checks']);

        // Incidents
        Route::get('/incidents', [IncidentController::class, 'index']);
        Route::get('/incidents/{id}', [IncidentController::class, 'show']);
        Route::post('/incidents/{id}/acknowledge', [IncidentController::class, 'acknowledge']);
        Route::post('/incidents/{id}/resolve', [IncidentController::class, 'resolve']);

        // Status Pages
        Route::get('/status-pages', [StatusPageController::class, 'index']);
        Route::get('/status-pages/{id}', [StatusPageController::class, 'show']);

        // SLA
        Route::get('/sla', [SlaController::class, 'index']);
    });
});
