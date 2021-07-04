<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreakHourSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('break_hours')->insert([
            'name' => '00:00',
        ]);
        DB::table('break_hours')->insert([
            'name' => '00:30',
        ]);
        DB::table('break_hours')->insert([
            'name' => '01:00',
        ]);
        DB::table('break_hours')->insert([
            'name' => '01:30',
        ]);
    }
}
