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
        Schema::create('virt_vps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('vps_id')->nullable();
            $table->unsignedBigInteger('ip_id');
            $table->string('vps_name')->nullable();
            $table->string('hostname')->nullable();
            $table->unsignedBigInteger('os_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('root_pass')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $table->foreign('ip_id')->references('id')->on('vps_ips')->onDelete('cascade');
            $table->foreign('os_id')->references('id')->on('vps_oses')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('vps_plans')->onDelete('cascade');
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
