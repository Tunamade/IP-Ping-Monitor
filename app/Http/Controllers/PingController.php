<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientPing;
use Carbon\Carbon;

class PingController extends Controller
{
    public function index()
    {
        return view('anasayfa');
    }

    // IP'leri listele (JSON olarak döner)
    public function json()
    {
        $ips = ClientPing::all()->map(function ($ip) {
            return [
                'ip' => $ip->ip,
                'status' => $ip->status,
                'latency' => $ip->latency,
            ];
        });

        return response()->json($ips);
    }

    // Tek seferde sıradaki IP'yi ping atar
    public function ping()
    {
        $ip = ClientPing::inRandomOrder()->first();

        if (!$ip) {
            return response()->json([], 204);
        }

        $start = microtime(true);
        exec("ping -n 1 {$ip->ip}", $output, $resultCode); // Windows için
        $latency = (microtime(true) - $start) * 1000;

        $status = $resultCode === 0 ? 'success' : 'failed';
        $latencyVal = $status === 'success' ? round($latency, 2) : null;

        $ip->update([
            'status' => $status,
            'latency' => $latencyVal
        ]);

        return $this->json(); // Tüm IP’leri döndür
    }

    // Birden fazla IP'yi aynı anda ping at (isteğe bağlı olarak kullanılabilir)
    public function pingMultipleIPs(Request $request)
    {
        $ips = $request->input('ips'); // Dizi bekleniyor
        $results = [];

        foreach ($ips as $ip) {
            $start = microtime(true);
            exec("ping -n 1 $ip", $output, $resultCode);
            $latency = (microtime(true) - $start) * 1000;

            $status = $resultCode === 0 ? 'success' : 'failed';
            $latencyVal = $status === 'success' ? round($latency) : null;

            $clientPing = ClientPing::where('ip', $ip)->first();

            if ($clientPing) {
                $clientPing->update([
                    'latency' => $latencyVal,
                    'status' => $status
                ]);
            } else {
                ClientPing::create([
                    'ip' => $ip,
                    'latency' => $latencyVal,
                    'status' => $status
                ]);
            }

            $results[] = [
                'ip' => $ip,
                'status' => $status,
                'latency' => $latencyVal,
            ];
        }

        return response()->json($results);
    }
}
