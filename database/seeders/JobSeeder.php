<?php

namespace Database\Seeders;

use App\Models\JobInfo;
use App\Models\JobType;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            JOB_TYPE => [
                '農業、林業、漁業|Agriculture, Forestry, Fishery' => [
                    '耕種農業|Crop farming',
                    '畜産農業|Livestock farming',
                    '育林業|Forestry expansion',
                    '素材生産業|Material production industry',
                    'その他林業|Other forestry business',
                    '海面漁業（海での漁師）|Sea fishery(fishermen at sea)',
                    '内水面漁業（淡水での漁師）|Inland fishery (Fishermen at freshwater)',
                    '海面養殖業（海での養殖）|Aquaculture (In the sea)',
                    '内水面養殖業（淡水での養殖）|Inland water culture (In fresh water)',
                    'その他|Others'
                ],
                '建設業|Construction industry' => [
                    '土木工事業|Civil engineering business', '舗装工事業|Pavement construction business', '建築工事業|Construction work', 'その他|Others'
                ],
                '製造業|Manufacturing industry' => [
                    '飲食料品製造業|Food and beverage manufacturing industry',
                    '繊維・縫製業|Textile・Sewing industry',
                    '木材・木製品製造業|Lumber・Wood products manufacturing industry',
                    '印刷関連業|Printing industry',
                    'プラスチック製品製造業|Plastic product manufacturing industry',
                    '鉄鋼業|Steel industry',
                    '非鉄金属製造業|Non-ferrous metal manufacturing industry　',
                    '金属製品製造業|Fabricated metal product manufacturing industry',
                    '機械器具製造業 |Equipment manufacturing industry',
                    '電気・電子製造業|Electric・Electronic manufacturing industry',
                    'その他|Others'
                ],
                '電気・ガス・水道業|Electricity・Gas・Water industry' => [
                    '電気業|Electric industry', 'ガス業|Gas industry', '水道業|Waterworks', 'その他|Others',
                ],
                '情報通信業|Telecommunications industry' => [
                    '通信業|Communications industry',
                    '放送業|Broadcasting industry',
                    '情報サービス業|Information service industry',
                    '映像・音声・文字情報制作業|Video・Audio・Text data production',
                    'その他|Others'
                ],
                '運輸業、郵便業|Transportation industry, Postal industry' => [
                    '鉄道業|Railway industry',
                    '旅客運送業|Passenger transportation business',
                    '貨物運送業|Freight transportation business',
                    '倉庫業|Warehouse business',
                    '郵便業|Postal business',
                    'その他|Others'
                ],
                '卸売業、小売業|Wholesale business,Retail business' => [
                    '飲食料品卸売業|Beverage wholesale business',
                    '繊維・衣服等卸売業|Fiber・Clothing wholesales business',
                    '材料卸売業（建築・鉱物・金属）|Material wholesale trade (Construction・minerals・metals)',
                    '機械器具卸売業|Machines instruments wholesales business',
                    'その他|Others'
                ],
                '金融業、保険業|Finance industry, Insurance industry' => [
                    '銀行業|Banking', '貸金・クレジットカード業|Debt・Credit card business', '保険業|Insurance business', 'その他|Others'
                ],
                '不動産業|Real estate business' => [
                    '不動産売買業|Real estate trading business', '不動産賃貸業|Real estate rental business', 'その他|Others',
                ],
                '学術研究、専門・技術|Academic research, expertise and technology' => [
                    '学術・開発研究機関|Academic・development research organization',
                    '広告業|Advertising industry',
                    '専門サービス業（司法、行政、デザイン）|Professional service industry (judicial, administrative, design)',
                    'その他|Others',
                ],
                '宿泊業、飲食サービス業|Accommodation business, food service business' => [
                    '宿泊業|Accommodation business', '飲食店業|Food  business', 'その他|Others'
                ],
                '生活関連サービス業|Life - related service industry' => [
                    '美容師業|Beautician business',
                    'カジノ業|Casino business',
                    '映画館・公園・遊園地等|Movie theaters・ Parks・ Amusement parks etc',
                    'その他|Others'
                ],
                '教育、学習支援業|Education, Learning support industry' => [
                    '学校教育業|School education business',
                    '学校教育支援機関|School education support organization ',
                    '学習塾|Tutoring school',
                    '職業・教育支援施設|Occupation・Educational support facilities',
                    'その他|Others',
                ],
                '医療|Medical treatment' => ['病院|Hospitals', 'その他|Others'],
                '公務|Public service' => ['公務員|Public employee', 'その他|Others'],
                'サービス業|Service industry' => [
                    'クリーニング|Cleaning',
                    '自動車整備|Car maintenance',
                    '人材事業 |Human resource',
                    '協同組合|Cooperative',
                    'その他|Others'
                ],
                'その他事業|Other business' => ['その他|Others'],
            ],
            MAJOR_CLASSIFICATION => [
                '経営学等|Business administration, etc' => [
                    '会計学|Accounting', '経営学|Business Administration', 'マーケティング|Marketing', 'その他|Others'
                ],
                '人文化学|Humanities' => [
                    '芸術|Art', '歴史学|History', '文学|Literature', 'その他|Others'
                ],
                '言語学|Linguistics' => [
                    '日本語|Japanese', '英語|English', 'その他|Others'
                ],
                '自然科学|Natural sciences' => [
                    '建築学|Architecture', 'コンピュータサイエンス|Computer Science', '工学|Engineering',
                    '統計学|Statistics', 'その他|Others'
                ],
                '社会科学|Social science' => [
                    '経済学|Economics', '教育学|Pedagogy', '行政学|Administration', '国際関係学|International Relations',
                    '法律学|Legal Studies', '政治学|Political Science', 'その他|Others'
                ],
                'その他|Others' => ['その他|Others'],
            ]
        ];

        if (!JobType::first() && !JobInfo::first()) {
            foreach ($data as $key => $value) {
                foreach ($value as $keyJob => $job) {
                    $name = explode('|', $keyJob);
                    $job_type = JobType::create([
                        'name_ja' => data_get($name, 0),
                        'name_en' => data_get($name, 1),
                        'type' => $key
                    ]);
                    foreach ($job as $jobInfo) {
                        $nameInfo = explode('|', $jobInfo);
                        JobInfo::create([
                            'job_type_id' => $job_type->id,
                            'name_ja' => data_get($nameInfo, 0),
                            'name_en' => data_get($nameInfo, 1),
                        ]);
                    }
                }
            }
        }
    }
}
