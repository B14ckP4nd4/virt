<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virt_oses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('os_id');
            $table->unsignedBigInteger('server_id');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('filename')->default(0);
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vps_oses');
    }
}
