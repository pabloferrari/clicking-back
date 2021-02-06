<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AssignmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assignment_status')->insert([
            ['name' => "Pendiente"],
            ['name' => "En Corrección"],
            ['name' => "Correjido"],
        ]);
    }
}
