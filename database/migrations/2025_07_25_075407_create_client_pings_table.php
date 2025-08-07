<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPingsTable extends Migration
{
    public function up()
    {
        Schema::create('client_pings', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->integer('latency')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_pings');
    }
}