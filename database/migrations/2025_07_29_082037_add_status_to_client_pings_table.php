<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToClientPingsTable extends Migration
{
    public function up()
    {
        Schema::table('client_pings', function (Blueprint $table) {
            $table->string('status')->nullable()->after('latency');
        });
    }

    public function down()
    {
        Schema::table('client_pings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
