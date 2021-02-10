<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('institutions')->insert([
            'id' => 1,
            'name' => 'IIC',
            'email' => 'iic@gmail.com',
            'phone' => '45454545',
            'cuit' => '33-12312323-5',
            'image' => NULL,
            'plan_id' => 1,
            'city_id' => 2755,
            'active' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}