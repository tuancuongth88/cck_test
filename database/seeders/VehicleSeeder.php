<?php

namespace Database\Seeders;

use App\Models\VehicleMaster;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleMaster::factory()->create();
    }
}
