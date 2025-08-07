<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ClientPing;

class ClientPingController extends Controller
{

    // app/Http/Controllers/ClientPingController.php
    public function destroy($id)
    {
        $ping = ClientPing::find($id);

        if (!$ping) {
            return response()->json(['message' => 'Kayıt bulunamadı'], 404);
        }

        $ping->delete();

        return response()->json(['message' => 'Silme başarılı']);
    }



    public function index()
    {
        $clientPings = ClientPing::orderByDesc('created_at')->get();

        return view('client_pings.index', compact('clientPings'));
    }

    public function json()
{
    $clientPings = \App\Models\ClientPing::orderBy('id', 'desc')->get();

    $formatted = $clientPings->map(function ($ping) {
        return [
            'id' => $ping->id,
            'ip' => $ping->ip,
            'latency' => $ping->latency,
            'status' => $ping->status,
            'created_at' => Carbon::parse($ping->created_at)->format('d.m.Y H:i:s'),
        ];
    });

    return response()->json($formatted);
}



}
