<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateVpsLogsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('virt_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('server_id');
                $table->unsignedBigInteger('vps_id');
                $table->unsignedBigInteger('task_id')->nullable();
                $table->string('task');
                $table->string('status');
                $table->text('description');
                $table->timestamp('date');
                $table->timestamps();


                $table->foreign('server_id')->references('id')->on('virt_server')->onDelete('cascade');
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
            Schema::dropIfExists('v_p_s_l_o_g');
        }
    }
