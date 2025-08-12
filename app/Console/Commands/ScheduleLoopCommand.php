<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleLoopCommand extends Command
{
    protected $signature = 'schedule:loop';
    protected $description = 'Cron olmadan schedule komutlarını sürekli çalıştırır';

    public function handle()
    {
        $this->info('Schedule loop başlatıldı... (CTRL+C ile durdur)');

        while (true) {
            $this->call('schedule:run');
            sleep(60); // her 60 saniyede bir çalıştır
        }
    }
}
