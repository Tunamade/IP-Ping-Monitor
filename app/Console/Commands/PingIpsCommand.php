<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonitorIp;
use Illuminate\Support\Facades\Auth;
use App\Jobs\PingIpJob;
use Illuminate\Support\Facades\Cache;

class PingIpsCommand extends Command
{
    // Komut adı (terminalden çalıştırmak için)
    protected $signature = 'ping:ips';
    protected $description = 'Tüm IP adreslerine ping işlemi kuyruğa alınır';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Sistem komutu için sistem kullanıcısı oluştur veya belirle
        // Eğer oturum açmış kullanıcı yoksa, örnek olarak x id'li kullanıcı alabiliriz
        $user = \App\Models\User::find(3);

        if (!$user || !$user->email) {
            $this->error('Sistem kullanıcısı bulunamadı veya e-posta adresi yok.');
            return 1;
        }

        $ipAddresses = MonitorIp::all();
        $totalJobs = $ipAddresses->count();

        if ($totalJobs === 0) {
            $this->info('İzlenecek IP adresi yok.');
            return 0;
        }

        Cache::put('ping_jobs_running', true, now()->addMinutes(5));
        Cache::put('ping_jobs_remaining', $totalJobs, now()->addMinutes(5));

        foreach ($ipAddresses as $ipAddress) {
            PingIpJob::dispatch($ipAddress, $user);
        }

        $this->info("$totalJobs adet ping işlemi kuyruğa alındı.");

        return 0;
    }
}
