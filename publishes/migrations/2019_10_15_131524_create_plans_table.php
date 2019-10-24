<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vps_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('server_id');
            $table->string('name');
            $table->integer('space')->nullable();
            $table->integer('ram')->nullable();
            $table->integer('swap')->nullable();
            $table->integer('cpu')->nullable();
            $table->integer('cores')->nullable();
            $table->unsignedBigInteger('os_id')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $table->foreign('os_id')->references('id')->on('vps_oses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vps_plans');
    }
}
