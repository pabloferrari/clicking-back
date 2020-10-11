<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            ['name' => "Argentina", 'code' => "AR", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => "Venezuela", 'code' => "VE", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
