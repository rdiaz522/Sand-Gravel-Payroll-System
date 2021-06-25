<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedBigInteger('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->string('daily_rate')->nullable();
            $table->string('log_date')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('daily_logs');
        Schema::enableForeignKeyConstraints();
    }
}
