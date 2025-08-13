<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToMonitorIpsTable extends Migration
{
    public function up()
    {
        Schema::table('monitor_ips', function (Blueprint $table) {
            $table->string('name')->nullable()->after('ip');
        });
    }

    public function down()
    {
        Schema::table('monitor_ips', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}
