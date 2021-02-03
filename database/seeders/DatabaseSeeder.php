<?php

namespace Database\Seeders;

use App\Models\AssignmentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     CountrySeeder::class,
        //     ProvinceSeeder::class,
        //     CitySeeder::class,
        // ]);

        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            RoleUserSeeder::class,
            CountrySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            AssignmentTypeSeeder::class,
            TeacherSeeder::class,
            MeetingTypeSeeder::class,
            CourseTypeSeeder::class,
            AssignmentStatusSeeder::class
        ]);
    }   
}
