<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoredIpsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('monitor_ips')) {
            Schema::create('monitor_ips', function (Blueprint $table) {
                $table->id();
                $table->string('ip')->unique();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('monitor_ips');
    }
}
