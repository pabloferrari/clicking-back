<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_types')->insert([
            ['name' => "Curso", "institution_id" => 1],
            ['name' => "Taller", "institution_id" => 1]
        ]);
    }
}
