<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'name' => 'Cow Shed',
        ]);

        DB::table('locations')->insert([
            'name' => 'Processing',
        ]);

        DB::table('locations')->insert([
            'name' => 'Sugarcane',
        ]);
    }
}
