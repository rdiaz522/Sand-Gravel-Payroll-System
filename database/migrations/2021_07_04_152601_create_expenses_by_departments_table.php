<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesByDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('expenses_by_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('locations');
            $table->string('description')->nullable();
            $table->float('amount')->nullable();
            $table->string('cash_from')->nullable();
            $table->string('cash_date')->nullable();
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
        Schema::dropIfExists('expenses_by_departments');
        Schema::enableForeignKeyConstraints();
    }
}
