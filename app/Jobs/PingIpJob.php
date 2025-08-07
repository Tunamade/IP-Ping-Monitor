<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache; // Cache eklendi
use App\Models\MonitorIp;
use App\Models\User;
use App\Mail\FailedPingNotification;

class PingIpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ipEntry;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param MonitorIp $ipEntry
     * @param User $user
     */
    public function __construct(MonitorIp $ipEntry, User $user)
    {
        $this->ipEntry = $ipEntry;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ip = $this->ipEntry->ip;

        // İşletim sistemine göre ping komutunu ayarla
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

        // Eğer ping başarısızsa ve henüz bildirim yapılmamışsa mail gönder
        if (!$success && is_null($this->ipEntry->notified_at)) {
            Mail::to($this->user->email)->send(new FailedPingNotification($ip));
            $this->ipEntry->notified_at = now();
        }

        // Eğer ping başarılıysa ve önceden bildirim yapılmışsa, notified_at sıfırla
        if ($success && $this->ipEntry->notified_at !== null) {
            $this->ipEntry->notified_at = null;
        }

        $this->ipEntry->save();

        // Kuyruktaki kalan job sayısını azalt
        $remaining = Cache::decrement('ping_jobs_remaining');

        // Eğer kalan job kalmadıysa flag'i kaldır
        if ($remaining <= 0) {
            Cache::forget('ping_jobs_running');
            Cache::forget('ping_jobs_remaining');
        }
    }
}
