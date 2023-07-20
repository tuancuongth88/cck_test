<?php

namespace Database\Seeders;

use App\Models\Passion;
use Illuminate\Database\Seeder;

class PassionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Passion::insert([
                ['name_jp' => '住宅手当有', 'name_en' => 'Housing allowance'],
                ['name_jp' => '福利厚生充実', 'name_en' => 'Welfare enhancement'],
                ['name_jp' => '退職金有', 'name_en' => 'Severance pay'],
                ['name_jp' => '年間休日120日以上', 'name_en' => '120 or more annual holidays'],
                ['name_jp' => '寮有', 'name_en' => 'Residence'],
                ['name_jp' => '未経験可', 'name_en' => 'No experience ok'],
                ['name_jp' => '外国人管理職登用実績有', 'name_en' => 'experience of foreigners appointed to managerial positions'],
                ['name_jp' => 'リモート勤務可', 'name_en' => 'Remote work ok'],
                ['name_jp' => '引っ越し手当有', 'name_en' => 'Moving allowance'],
                ['name_jp' => '週28時間以下', 'name_en' => 'OK/within 28 hours per week ok'],
            ]
        );
    }
}
