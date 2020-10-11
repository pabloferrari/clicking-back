<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert([
            ['name' => 'CABA','iso31662' => 'AR-C', 'country_id' => 1],
            ['name' => 'Buenos Aires','iso31662' => 'AR-B', 'country_id' => 1],
            ['name' => 'Catamarca','iso31662' => 'AR-K', 'country_id' => 1],
            ['name' => 'Córdoba','iso31662' => 'AR-X', 'country_id' => 1],
            ['name' => 'Corrientes','iso31662' => 'AR-W', 'country_id' => 1],
            ['name' => 'Entre Ríos','iso31662' => 'AR-E', 'country_id' => 1],
            ['name' => 'Jujuy','iso31662' => 'AR-Y', 'country_id' => 1],
            ['name' => 'Mendoza','iso31662' => 'AR-M', 'country_id' => 1],
            ['name' => 'La Rioja','iso31662' => 'AR-F', 'country_id' => 1],
            ['name' => 'Salta','iso31662' => 'AR-A', 'country_id' => 1],
            ['name' => 'San Juan','iso31662' => 'AR-J', 'country_id' => 1],
            ['name' => 'San Luis','iso31662' => 'AR-D', 'country_id' => 1],
            ['name' => 'Santa Fe','iso31662' => 'AR-S', 'country_id' => 1],
            ['name' => 'Santiago del Estero','iso31662' => 'AR-G', 'country_id' => 1],
            ['name' => 'Tucumán','iso31662' => 'AR-T', 'country_id' => 1],
            ['name' => 'Chaco','iso31662' => 'AR-H', 'country_id' => 1],
            ['name' => 'Chubut','iso31662' => 'AR-U', 'country_id' => 1],
            ['name' => 'Formosa','iso31662' => 'AR-P', 'country_id' => 1],
            ['name' => 'Misiones','iso31662' => 'AR-N', 'country_id' => 1],
            ['name' => 'Neuquén','iso31662' => 'AR-Q', 'country_id' => 1],
            ['name' => 'La Pampa','iso31662' => 'AR-L', 'country_id' => 1],
            ['name' => 'Río Negro','iso31662' => 'AR-R', 'country_id' => 1],
            ['name' => 'Santa Cruz','iso31662' => 'AR-Z', 'country_id' => 1],
            ['name' => 'Tierra del Fuego','iso31662' => 'AR-V', 'country_id' => 1]
        ]);
    }
}
