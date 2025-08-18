<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PingController;
use App\Http\Controllers\ClientPingController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\AuthController;
use App\Models\ClientPing;
use Illuminate\Support\Facades\Mail;
use App\Mail\FailedPingNotification;
use App\Jobs\TestQueueJob;
use App\Http\Controllers\ProfileController;

// Auth middleware ile korunan grup
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profil Yönetimi
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile'); // profil sayfası
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');


    /*
    |--------------------------------------------------------------------------
    | Monitor ve IP Yönetimi
    |--------------------------------------------------------------------------
    */
    Route::get('/monitor/table', function () {
        return view('monitor_table');
    });

    Route::get('/ping/status', [MonitorController::class, 'pingStatus']);
    Route::get('/monitor', [MonitorController::class, 'index']);
    Route::get('/monitor/ips', [MonitorController::class, 'getIPs']);
    Route::post('/monitor/ips', [MonitorController::class, 'store']);
    Route::delete('/monitor/ips/{ip}', [MonitorController::class, 'destroy']);
    Route::get('/monitor/failed-ips', [MonitorController::class, 'getFailedIPs']);
    Route::post('/ping/queue', [MonitorController::class, 'queuePing']);
    Route::post('/monitor/loop', [MonitorController::class, 'monitorLoop']);
    Route::get('/monitor/send-failed-notifications', [MonitorController::class, 'sendPendingNotifications']);

    /*
    |--------------------------------------------------------------------------
    | Ana Sayfa ve Ping İşlemleri
    |--------------------------------------------------------------------------
    */
    Route::get('/', [PingController::class, 'index'])->name('home');
    Route::get('/pings', [PingController::class, 'index']);
    Route::get('/pings/json', [PingController::class, 'json']);
    Route::post('/ping-multiple', [PingController::class, 'pingMultipleIPs']);

    /*
    |--------------------------------------------------------------------------
    | Client Ping İşlemleri
    |--------------------------------------------------------------------------
    */
    Route::get('/client-pings', [ClientPingController::class, 'index']);
    Route::get('/client-pings/json', [ClientPingController::class, 'json']);
    Route::delete('/client-pings/{id}', [ClientPingController::class, 'destroy']);

    Route::post('/client-ping', function (Request $request) {
        $validated = $request->validate([
            'ip' => 'required|string',
            'latency' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        $clientPing = ClientPing::where('ip', $validated['ip'])->first();

        if ($clientPing) {
            $clientPing->update([
                'latency' => $validated['latency'],
                'status' => $validated['status'],
            ]);
        } else {
            ClientPing::create($validated);
        }

        return response()->json(['status' => 'ok']);
    });

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Test Sayfası
    |--------------------------------------------------------------------------
    */
    Route::get('/bootstrap-test', function () {
        return view('bootstrap-test');
    });
});

/*
|--------------------------------------------------------------------------
| Genel Route’lar (Auth Harici)
|--------------------------------------------------------------------------
*/

// Test queue job (örnek)
Route::get('/test-queue', function () {
    TestQueueJob::dispatch();
    return 'Job kuyruğa eklendi!';
});

/*
|--------------------------------------------------------------------------
| Auth Giriş ve Kayıt
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
