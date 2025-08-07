<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoredIP extends Model
{
    protected $fillable = ['ip', 'status', 'latency', 'checked_at'];

}
