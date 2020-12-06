<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use DB;

class AssignmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assignment_types')->insert([
            ['name' => "Tarea", 'group_enabled' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => "Evaluación", 'group_enabled' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => "Trabajo Practico", 'group_enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
