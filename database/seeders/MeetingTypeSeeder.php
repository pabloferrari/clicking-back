<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MeetingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bbb_meeting_types')->insert([
            ['type' => 'classroom', 'role' => 'root'],
            ['type' => 'classroom', 'role' => 'admin'],
            ['type' => 'classroom', 'role' => 'institution'],
            ['type' => 'classroom', 'role' => 'teacher'],
            ['type' => 'class', 'role' => 'root'],
            ['type' => 'class', 'role' => 'admin'],
            ['type' => 'class', 'role' => 'institution'],
            ['type' => 'class', 'role' => 'teacher'],
            ['type' => 'user', 'role' => 'root'],
            ['type' => 'user', 'role' => 'admin'],
            ['type' => 'user', 'role' => 'institution'],
            ['type' => 'user', 'role' => 'teacher'],
            ['type' => 'teacher', 'role' => 'root'],
            ['type' => 'teacher', 'role' => 'admin'],
            ['type' => 'teacher', 'role' => 'institution'],
            ['type' => 'teacher', 'role' => 'teacher'],
            ['type' => 'student', 'role' => 'root'],
            ['type' => 'student', 'role' => 'admin'],
            ['type' => 'student', 'role' => 'institution'],
            ['type' => 'student', 'role' => 'teacher'],
        ]);

        // php artisan db:seed --class=MeetingTypeSeeder
    }
}


