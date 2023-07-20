<?php

namespace App\Jobs;

use App\Models\Interview;
use App\Models\Result;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationDistributionJob extends RemindJob
{

    private $user;
    private $title;
    private $text;
    private $urlImage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user = null, $title, $text, $image)
    {
        $this->user = $user;
        $this->title = $title;
        $this->text = $text;
        $this->urlImage = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $partView = $this->partViewRemind(13);
            $subject = $this->getSubject(self::TYPE_NOTIFY_DISTRIBUTION);
            $role = 0;
            $users = User::query()->get();
            foreach ($users as $user) {
                $data['title'] = $this->title;
                $data['text'] = $this->text;
                $data['image'] = $this->urlImage;
                $data['date'] = Carbon::now()->format('Y/m/d');
                $this->send($user, $data, $partView['web'], $subject, '',self::TYPE_NOTIFY_DISTRIBUTION);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * Get all data send Notification
     * @param int $type
     * @return collection
     */
    private function getData($type)
    {
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
            ->where(Interview::INTERVIEW_ADJUSTMENT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT)
            ->where(Interview::DISPLAY, 'on')
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
            ->where(function ($query) {
                $query->where('status_selection', INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM)
                    ->orWhere('status_selection', INTERVIEW_STATUS_SELECTION_DOC_PASS)
                    ->where(Interview::INTERVIEW_ADJUSTMENT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT);
            })
            ->where(Interview::DISPLAY, 'on')
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
            ->where(Interview::DISPLAY, 'on')
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
            ->where(Interview::DISPLAY, 'on')
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
