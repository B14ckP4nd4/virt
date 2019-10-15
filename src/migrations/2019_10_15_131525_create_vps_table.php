<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVPStable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('vps_id')->nullable();
            $table->unsignedBigInteger('ip_id');
            $table->string('vps_name')->nullable();
            $table->string('hostname')->nullable();
            $table->unsignedInteger('os_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->text('root_pass')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers');
            $table->foreign('ip_id')->references('id')->on('vps_ips');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vps');
    }
}
