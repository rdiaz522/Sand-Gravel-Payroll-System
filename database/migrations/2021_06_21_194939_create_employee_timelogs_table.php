<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTimelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('employee_timelogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedBigInteger('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('break_time')->nullable();
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
        Schema::dropIfExists('employee_timelogs');
        Schema::enableForeignKeyConstraints();
    }
}
