<?php

namespace Database\Seeders;

use App\Models\Country;
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
        $this->call(LaratrustSeeder::class);
        $this->call(UserSeeder::class);
//        $this->call(ProvinceSeeder::class);
//        $this->call(WardSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(JobSeeder::class);
        $this->call(LanguageRequirementSeed::class);
        $this->call(PassionSeed::class);
        $this->call(CountrySeed::class);
    }
}
