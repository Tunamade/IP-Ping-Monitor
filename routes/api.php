<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\ProfileController;

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

    // Profile
    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::put('/profile/notifications', [ProfileController::class, 'updateNotifications']);

    // Ping
    Route::post('/ping', [PingController::class, 'store']);
    Route::post('/ping', [MonitorController::class, 'store']);
    Route::post('/ping/queue', [MonitorController::class, 'queuePing']); // Queue endpoint eklendi
    Route::get('/pings', [PingController::class, 'index']);
    Route::get('/pings', [MonitorController::class, 'index']);
    Route::get('/pings/{id}', [PingController::class, 'show']);
    Route::get('/pings/{id}', [MonitorController::class, 'show']);

    // Monitor IP işlemleri
    Route::get('/monitor/ips', [MonitorController::class, 'getIPs']);
    Route::post('/monitor/ips', [MonitorController::class, 'store']);
    Route::delete('/monitor/ips/{ip}', [MonitorController::class, 'destroy']);

    // Başarısız IP'ler
    Route::get('/monitor/failed-ips', [MonitorController::class, 'getFailedIPs']);
});
