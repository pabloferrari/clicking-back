<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Root', 'slug' => 'root', 'description' => 'root user', 'level' => 1],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'admin user', 'level' => 2],
            ['name' => 'Institution', 'slug' => 'institution', 'description' => 'institution user', 'level' => 3],
            ['name' => 'Teacher', 'slug' => 'teacher', 'description' => 'teacher user', 'level' => 4],
            ['name' => 'Student', 'slug' => 'student', 'description' => 'student user', 'level' => 5]
        ]);
    }
}
