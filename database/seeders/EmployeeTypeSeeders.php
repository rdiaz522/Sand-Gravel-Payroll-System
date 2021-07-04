<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTypeSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee_types')->insert([
            'name' => 'Regular',
        ]);
        DB::table('employee_types')->insert([
            'name' => 'Temporary',
        ]);
    }
}
