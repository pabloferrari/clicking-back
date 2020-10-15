<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('teachers')->insert([
            [
                  'name' => "Pedro Perez"
                , 'email' => "pperez@gmail.com"
                , 'phone' => "5894589644"
                , 'active' => 1
                , 'user_id' => 1
                , 'created_at' => Carbon::now()
                , 'updated_at' => Carbon::now()
            ],
            [
                'name' => "Luis Santori"
              , 'email' => "lsantori@gmail.com"
              , 'phone' => "58945896344"
              , 'active' => 1
              , 'user_id' => 1
              , 'created_at' => Carbon::now()
              , 'updated_at' => Carbon::now()
            ],
            [
                'name' => "Carlos Reyez"
              , 'email' => "creyez@gmail.com"
              , 'phone' => "58945896344"
              , 'active' => 0
              , 'user_id' => 1
              , 'created_at' => Carbon::now()
              , 'updated_at' => Carbon::now()
            ],
           
        ]);
    }
}
