<?php


namespace Tests\Browser\Scenario;


use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\Interview;
use App\Models\InterviewInfo;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\PassionWork;
use App\Models\PasswordReset;
use App\Models\Result;
use App\Models\User;
use App\Models\UserFavorite;
use App\Models\Work;
use Facebook\WebDriver\WebDriverBy;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;

trait SetupDataInterview
{
    use RefreshDatabase;
    protected $company;
    protected $companyUser;
    protected $hrOrgUser;
    protected $hrOrgCompany;
    protected $faker;
    public function setupRemindCreateCompany($companyEmail)
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        // Clear Company email
        $company = Company::query()->where(Company::MAIL_ADDRESS, '=', $companyEmail)->first();
        if($company) $company->forceDelete();

    }
    public function setupUserOnly()
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
    }

    public function setupCompanyWithMyEmail()
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
    }

    public function setupHrWithMyEmail($myEmail, $fakeToken)
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        $hrUser = User::query()->where(User::TYPE, 5)->first();
        $hrUser->mail_address = $myEmail;
        $hrUser->save();
        PasswordReset::create([
            'email' => $myEmail,
            'token' => $fakeToken
        ]);
    }

    public function setupJobAnHrList($targetJobName, $targetHrName)
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        $this->fakeDataJob($targetJobName);
        $this->fakeDataHr($targetHrName);
    }

    public function setupInterviewToOffer($targetJobName, $targetHrName)
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        $this->fakeDataJob($targetJobName);
        $this->fakeDataHr($targetHrName);
        $hrInfo = HR::query()->where(HR::FULL_NAME, $targetHrName)->first();
        $jobInfo = Work::query()->where(Work::TITLE, $targetJobName)->first();
        // Fake Offer
        Offer::create([
            Offer::HR_ID => $hrInfo->id,
            Offer::WORK_ID => $jobInfo->id,
            Offer::STATUS => 3,
            Offer::REQUEST_DATE => Carbon::now(),
            Offer::DISPLAY => 'off'
        ]);
        Interview::create([
            Interview::HR_ID => $hrInfo->id,
            Interview::WORK_ID => $jobInfo->id,
            Interview::INTERVIEW_DATE => Carbon::now(),
            Interview::TYPE => 2,
            Interview::STATUS_SELECTION => 2,
            Interview::INTERVIEW_ADJUSTMENT => 1,
            Interview::DISPLAY => 'on',
            Interview::STEP => 3
        ]);
    }

    public function setupMatchingOffer($targetJobName, $targetHrName)
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        $this->fakeDataJob($targetJobName);
        $this->fakeDataHr($targetHrName);
        $hrInfo = HR::query()->where(HR::FULL_NAME, $targetHrName)->first();
        $jobInfo = Work::query()->where(Work::TITLE, $targetJobName)->first();
        // Fake Offer
        Offer::create([
            Offer::HR_ID => $hrInfo->id,
            Offer::WORK_ID => $jobInfo->id,
            Offer::STATUS => 1,
            Offer::REQUEST_DATE => Carbon::now(),
            Offer::DISPLAY => 'on'
        ]);
    }

    public function setupDataHRListToCanSendOffer($targetJobName, $targetHrName)
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        $this->fakeDataJob($targetJobName);
        $this->fakeDataHr($targetHrName);
        // Fake
    }

    public function setupDataInterviewFirstCompleted()
    {
        $this->seed();
        $this->clearDataInterviewBeforeSetupData();
        $this->faker = Factory::create();
        $this->configUser();
        $this->fakeDataJob();
        $this->fakeDataHr();
        $this->fakeDataInterview();
        $interviews = Interview::query()->get();
        foreach ($interviews as $key1 => $interview) {
            $this->_fakeDataCompleteStep1($interview->id);
        }
    }

    public function setupDataInterviewSecondSelectedDateCompleted()
    {
        $this->setupDataInterviewFirstCompleted();
        $interviews = Interview::query()->get();
        foreach ($interviews as $key1 => $interview) {
            $this->_fakeSelectedDateCompleteStep2($interview->id);
        }
    }

    public function setupDataInterviewSecondSetUrlCompleted()
    {
        $this->setupDataInterviewFirstCompleted();
        $interviews = Interview::query()->get();
        foreach ($interviews as $key1 => $interview) {
            $this->_fakeDataCompleteSetURLStep2($interview->id);
        }
    }

    public function setupDataInterviewSecondPassToResult()
    {
        $this->setupDataInterviewFirstCompleted();
        $interviews = Interview::query()->limit(1)->get();
        foreach ($interviews as $key1 => $interview) {
            $this->_fakeDataPassStep2($interview->id);
        }
        $this->_createOtherEntriesAndInterviewForHr($interviews[0]);
    }

    public function setupDataResultWithTimeToHireDate()
    {
        $this->setupDataInterviewFirstCompleted();
        $interviews = Interview::query()->limit(1)->get();
        foreach ($interviews as $key1 => $interview) {
            $this->_fakeDataPassStep2($interview->id);
            // Update status of result
            $targetResult = Result::query()->where(Result::INTERVIEW_ID, $interview->id)->first();
            if ($targetResult) {
                $targetResult->status_selection = 3;
                $targetResult->time = null;
                $targetResult->save();
            }
        }

    }

    private function _createOtherEntriesAndInterviewForHr($interview)
    {
        $jobInfo = JobInfo::query()->whereNotIn('id', [$interview->work_id])->get();
        // Create Entries
        Entry::create([
            Entry::DISPLAY => 'on',
            Entry::HR_ID => $interview->hr_id,
            Entry::WORK_ID => $jobInfo[0]->id,
            Entry::STATUS => 1,
            Entry::ENTRY_CODE => 'E1'
        ]);
        // Create interview
        Interview::create([
            Interview::HR_ID => $interview->hr_id,
            Interview::WORK_ID => $jobInfo[2]->id,
            Interview::INTERVIEW_DATE => \Carbon\Carbon::now()->toDateString(),
            Interview::TYPE => 2,
            Interview::STATUS_SELECTION => 1,
            Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT,
            Interview::REMARKS => '',
            Interview::DISPLAY => 'on',
            Interview::STEP => 3
        ]);
        // Create offer
        Offer::create([
            Offer::HR_ID => $interview->hr_id,
            Offer::WORK_ID => $jobInfo[3]->id,
            Offer::REQUEST_DATE => \Carbon\Carbon::now()->toDateString(),
            Offer::STATUS => 1,
            Offer::DISPLAY => 'on',
        ]);
    }

    private function clearDataInterviewBeforeSetupData()
    {
        Work::query()->truncate();
        HR::query()->truncate();
        Entry::query()->truncate();
        Offer::query()->truncate();
        Interview::query()->truncate();
        InterviewInfo::query()->truncate();
        LanguageRequirementWork::query()->truncate();
        PassionWork::query()->truncate();
        Result::query()->truncate();
        Notification::query()->truncate();
        UserFavorite::query()->truncate();
        PasswordReset::query()->truncate();
    }

    private function configUser()
    {
        $user = User::query()->where('type', COMPANY)->first();
        if (!$user) {
            $user = User::factory()->create(['type' => COMPANY, 'mail_address' => 'test@gmail.com']);
        }
        $this->companyUser = $user;
        $company = Company::query()->where('user_id', $user->id)->first();
        if (!$company) {
            $company = Company::factory()->create([
                'user_id' => $user->id,
                Company::MAIL_ADDRESS => $this->companyUser->mail_address,
                Company::STATUS => CONFIRM]);
        }
        $this->company = $company;
        // For company manage
        $companyManagerUser = User::query()->where(User::TYPE, COMPANY_MANAGER)->first();
        if (!$companyManagerUser) {
            $companyManagerUser = User::factory()->create(['type' => COMPANY_MANAGER]);
        }

        // For Hr Org
        $hrOrgUser = User::query()->where(User::TYPE, \HR)->first();
        if (!$hrOrgUser) {
            $hrOrgUser = User::factory()->create(['type' => HR]);
        }
        $this->hrOrgUser = $hrOrgUser;
        $hrCom = HrOrganization::query()->where([HrOrganization::USER_ID => $hrOrgUser->id])->first();
        if (!$hrCom) {
            $hrCom = HrOrganization::factory()->create([
                HrOrganization::USER_ID => $hrOrgUser->id,
                HrOrganization::MAIL_ADDRESS => $hrOrgUser->mail_address,
            ]);
        }
        $this->hrOrgCompany = $hrCom;
        // Update all company with status  = 2;
        Company::where('status', '=', 1)->update([
            'status' => 2,
        ]);

    }

    private function fakeDataJob($specifyJobName = null)
    {

        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        $jobSize = 10;

        // Create 10 works
        for ($i = 0; $i <= $jobSize; $i++) {
            $jobTitle = $this->faker->name;
            // Use when you need specify 1 job name to test
            if ($i == $jobSize && $specifyJobName != null) {
                $jobTitle = $specifyJobName;
            }
            $workModel = [
                Work::TITLE => $jobTitle,
                Work::USER_ID => $this->companyUser->id,
                Work::MAJOR_CLASSIFICATION_ID => $jobType->id,
                Work::MIDDLE_CLASSIFICATION_ID => $jobInfo->id,
                Work::COMPANY_ID => $this->company->id,
                Work::APPLICATION_PERIOD_FROM => \Carbon\Carbon::now()->format('Y-m-d'),
                Work::APPLICATION_PERIOD_TO => Carbon::now()->addMonth()->format('Y-m-d'),
                Work::JOB_DESCRIPTION => $this->faker->text,
                Work::APPLICATION_CONDITION => $this->faker->text,
                Work::APPLICATION_REQUIREMENT => $this->faker->text,
                Work::OTHER_SKILL => $this->faker->text(100),
                Work::FORM_OF_EMPLOYMENT => $this->faker->randomElement([WORK_FULL_TIME_EMPLOYEE, WORK_CONTRACT_EMPLOYEE, WORK_TEMPORARY_EMPLOYEE, WORK_OTHER_EMPLOYEE]),
                Work::WORKING_TIME_FROM => Carbon::now()->format('H:i'),
                Work::WORKING_TIME_TO => Carbon::now()->addHour(8)->format('H:i'),
                Work::VACATION => $this->faker->text(100),
                Work::EXPECTED_INCOME => rand(100000, 3000000),
                Work::CITY_ID => 1,
                Work::TREATMENT_WELFARE => $this->faker->text(100),
                Work::BONUS_PAY_RISE => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::OVER_TIME => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::TRANSFER => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::INTERVIEW_FOLLOW => rand(1, 5),
            ];

            $work = Work::factory()->create($workModel);
            $this->addRelationHasMany(LanguageRequirementWork::class, [1, 2, 3],
                $work->id, 'language_requirement_id');

            $this->addRelationHasMany(PassionWork::class, [1, 2, 3], $work->id, 'passion_id');
        }
    }

    private function fakeDataHr($specifyHr = null)
    {
        $sizeHr = 5;
        for ($i = 0; $i <= $sizeHr; $i++) {
            $work_from = $this->faker->randomElement(HRS_WORK_FORM);
            $major_id = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();

            $middle_id = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major_id)->inRandomOrder()->pluck('id')->first();
            $status = HRS_STATUS_PUBLIC;
            $myEmail = 'test@gmail.com'; // To test send email
            $hrFullName = $this->faker->name;
            $hrFullNameJa = $this->faker->name;
            // Use when you need specify 1 hr name to test
            if ($i == $sizeHr && $specifyHr != null) {
                $hrFullName = $specifyHr;
                $hrFullNameJa = $specifyHr . " Katagana";
            }
            $hrModel = [
                HR::HR_ORGANIZATION_ID => $this->hrOrgCompany->id,
                HR::USER_ID => $this->hrOrgUser->id,
                HR::FULL_NAME => $hrFullName,
                HR::FULL_NAME_JA => $hrFullNameJa,
                HR::GENDER => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
                HR::CREATED_BY => $this->hrOrgUser->id,
                HR::DATE_OF_BIRTH => $this->faker->dateTimeBetween('-50 years', '-18 years'),
                HR::FINAL_EDUCATION_DATE => $this->faker->dateTimeBetween('-20 years')->format('Y-m'),
                HR::FINAL_EDUCATION_CLASSIFICATION => $this->faker->randomElement(HR_FINAL_EDUCATION),
                HR::FINAL_EDUCATION_DEGREE => $this->faker->randomElement(array_keys(HR_EDUCATION_DEGREES)),
                HR::WORK_FORM => $work_from,
                HR::PREFERRED_WORKING_HOURS => $work_from == HRS_FULL_TIME_EMPLOYEE ? null : rand(25, 60),
                HR::JAPANESE_LEVEL => LanguageRequirement::query()->inRandomOrder()->pluck('id')->first(),
                HR::MAJOR_CLASSIFICATION_ID => $major_id,
                HR::MIDDLE_CLASSIFICATION_ID => $middle_id,
                HR::MAIL_ADDRESS => $myEmail,
                HR::STATUS => $status
            ];
            $hrToCreate = HR::factory()->create($hrModel);
            HRMainJobCareer::factory()->create([HRMainJobCareer::HRS_ID => $hrToCreate->id]);
        }
    }

    private function _fakeDataCompleteStep1($interviewId)
    {

        $interviewSelectedDate = [
            [
                'date' => '2023-10-02',
                'start_time' => '10:00',
                'start_time_at' => 'AM',
                'expected_time' => '60'
            ]
        ];
        $interviewDate = Carbon::parse('2023-10-02');

        InterviewInfo::create([
            'interview_id' => $interviewId,
            'number' => 1,
            'type' => 2,
            'calendar_interview' => json_encode($interviewSelectedDate),
            'number_selection' => 2,
            'date_interview' => $interviewDate,
            'status' => 6,
            'url_zoom' => 'https://us06web.zoom.us/j/12345678?pwd=abcdefg',
            'id_zoom' => '12345678',
            'password_zoom' => 'abcdefg'
        ]);
        $interview = Interview::find($interviewId);
        $interview->interview_date = $interviewDate;
        $interview->type = 2;
        $interview->status_selection = 5;
        $interview->status_interview_adjustment = 1;
        $interview->display = 'on';
        $interview->step = 3;
        $interview->save();
    }

    private function _fakeDataCompleteSetURLStep2($interviewId)
    {

        $interviewSelectedDate = [
            [
                'date' => '2023-10-02',
                'start_time' => '10:00',
                'start_time_at' => 'AM',
                'expected_time' => '60'
            ]
        ];
        $interviewDate = Carbon::parse('2023-10-02');

        InterviewInfo::create([
            'interview_id' => $interviewId,
            'number' => 2,
            'type' => 2,
            'calendar_interview' => json_encode($interviewSelectedDate),
            'date_interview' => $interviewDate,
            'status' => 5,
            'url_zoom' => 'https://us06web.zoom.us/j/12345678?pwd=abcdefg',
            'id_zoom' => '12345678',
            'password_zoom' => 'abcdefg'
        ]);
        $interview = Interview::find($interviewId);
        $interview->interview_date = $interviewDate;
        $interview->type = 2;
        $interview->status_selection = 5;
        $interview->status_interview_adjustment = 4;
        $interview->display = 'on';
        $interview->step = 3;
        $interview->save();
    }

    private function _fakeDataPassStep2($interviewId)
    {

        $interviewSelectedDate = [
            [
                'date' => '2023-10-02',
                'start_time' => '10:00',
                'start_time_at' => 'AM',
                'expected_time' => '60'
            ]
        ];
        $interviewDate = Carbon::parse('2023-10-02');

        InterviewInfo::create([
            'interview_id' => $interviewId,
            'number' => 2,
            'type' => 2,
            'calendar_interview' => json_encode($interviewSelectedDate),
            'date_interview' => $interviewDate,
            'number_selected' => 5,
            'status' => 6,
            'url_zoom' => 'https://us06web.zoom.us/j/12345678?pwd=abcdefg',
            'id_zoom' => '12345678',
            'password_zoom' => 'abcdefg'
        ]);
        $interview = Interview::find($interviewId);
        $interview->interview_date = $interviewDate;
        $interview->type = 2;
        $interview->status_selection = 6;
        $interview->status_interview_adjustment = 4;
        $interview->display = 'off';
        $interview->step = 3;
        $interview->save();
        // Create Result
        Result::create([
            Result::INTERVIEW_ID => $interview->id,
            Result::HR_ID => $interview->hr_id,
            Result::WORK_ID => $interview->work_id,
            Result::STATUS_SELECTION => 1,
            Result::TIME => '20231101',
            Result::DISPLAY => 'on'
        ]);
    }

    private function _fakeSelectedDateCompleteStep2($interviewId)
    {

        $interviewSelectedDate = [
            [
                'date' => '2023-10-02',
                'start_time' => '10:00',
                'start_time_at' => 'AM',
                'expected_time' => '60'
            ]
        ];
        $interviewDate = Carbon::parse('2023-10-02');

        InterviewInfo::create([
            'interview_id' => $interviewId,
            'number' => 2,
            'type' => 2,
            'calendar_interview' => json_encode($interviewSelectedDate),
            'date_interview' => $interviewDate,
            'status' => 2,
//            'url_zoom' => 'https://us06web.zoom.us/j/12345678?pwd=abcdefg',
//            'id_zoom' => '12345678',
//            'password_zoom' => 'abcdefg'
        ]);
        $interview = Interview::find($interviewId);
        $interview->interview_date = $interviewDate;
        $interview->type = 2;
        $interview->status_selection = 5;
        $interview->status_interview_adjustment = 3;
        $interview->display = 'on';
        $interview->step = 3;
        $interview->save();
    }

    private function fakeDataInterview()
    {

        $works = Work::query()->limit(1)->get();
        $hrs = HR::query()->limit(2)->get();
        $lirst = [];
        foreach ($hrs as $key1 => $hr) {
            foreach ($works as $key2 => $work) {
                $interview = Interview::create([
                    Interview::HR_ID => $hr->id,
                    Interview::WORK_ID => $work->id,
                    Interview::INTERVIEW_DATE => \Carbon\Carbon::now()->toDateString(),
                    Interview::TYPE => ($key2 % 2) == 0 ? INTERVIEW_TYPE_GROUP : INTERVIEW_TYPE_PRIVATE,
                    Interview::STATUS_SELECTION => ($key2 % 2) == 0 ? INTERVIEW_STATUS_SELECTION_DOC_PASS : INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM,
                    Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT,
                    Interview::REMARKS => '',
                    Interview::DISPLAY => 'on',
                    Interview::STEP => INTERVIEW_TABLE_STEP_INTERVIEW
                ]);

            }
        }

    }

    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach ($items as $k => $item) {
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }

}
