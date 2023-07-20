<?php

namespace Database\Seeders;

use App\Models\LanguageRequirement;
use Illuminate\Database\Seeder;

class LanguageRequirementSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LanguageRequirement::insert(
            [
                ['name' => 'N1',],
                ['name' => 'N2'],
                ['name' => 'N3'],
                ['name' => 'N4'],
                ['name' => 'N5'],
                ['name' => 'Other']
            ]
        );
    }
}
