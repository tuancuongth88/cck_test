<?php


namespace Tests\Browser\Scenario;


use App\Models\Company;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

trait FakeDataST
{
    use WithFaker;
    public function fakeDataCaseG(){
        $majorId = JobType::query()
            ->where('type', JOB_TYPE)
            ->inRandomOrder()
            ->value('id');
        $middleId = JobInfo::query()
            ->where('job_type_id', $majorId)
            ->value('id');
        $user = User::factory()->create(['type' => COMPANY, 'mail_address' => 'hanoicompany@gmail.com']);
        $dataTest = [
            Company::COMPANY_NAME => 'Hanoi Company',
            Company::COMPANY_NAME_JP => 'Hanoi Company',
            Company::USER_ID => $user->id,
            Company::MAJOR_CLASSIFICATION => $majorId,
            Company::MIDDLE_CLASSIFICATION => $middleId,
            Company::POST_CODE => '1020093',
            Company::PREFECTURES => '東京都',
            Company::MUNICIPALITY => '千代田区平河町',
            Company::NUMBER => '1-7-10',
            Company::OTHER => '大盛丸平河町ビル2階',
            Company::TELEPHONE_NUMBER => '+84 0312345678',
            Company::MAIL_ADDRESS => "hanoicompany@gmail.com",
            Company::URL => 'https://okuridashi_hanoi.com',
            Company::JOB_TITLE => '代表取締役会長',
            Company::FULL_NAME => '鈴木　太郎',
            Company::FULL_NAME_FURIGANA => 'スズキ　タロウ',
            Company::REPRESENTATIVE_CONTACT => '+84 0312345678',
            Company::ASSIGNEE_CONTACT => '+84 0312345678',
            Company::STATUS => CONFIRM,
        ];
        $this->company = Company::factory()->create($dataTest);
        //fake data work
        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        for ($i = 0; $i < 10; $i++){
            $workData = [
                Work::USER_ID => $user->id,
                Work::MAJOR_CLASSIFICATION_ID => $jobType->id,
                Work::MIDDLE_CLASSIFICATION_ID => $jobInfo->id,
                Work::TITLE => 'ITエンジニア',
                Work::COMPANY_ID => $this->company->id,
                Work::APPLICATION_PERIOD_FROM => Carbon::now()->format('Y-m-d'),
                Work::APPLICATION_PERIOD_TO => Carbon::now()->addDay(5)->format('Y-m-d'),
                Work::JOB_DESCRIPTION => 'ITエンジニアのFEとしてシステム開発',
                Work::APPLICATION_CONDITION => '3年以上の経験必須',
                Work::APPLICATION_REQUIREMENT => 'マネージメント経験があるとなお良し',
                Work::OTHER_SKILL => '日本語、ベトナム語',
                Work::PREFERRED_SKILL => 'ビジネスレベル英語',
                Work::FORM_OF_EMPLOYMENT => WORK_FULL_TIME_EMPLOYEE,
                Work::WORKING_TIME_FROM => '09:00',
                Work::WORKING_TIME_TO => '17:00',
                Work::VACATION => '土日祝日',
                Work::EXPECTED_INCOME => 500,
                Work::WORKING_PALACE_DETAIL => '大阪環状線「大阪駅」より徒歩5分',
                Work::TREATMENT_WELFARE => '保険加入、住宅手当・家賃補助有り、通勤手当有り',
                Work::COMPANY_PR_APPEAL => 'インターナショナルで活気のある雰囲気です',
                Work::BONUS_PAY_RISE => WORK_CONFIRM_YES,
                Work::OVER_TIME => WORK_CONFIRM_YES,
                Work::TRANSFER => WORK_CONFIRM_NO,
                Work::PASSIVE_SMOKING => WORK_CONFIRM_NO,
                Work::INTERVIEW_FOLLOW => WORK_SECOND_INTERVIEW,
                Work::STATUS => WORK_STATUS_RECRUITING,
                Work::CITY_ID => 27,
            ];

            $work = Work::create($workData);
            $this->addRelationHasMany(LanguageRequirementWork::class, [1, 2, 3],
                $work->id, 'language_requirement_id');

            $this->addRelationHasMany(PassionWork::class, [2,5,8,9],
                $work->id, 'passion_id');
        }

        //fake data hr
        $hrUser = User::query()->where(User::TYPE, HR)->first();
        for ($i = 1; $i <= 25; $i++) {

            $name = $this->faker->name;
            $major_id = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
            $middle_id = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major_id)->inRandomOrder()->pluck('id')->first();
            $status = HRS_STATUS_PUBLIC;
            if ($i == 23) {
                $status = HRS_STATUS_PRIVATE;
            }
            if ($i == 24) {
                $status = HRS_STATUS_CONFIRM;
            }
            $work_form = $this->faker->randomElement(HRS_WORK_FORM);
            $hr_id = HR::create([
                HR::HR_ORGANIZATION_ID => $hrUser->hrOrganization->id,
                HR::USER_ID => $hrUser->id,
                HR::FULL_NAME => $name,
                HR::FULL_NAME_JA => $name,
                HR::GENDER => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
                HR::CREATED_BY => $hrUser->id,
                HR::DATE_OF_BIRTH => $this->faker->dateTimeBetween('-50 years', '-18 years'),
                HR::FINAL_EDUCATION_DATE => $this->faker->dateTimeBetween('-20 years')->format('Y-m'),
                HR::FINAL_EDUCATION_CLASSIFICATION => $this->faker->randomElement(HR_FINAL_EDUCATION),
                HR::FINAL_EDUCATION_DEGREE => $this->faker->randomElement(array_keys(HR_EDUCATION_DEGREES)),
                HR::WORK_FORM => $work_form,
                HR::PREFERRED_WORKING_HOURS => $work_form == HRS_FULL_TIME_EMPLOYEE ? null : rand(25, 60),
                HR::JAPANESE_LEVEL => LanguageRequirement::query()->inRandomOrder()->pluck('id')->first(),
                HR::MAJOR_CLASSIFICATION_ID => $major_id,
                HR::MIDDLE_CLASSIFICATION_ID => $middle_id,
                HR::MAIL_ADDRESS => $this->faker->email,
                HR::STATUS => $status
            ]);

            HRMainJobCareer::factory()->create([HRMainJobCareer::HRS_ID => $hr_id]);
        }

    }


    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach ($items as $k => $item){
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }
}
