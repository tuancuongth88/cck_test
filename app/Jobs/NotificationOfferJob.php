<?php

namespace App\Jobs;

use App\Models\Interview;
use App\Models\Result;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationOfferJob extends RemindJob
{

    protected $user;
    protected $offer;
    protected $status;
    protected $note;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $offer, $status, $note, $data = null)
    {
        $this->user = $user;
        $this->offer = $offer;
        $this->status = $status;
        $this->note = $note;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company = $this->offer->work->company;
        $hrOrg = $this->offer->hr->hrOrg;
        try {
            switch ($this->status) {
                case OFFER_STATUS_DECLINE :
                    $this->sendNotificationOfferStatusDecline($company, $hrOrg, $this->offer, $this->status, $this->note);
                    break;
                case OFFER_STATUS_CONFIRM :
                    $this->sendNotificationOfferStatusConfirm($company, $hrOrg, $this->offer, $this->status, $this->note);
                    break;
                default:
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    //send noti HR change status TYPE_NOTIFY_offer_DECLINE = 3
    private function sendNotificationOfferStatusDecline($company, $hrOrg, $offer, $status, $note)
    {
        $partView = $this->partViewRemind(18);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $title = 'オファー承認可否が設定されました';
        $role = 0;
        $users = User::query()->whereHas('company', function ($q) use ($company) {
            $q->where('id', $company->id);
        })->get();
        foreach ($users as $user) {
            $data['title'] = $title;
            $data['text'] = $note;
            $data['company'] = $user->company->company_name_jp;
            $data['content'] = self::getContent(17);
            $data['offer_code'] = $offer->offer_code;
            $data['job'] = $offer->work->title;
            $data['full_name_ja'] = $offer->hr->full_name_ja;
            $data['date'] = Carbon::now()->format('Y/m/d');
            $data['nameTable1'] = 'オファー求人情報';
            $data['nameTable2'] = 'オファー承認可否';
            $data['status'] = OFFER_STATUS_TEXTS[$offer->status];
            $this->send($user, $data, $partView['web'], $subject, '', self::TYPE_NOTIFY_OFFER_DECLINE);
        }
    }

    //send noti HR change status TYPE_NOTIFY_offer_REJECTION = 4
    private function sendNotificationOfferStatusConfirm($company, $hrOrg, $offer, $status, $note)
    {
        $partView = $this->partViewRemind(19);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $title = 'オファー承認可否が設定されました';
        $role = 0;
        $users = User::query()->whereHas('company', function ($q) use ($company) {
            $q->where('id', $company->id);
        })->get();
        foreach ($users as $user) {
            $data['title'] = $title;
            $data['text'] = $note;
            $data['company'] = $user->company->company_name_jp;;
            $data['content'] = self::getContent(18 );
            $data['offer_code'] = $offer->offer_code;
            $data['job'] = $offer->work->title;
            $data['full_name_ja'] = $offer->hr->full_name_ja;
            $data['nameTable1'] = 'オファー求人情報';
            $data['nameTable2'] = 'オファー承認可否';
            $data['status'] = OFFER_STATUS_TEXTS[$offer->status];
            $data['date'] = Carbon::now()->format('Y/m/d');
            $this->send($user, $data, $partView['web'], $subject, '', self::TYPE_NOTIFY_OFFER_CONFIRM);
        }
    }
    private function getListNameJa($data)
    {
        $listName = [];
        foreach ($data as $value) {
            $listName[] = $value->hr->full_name_ja;
        }
        return $listName;
    }
}
