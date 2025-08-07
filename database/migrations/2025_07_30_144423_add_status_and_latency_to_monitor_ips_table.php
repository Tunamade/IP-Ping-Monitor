<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndLatencyToMonitorIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitor_ips', function (Blueprint $table) {
            $table->string('status')->nullable()->after('ip');  // 'status' sütununu ekliyoruz
            $table->integer('latency')->nullable()->after('status');  // 'latency' sütununu ekliyoruz
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitor_ips', function (Blueprint $table) {
            $table->dropColumn(['status', 'latency']);
        });
    }
}
