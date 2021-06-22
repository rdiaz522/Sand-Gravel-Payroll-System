<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class EmployeeSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i = 1; $i < 30; $i++) {
            DB::table('employees')->insert([
                'firstname' =>  Str::random(10),
                'lastname' =>  Str::random(10),
                'middlename' =>  Str::random(10),
            ]);
        }
    }
}
