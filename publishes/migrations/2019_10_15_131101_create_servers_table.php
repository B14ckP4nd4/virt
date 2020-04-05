<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virt_server', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('domain');
            $table->ipAddress('ip')->unique();
            $table->unsignedBigInteger('port')->default(4085);
            $table->unsignedBigInteger('admin_user_id');
            $table->unsignedBigInteger('main_plan_id');
            $table->string('key');
            $table->string('pass');
            $table->string('licence_key')->nullable();
            $table->timestamp('licence_expire')->nullable();
            $table->string('dataCenter')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('payment');
            $table->double('price',8,3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
