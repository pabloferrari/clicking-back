<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'Root','email' => 'root@clicking.com','description' => 'Root user','image' => '','password' => Hash::make('eo3ahleu3Oor')],
            ['name' => 'Admin','email' => 'admin@clicking.com','description' => 'Admin user','image' => '','password' => Hash::make('Chepa4miw9qu')],
        ]);
    }
}
