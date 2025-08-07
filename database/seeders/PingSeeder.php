<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ping;

class PingSeeder extends Seeder
{
    public function run()
    {
        $ips = [      //örnek ip adresleri oluşturuyor çok da gerek yok
            '1.1.1.1',
            '8.8.4.4',
            '4.2.2.2',
            '192.168.1.1',
            '208.67.222.222',
        ];

        foreach ($ips as $ip) {
            Ping::create(['ip_address' => $ip]);
        }
    }
}
