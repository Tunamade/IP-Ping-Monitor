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
        // ID'yi saklıyoruz, böylece queue çalışırken model yeniden yüklenebilir
        $this->ipEntryId = $ipEntry->id;
        $this->name = $ipEntry->name; // name direkt olarak saklanıyor
        $this->user = $user;
    }

    public function handle(): void
    {
        // Modeli yeniden yükle (queue sırasında eksik alan sorununu önlemek için)
        $this->ipEntry = MonitorIp::find($this->ipEntryId);
        if (!$this->ipEntry) {
            return; // kayıt bulunmazsa işlemi sonlandır
        }

        $ip = $this->ipEntry->ip;

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

        $this->ipEntry->latency = $latency;
        $this->ipEntry->status = $success ? 'success' : 'fail';

        // Başarısızsa ve bildirilmemişse mail gönder
        if (!$success && is_null($this->ipEntry->notified_at)) {
            Mail::to($this->user->email)->send(
                new FailedPingNotification($this->ipEntry->ip, $this->ipEntry->name ?? '-')
            );
            $this->ipEntry->notified_at = now();
        }

        // Başarılıysa notified_at sıfırla
        if ($success && $this->ipEntry->notified_at !== null) {
            $this->ipEntry->notified_at = null;
        }

        $this->ipEntry->save();

        $remaining = Cache::decrement('ping_jobs_remaining');
        if ($remaining <= 0) {
            Cache::forget('ping_jobs_running');
            Cache::forget('ping_jobs_remaining');
        }
    }
}
