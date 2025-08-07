<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPing extends Model
{
    protected $table = 'client_pings';

    protected $fillable = [
        'ip',
        'latency',
        'status' 
    ];
}
