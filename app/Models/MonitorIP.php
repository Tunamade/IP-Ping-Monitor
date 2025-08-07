<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitorIp extends Model
{
    protected $table = 'monitor_ips';

    protected $fillable = [
        'ip', 'status', 'latency', 'notified_at'
    ];

}
