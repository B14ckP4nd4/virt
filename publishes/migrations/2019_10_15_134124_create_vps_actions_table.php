<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVpsActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virt_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vps_id');
            $table->string('action');
            $table->json('data')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('vps_id')->references('id')->on('virt_vps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virt_actions');
    }
}
