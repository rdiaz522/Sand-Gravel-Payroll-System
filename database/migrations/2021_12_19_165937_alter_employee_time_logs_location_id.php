<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmployeeTimeLogsLocationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if (!Schema::hasColumn('employee_timelogs', 'location_id')) {
            Schema::table('employee_timelogs', function (Blueprint $table) {
                $table->unsignedBigInteger('location_id');
            });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      if (Schema::hasColumn('employee_timelogs', 'location_id')) {
            Schema::table('employee_timelogs', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
      }
    }
}
