<?php

namespace App\Jobs;

use App\Models\Interview;
use App\Models\Result;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class RemindInterViewJob extends RemindJob
{

    private $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            self::start();
        }catch (\Exception $exception)
        {
            Log::error($exception);
        }
    }

    public function start(){
        for($i = 5; $i < 13; $i++){
            $this->sendNotify($i);
        }
    }

    public function sendNotify($type){
        $partView = $this->partViewRemind($type);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $content = $this->getContent($type);
        $getData = self::getData($type);
        $role = 0;
        if (in_array($type, [5, 6, 8, 10])){
            $role = COMPANY_MANAGER;
        }
        if (in_array($type, [7, 11]))
        {
            $role = HR_MANAGER;
        }
        foreach ($getData as $value)
        {
            $user = $value->work->user;
            $users = User::query()->whereIn(User::TYPE, [$role, SUPER_ADMIN])
                ->orWhere('id', $user->id)->get();
            foreach ($users as $user) {
                $data['entry_code'] = @$value->code;
                $data['email'] = @$user->mail_address;
                $data['job'] = @$value->work->title;
                $data['company'] = $role == COMPANY_MANAGER ? @$value->work->company->company_name_jp : $value->hr->hrOrg->corporate_name_ja;
                $data['full_name_ja'] = @$value->hr->full_name_ja;
                $data['date'] = Carbon::now()->format('Y/m/d');
                $data['content'] = $content;
                $data['status'] = $value->status;
                $data['interview_or_group_interview'] = $value->type == INTERVIEW_TYPE_GROUP ? '集団面接' :'個別面接';
                $data['interview_date'] = $value->interview_date;
                $this->send($user, $data, $partView['web'], $subject, $partView['email']);
            }
        }
    }


    /**
     * Get all data send Notification
     * @param  int  $type
     * @return collection
     */
    private function getData($type){
        $methodMap = [
            5 => 'getDataInterviewDateConfirmOffer',
            6 => 'getDataInterViewDateConfirmEntry',
            7 => 'getDataAnswerInterviewAdjustmentAvailability',
            8 => 'getDataAnswerInterviewAdjustmentAvailability',
            9 => 'getDataIssueInterviewURL',
            10 => 'getDataJudgePassFailInterview',
            11 => 'getDataDetermineAcceptanceOfOfficialOffer',
            12 => 'getDataSetHireDateForOfficialOffer',
        ];

        if (isset($methodMap[$type])) {
            $methodName = $methodMap[$type];
            return $this->$methodName();
        } else {
            throw new InvalidArgumentException("Invalid type '$type'");
        }
    }


    /**
     * 5 => Notify Company
     * Set a interview date for an confirmed offer
     * @return Collection
     *
     */
    public function getDataInterviewDateConfirmOffer()
    {
        return Interview::query()
        ->where('status_selection', INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM)
        ->where(Interview::INTERVIEW_ADJUSTMENT,INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT)
        ->where(Interview::DISPLAY,'on')
        ->whereDate(Interview::UPDATED_AT, '<=', Carbon::now()->subDays(5))
        ->get();
    }

    /**
     * 6 => Notify Company
     * Set a interview date for an confirmed entry
     * @return Collection
     */
    public function getDataInterViewDateConfirmEntry()
    {
        return Interview::query()
            ->where('status_selection', INTERVIEW_STATUS_SELECTION_DOC_PASS)
            ->where(Interview::INTERVIEW_ADJUSTMENT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT)
            ->whereDate(Interview::UPDATED_AT, '<=', Carbon::now()->subDays(5))
            ->get();
    }

    /**
     * 7, 8 Notify Company, HR
     * Answer whether or not adjustment is possible for set interview
     * @return Collection
     */
    public function getDataAnswerInterviewAdjustmentAvailability()
    {
        return Interview::query()
            ->where(function($query) {
                $query->where('status_selection', INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM)
                    ->orWhere('status_selection', INTERVIEW_STATUS_SELECTION_DOC_PASS)
                    ->where(Interview::INTERVIEW_ADJUSTMENT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT);
            })
            ->where(Interview::DISPLAY,'on')
            ->whereDate(Interview::UPDATED_AT, '<=', Carbon::now()->subDays(5))
            ->get();
    }


    /**
     * 9 => Supper Admin
     * Issue an interview URL for the set interview
     * @return Collection
     */
    public function getDataIssueInterviewURL()
    {
        return Interview::query()
            ->where(Interview::INTERVIEW_ADJUSTMENT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING)
            ->where(Interview::DISPLAY,'on')
            ->whereDate(Interview::UPDATED_AT, '<=', Carbon::now()->subDays(5))
            ->get();
    }

    /**
     * 10 => Company
     * Judge pass/fail for an interview
     * @return Collection
     */
    public function getDataJudgePassFailInterview()
    {
        return Interview::query()
            ->where('status_interview_adjustment', INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT)
            ->where(Interview::DISPLAY,'on')
            ->whereDate(Interview::UPDATED_AT, '<=', Carbon::now()->subDays(5))
            ->get();
    }

    /**
     * 11 => HR organization
     * Determine whether to accept or decline the requested official offer
     * @return Collection
     */
    public function getDataDetermineAcceptanceOfOfficialOffer()
    {
        return Result::query()->where(Result::STATUS_SELECTION, RESULT_STATUS_SELECTION_OFFER)
            ->whereDate(Result::UPDATED_AT, '<=', Carbon::now()->subDays(5))
            ->get();
    }

    /**
     * 12 => Supper Admin
     * Set the hire date for the official offer
     * @return Collection
     */
    public function getDataSetHireDateForOfficialOffer()
    {
        return Result::query()->where(Result::STATUS_SELECTION, RESULT_STATUS_SELECTION_OFFER_CONFIRM)
            ->whereDate(Result::UPDATED_AT, '<=', Carbon::now()->subDays(5))
            ->get();
    }

}
