<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ping;
use App\Jobs\PingServerJob;

class PingController extends Controller
{
    // Ping geçmişi
    public function index(Request $request)
    {
        $pings = Ping::with('user')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($pings);
    }

    // Tek ping detayı
    public function show($id, Request $request)
    {
        $ping = Ping::with('user')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($ping);
    }

    // Ping isteği kaydetme ve queue job'a gönderme
    public function store(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip'
        ]);

        $ping = Ping::create([
            'user_id' => $request->user()->id,
            'ip' => $request->ip,
            'status' => 'queued'
        ]);

        // PingServerJob kuyruğa ekleniyor
        PingServerJob::dispatch($ping);

        return response()->json([
            'message' => 'Ping request queued',
            'ping' => $ping
        ], 201);
    }
}
