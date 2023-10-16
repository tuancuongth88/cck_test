<?php

namespace Tests\Browser;

use App\Models\Entry;
use App\Models\HR;
use App\Models\Offer;
use App\Models\User;
use App\Models\Work;
use App\Notifications\DistributionNotification;
use App\Notifications\RemindAccountNotification;
use Facebook\WebDriver\WebDriverBy;
use Helper\Common;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    private $faker;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        $this->fakeMessage();
        $this->browse(function ($browser) {
            $this->login();
            $this->caseHomeMsg($browser);
            $this->caseHomeOnGoingJob($browser);
            $this->caseHomeOther($browser);
        });
    }

    public function caseHomeMsg(Browser $browser) {
        //fake data notify Msg
        $browser->assertSee('新着メッセージ')
            ->assertSee('メッセージをさらに表示する')->pause(5000)
            ->click('@show-more-msg')->pause(3000)
            ->assertSee('新着メッセージ')
            ->click('@detail-msg')->pause(2000);
    }


    public function caseHomeOther(Browser $browser)
    {
        $browser->visit('/home')->pause(5000);
        $browser->click('@create_msg')->pause(1000);
        //test validate
        $browser->click('@create-msg')->pause(1000)->assertSee('入力してください');
        //test upload file > 3mb
        $file = "tests/FileTest/error_file_size.crdownload";
        $browser->attach('@file-input', $file)->pause(1000)->assertSee('3MB以内のファイルをアップロードしてください。');
        //test create success
        $file = "tests/FileTest/image.png";
        $browser->typeSlowly('@title', 'system alert')
                ->typeSlowly('@description', 'content body text')
                ->attach('@file-input', $file)->pause(5000)
                ->click('@create-msg')->pause(10000);

        $browser->scrollIntoView('#distribution-msg-table')->assertSee('system alert')->pause(2000);
    }

    private function fakeMessage(){
        for ($i = 0; $i < 10; $i++){
            $user = User::query()->find(1);
            $data['permission'] = User::getPermissionName($user);
            $data['subject'] = 'test message';
            $data['type'] = $user->type;
            $data['text'] = 'this is note text';
            $data['company'] = 'Veho Company';
            $data['content'] = '求人にエントリーが実行されました。<br><a href="/matching-management">こちら</a>よりエントリーの内容を確認してください。';
            $data['entry_code'] = '001';
            $data['job'] = 'Develop PHP Senior';
            $data['full_name_ja'] = 'Nguyen Thi Nhi';
            $data['date'] = Carbon::now()->format('Y/m/d');
            $data['contentHTML'] = view('messages.remind.format_b', compact('data'))->render();
            Notification::send($user, new RemindAccountNotification($data));
        }
    }

    private function fakeDataOnGoingJob($model)
    {
        $this->faker = \Faker\Factory::create();
        $userHr = User::query()->where(User::TYPE, \HR)->first()->id;
        $userCompany = User::query()->where(User::TYPE, \COMPANY)->first()->id;
        for ($i = 1; $i <= 21; $i++) {
            if($i<= 6) {
                $hrId = HR::factory()->create([HR::USER_ID => $userHr])->id;
                $workId = Work::factory()->create([Work::USER_ID => $userCompany])->id;
            } else {
                $hrId = HR::factory()->create()->id;
                $workId = Work::factory()->create()->id;
            }

            if($model == Entry::class) {
                $status = rand(1, 4);
                Entry::create([
                    Entry::ENTRY_CODE => 'E' . rand(00000000, 10000000),
                    Entry::HR_ID => $hrId,
                    Entry::WORK_ID => $workId,
                    Entry::REMARKS => $this->faker->text(30),
                    Entry::DISPLAY => 'on',
                    Entry::STATUS => $status,
                    Entry::REQUEST_DATE => $this->faker->dateTimeBetween('-1 years', 'today')->format('Y-m-d')
                ]);
            } else {
                $status = rand(1, 3);
                Offer::factory()->create([
                    Offer::HR_ID => $hrId,
                    Offer::WORK_ID => $workId,
                    Offer::REMARKS => $this->faker->text(30),
                    Offer::DISPLAY => 'on',
                    Offer::STATUS => $status,
                    Offer::REQUEST_DATE => $this->faker->dateTimeBetween('-1 years', 'today')->format('Y-m-d')
                ]);
            }
        }
    }

    public function caseHomeOnGoingJob(Browser $browser)
    {
        $this->fakeDataOnGoingJob(Entry::class);
        $this->fakeDataOnGoingJob(Offer::class);
        $types = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER, \COMPANY, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $browser->pause(10000)
                ->scrollIntoView('#dusk_on_going_job')
                ->assertSee('進行中の案件')->pause(15000);

            $user = User::query()->where(User::TYPE, $type)->first();
            $dataFull = [];
            $listDataOffer = Offer::select("offers.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
                "offers.status", "offers.request_date", "works.id as workdid", "works.title", "companies.company_name", "companies.company_name_jp", "interviews.step")
                ->join('hrs', 'hrs.id', '=', "offers.hr_id")
                ->join('works', 'works.id', '=', "offers.work_id")
                ->join('companies', 'companies.id', '=', "works.company_id")
                ->leftJoin('interviews', function ($interview) {
                    $interview->on('interviews.hr_id', '=', 'hrs.id')
                        ->on('works.id', '=', 'interviews.work_id');
                })
                ->whereIn('offers.display', ['on', 'off'])
                ->orderBy('offers.id', 'desc');
            $listDataOffer = Common::getDataForPermisstion($listDataOffer, $user);
            $listDataOffer = $listDataOffer->get();

            $listDataEntry = Entry::select("entries.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
                "entries.status", "works.id as workdid", "entries.request_date", "works.title", "entries.code", "companies.company_name", "companies.company_name_jp", "interviews.step")
                ->join('hrs', 'hrs.id', '=', "entries.hr_id")
                ->join('works', 'works.id', '=', "entries.work_id")
                ->join('companies', 'companies.id', '=', "works.company_id")
                ->leftJoin('interviews', function ($interview) {
                    $interview->on('interviews.hr_id', '=', 'hrs.id')
                        ->on('works.id', '=', 'interviews.work_id');
                })
                ->whereIn('entries.display', ['on', 'off'])
                ->orderBy('entries.id', 'desc');
            $listDataEntry = Common::getDataForPermisstion($listDataEntry, $user);
            $listDataEntry = $listDataEntry->get();

            $totalRecord = count($listDataOffer) + count($listDataEntry);
            $listDatas = collect(array_merge($listDataOffer->toArray(), $listDataEntry->toArray()))->sortByDesc('request_date');
            foreach ($listDatas as $listData) {
                $dataFull[] = [
                    'occupation_ja' => isset($listData['code']) ? 'エントリー' : 'オファー',
                    'full_name' => $listData['full_name'],
                    'full_name_ja' => $listData['full_name_ja'],
                    'job_name' => $listData['title'],
                    'company_name_ja' => $listData['company_name_jp'],
                ];
            }

            if($totalRecord > 0) {
                $browser->scrollIntoView('#dusk_on_going_job > div > div > div > a')
                    ->click('#dusk_on_going_job > div > div > div > a')->pause(5000);

                $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
                $dataList = [];
                foreach ($attributes as $attribute) {
                    if (empty($attribute->getText())) break;

                    $dataList[] = explode("\n", $attribute->getText());
                }
                foreach ($dataList as $key => $value) {
                    if (!$value) break;
                    unset($value[0]);
                    $this->assertTrue(count(array_diff($value, $dataFull[$key])) == 0);
                }

                $count = count($dataList);
                if ($totalRecord > 20) {
                    $tmp = intdiv($totalRecord, 20);
                    $max = ($totalRecord % 20 > 0) ? $tmp + 1 : $tmp;
                    $this->assertEquals($count, 20);
                    $browser->scrollIntoView('.pagination')->pause(5000)
                        ->assertSeeIn('.pagination', $max);
                } else {
                    $this->assertEquals($count, $totalRecord);
                    $browser->scrollIntoView('.pagination')->pause(3000)
                        ->assertDontSeeIn('.pagination', '2');
                }
            }

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }
}
