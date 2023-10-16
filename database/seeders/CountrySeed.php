<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            'Vietnam' => 'ベトナム',
            'Myanmar' => 'ミャンマー',
            'Philippines' => 'フィリピン',
            'Bangladesh' => 'バングラデシュ',
            'Nepal' => 'ネパール',
            'Cambodia' => 'カンボジア',
            'Thailand' => 'タイ',
        ];

        if (!Country::first()) {
            foreach ($countries as $key => $value) {
                Country::create([
                    'name_en' => $key,
                    'name_ja' => $value
                ]);
            }
        }
    }
}
