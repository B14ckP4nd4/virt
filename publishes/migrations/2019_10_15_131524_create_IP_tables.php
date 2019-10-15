<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIPtables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vps_ips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ip_id');
            $table->unsignedBigInteger('server_id');
            $table->ipAddress('ip');
            $table->boolean('locked')->default(false);
            $table->timestamp('last_use')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vps_ips');
    }
}
