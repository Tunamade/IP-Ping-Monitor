<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\MonitorIp;
use App\Models\User;
use App\Mail\FailedPingNotification;

class PingIpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ipEntryId;
    protected $user;

    public function __construct(MonitorIp $ipEntry, User $user)
    {
        $this->ipEntryId = $ipEntry->id;
        $this->user = $user;
    }

    public function handle(): void
    {
        $ipEntry = MonitorIp::find($this->ipEntryId);
        if (!$ipEntry) return;

        $ip = $ipEntry->ip;

        if (stripos(PHP_OS, 'WIN') !== false) {
            $pingOutput = shell_exec("ping -n 1 -w 1000 $ip");
        } else {
            $pingOutput = shell_exec("ping -c 1 -W 1 $ip");
        }

        $success = false;
        if (stripos(PHP_OS, 'WIN') !== false) {
            $success = strpos($pingOutput, 'TTL=') !== false;
        } else {
            $success = stripos($pingOutput, 'ttl=') !== false || stripos($pingOutput, 'bytes from') !== false;
        }

        $latency = null;
        if ($success && preg_match('/time[=<]?(\d+\.?\d*) ?ms/i', $pingOutput, $matches)) {
            $latency = (float) $matches[1];
        }

        $ipEntry->latency = $latency;
        $ipEntry->status = $success ? 'success' : 'fail';

        // 🔹 Başarısız ve daha önce bildirilmemişse mail gönder (kullanıcı tercihi kontrolü)
        if (!$success && is_null($ipEntry->notified_at)) {
            if ($this->user->email_notifications) {
                Mail::to($this->user->email)->send(
                    new FailedPingNotification($ipEntry->ip, $ipEntry->name ?? '-')
                );
            }
            $ipEntry->notified_at = now();
        }

        // Başarılı ise notified_at sıfırlanır
        if ($success && $ipEntry->notified_at !== null) {
            $ipEntry->notified_at = null;
        }

        $ipEntry->save();

        // Kuyruk sayacını güncelle
        $remaining = Cache::decrement('ping_jobs_remaining');
        if ($remaining <= 0) {
            Cache::forget('ping_jobs_running');
            Cache::forget('ping_jobs_remaining');
        }
    }
}
