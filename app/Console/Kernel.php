<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //php artisan schedule:work         php artisan schedule:run   bu iki kod ile ping:ips i çalıştırıyoruz
        \App\Console\Commands\PingIpsCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Her dakika çalışan görev
        $schedule->command('ping:ips')->everyMinute();

        // 5 dakikada bir çalışan görev
        // $schedule->command('ping:ips')->everyFiveMinutes();

        // Saatte bir çalışan görev
        // $schedule->command('ping:ips')->hourly();

        // Günlük olarak (her gün saat 00:00'da) çalışan görev
        // $schedule->command('ping:ips')->daily();
    }
}
