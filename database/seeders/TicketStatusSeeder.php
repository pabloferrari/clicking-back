<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Carbon;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_status')->insert([
            [
                'name' => "Pendiente", 
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now()
            ],
            [
                'name' => "En Proceso", 
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now()
            ],
            [
                'name' => "Completado", 
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
