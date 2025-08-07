<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitorIp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;  // Cache eklendi
use App\Mail\FailedPingNotification;
use App\Jobs\PingIpJob;

class MonitorController extends Controller
{
    public function index()
    {
        return view('monitor');
    }

    // Ping işlemlerini job olarak kuyruğa atar
    public function queuePing()
    {
        $user = Auth::user();

        if (!$user || !$user->email) {
            return response()->json(['message' => 'Oturumda kullanıcı bulunamadı veya e-posta adresi yok.'], 403);
        }

        $ipAddresses = MonitorIp::all();

        $totalJobs = $ipAddresses->count();

        if ($totalJobs === 0) {
            return response()->json(['message' => 'İzlenecek IP adresi yok.']);
        }

        // Flag ve sayaç cache'de tutuluyor
        Cache::put('ping_jobs_running', true, now()->addMinutes(5));
        Cache::put('ping_jobs_remaining', $totalJobs, now()->addMinutes(5));

        foreach ($ipAddresses as $ipAddress) {
            PingIpJob::dispatch($ipAddress, $user);
        }

        return response()->json(['message' => "$totalJobs adet ping işlemi kuyruğa alındı."]);
    }

    public function getIPs() // Tüm ipleri çeker
    {
        $ips = MonitorIp::all();
        $result = $ips->map(function($ip) {
            return [
                'id' => $ip->id,
                'ip' => $ip->ip,
                'status' => $ip->status ?? 'N/A',
                'latency' => $ip->latency,
                'created_at' => $ip->created_at,
                'updated_at' => $ip->updated_at
            ];
        });
        return response()->json($result);
    }

    public function getFailedIPs()  // başarısız olan ipleri çeker
    {
        $failed = MonitorIp::where('status', 'fail')->get();

        return response()->json($failed->map(function ($ip) {
            return [
                'ip' => $ip->ip,
                'latency' => $ip->latency,
                'updated_at' => $ip->updated_at ? $ip->updated_at->format('d.m.Y H:i:s') : null,
            ];
        }));
    }

    public function store(Request $request)  // ekle butonu
    {
        $request->validate([
            'ip' => [
                'required',
                'unique:monitor_ips,ip',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_IP) && !filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                        $fail('Geçerli bir IP adresi veya domain adı girin.');
                    }
                }
            ],
        ]);

        $ip = MonitorIp::create(['ip' => $request->ip]);

        return response()->json($ip, 201);
    }

    public function destroy($ip)  // sil butonu
    {
        $deleted = MonitorIp::where('ip', $ip)->delete();

        if ($deleted) {
            return response()->json(['message' => 'IP silindi.']);
        }

        return response()->json(['message' => 'IP bulunamadı.'], 404);
    }

    public function json() // en son eklenen en üstte olarak json olarak döndürür
    {
        $monitorPings = MonitorIp::orderBy('id', 'desc')->get();

        return response()->json($monitorPings->map(function ($ping) {
            return [
                'id' => $ping->id,
                'ip' => $ping->ip,
                'latency' => $ping->latency,
                'status' => $ping->status ?? 'N/A',
                'created_at' => Carbon::parse($ping->created_at)->format('d.m.Y H:i:s'),
            ];
        })->values());
    }

    // NOT: Eski ping() fonksiyonunu artık kullanmıyoruz, iş kuyruğunda çalışacak!

    public function pingStatus()
    {
        $running = \Cache::get('ping_jobs_running', false);
        return response()->json(['running' => $running]);
    }

    public function sendPendingNotifications()
    {
        $user = Auth::user();
        if (!$user || !$user->email) {
            return response()->json(['message' => 'Oturumda kullanıcı bulunamadı veya e-posta adresi yok.'], 403);
        }
        // Ping başarısız olup henüz bildirilmemiş IP'leri alır
        $failedIPs = MonitorIp::where('status', 'fail')
            ->whereNull('notified_at')
            ->get();

        foreach ($failedIPs as $ipEntry) {
            Mail::to($user->email)->send(new FailedPingNotification($ipEntry->ip));
            $ipEntry->notified_at = now();
            $ipEntry->save();
        }

        return response()->json(['message' => count($failedIPs).' adet bildirim gönderildi.']);
    }
}
