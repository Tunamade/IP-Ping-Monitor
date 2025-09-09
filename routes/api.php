<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\MonitorController;

// ----------------------------
// Public Routes
// ----------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ----------------------------
// Protected Routes (auth:sanctum)
// ----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Ping
    Route::post('/ping', [PingController::class, 'store']);
    Route::get('/pings', [PingController::class, 'index']);
    Route::get('/pings/{id}', [PingController::class, 'show']);

    // Monitor IP işlemleri
    Route::get('/monitor/ips', [MonitorController::class, 'getIPs']);
    Route::post('/monitor/ips', [MonitorController::class, 'store']);
    Route::delete('/monitor/ips/{ip}', [MonitorController::class, 'destroy']);
});
