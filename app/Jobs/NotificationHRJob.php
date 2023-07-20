<?php

namespace App\Jobs;

use App\Models\Interview;
use App\Models\Result;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationHRJob extends RemindJob
{

    protected $user;
    protected $entry;
    protected $status;
    protected $note;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $entry, $status, $note, $data = null)
    {
        $this->user = $user;
        $this->entry = $entry;
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
        $company = $this->entry->work->company;
        $hrOrg = $this->entry->hr->hrOrg;
        try {
            switch ($this->status) {
                case ENTRY_STATUS_REQUESTING :
                    $this->sendNotificationEntryStatusRequesting($company, $hrOrg, $this->entry, $this->status, $this->note, $this->data);
                    break;
                case ENTRY_STATUS_DECLINE :
                    $this->sendNotificationEntryStatusDecline($company, $hrOrg, $this->entry, $this->status, $this->note);
                    break;
                case ENTRY_STATUS_REJECT :
                    $this->sendNotificationEntryStatusReject($company, $hrOrg, $this->entry, $this->status, $this->note);
                    break;
                case ENTRY_STATUS_CONFIRM :
                    $this->sendNotificationEntryStatusConfirm($company, $hrOrg, $this->entry, $this->status, $this->note);
                    break;
                default:
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    private function sendNotificationEntryStatusRequesting($company, $hrOrg, $entry, $status, $note, $data)
    {

        $partView = $this->partViewRemind(17);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $title = 'エントリーが実行されました';
        $role = 0;
        $listNameJa = $this->getListNameJa($data);
        $users = User::query()->whereHas('company', function ($q) use ($company) {
            $q->where('id', $company->id);
        })->get();
        foreach ($users as $user) {
            $data['title'] = $title;
            $data['text'] = $note;
            $data['company'] = $user->company->company_name_jp;
            $data['content'] = self::getContent(16);
            $data['entry_code'] = $entry->code;
            $data['job'] = $entry->work->title;
            $data['full_name_ja'] = $entry->hr->full_name_ja;
            $data['date'] = Carbon::now()->format('Y/m/d');
            $data['data'] = $listNameJa;
            $this->send($user, $data, $partView['web'], $subject, '', self::TYPE_NOTIFY_ENTRY_REQUESTING);
        }

    }

    //send noti HR change status TYPE_NOTIFY_ENTRY_DECLINE = 3
    private function sendNotificationEntryStatusDecline($company, $hrOrg, $entry, $status, $note)
    {
        $partView = $this->partViewRemind(14);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $title = 'エントリー辞退が設定されました';
        $role = 0;
        $users = User::query()->whereHas('company', function ($q) use ($company) {
            $q->where('id', $company->id);
        })->get();
        foreach ($users as $user) {
            $data['title'] = $title;
            $data['text'] = $note;
            $data['company'] = $user->company->company_name_jp;
            $data['content'] = self::getContent(13);
            $data['entry_code'] = $entry->code;
            $data['job'] = $entry->work->title;
            $data['full_name_ja'] = $entry->hr->full_name_ja;
            $data['date'] = Carbon::now()->format('Y/m/d');
            $this->send($user, $data, $partView['web'], $subject, '', self::TYPE_NOTIFY_ENTRY_DECLINE);
        }
    }

    //send noti HR change status TYPE_NOTIFY_ENTRY_REJECTION = 2

    private function sendNotificationEntryStatusReject($company, $hrOrg, $entry, $status, $note)
    {
        $partView = $this->partViewRemind(15);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $title = '海外人材マッチングシステム(仮)';
        $role = 0;
        $users = User::query()->get();

        $users = User::query()->whereHas('hrOrganization', function ($q) use ($hrOrg) {
            $q->where('id', $hrOrg->id);
        })->get();
        foreach ($users as $user) {
            $data['title'] = $title;
            $data['text'] = $note;
            $data['company'] = $user->hrOrganization->corporate_name_ja;
            $data['content'] = self::getContent(14);
            $data['entry_code'] = $entry->code;
            $data['job'] = $entry->work->title;
            $data['full_name_ja'] = $entry->hr->full_name_ja;
            $data['status'] = ENTRY_STATUS_TEXTS[$entry->status];
            $data['date'] = Carbon::now()->format('Y/m/d');
            $this->send($user, $data, $partView['web'], $subject, '', self::TYPE_NOTIFY_ENTRY_REJECTION);
        }
    }

    //send noti HR change status TYPE_NOTIFY_ENTRY_REJECTION = 4
    private function sendNotificationEntryStatusConfirm($company, $hrOrg, $entry, $status, $note)
    {
        $partView = $this->partViewRemind(16);
        $subject = $this->getSubject(self::TYPE_NOTIFY_REMIND_ACCOUNT);
        $title = 'エントリー承認可否が設定されました';
        $role = 0;
        $users = User::query()->whereHas('hrOrganization', function ($q) use ($hrOrg) {
            $q->where('id', $hrOrg->id);
        })->get();
        foreach ($users as $user) {
            $data['title'] = $title;
            $data['text'] = $note;
            $data['company'] = $user->hrOrganization->corporate_name_ja;
            $data['content'] = self::getContent(15);
            $data['entry_code'] = $entry->code;
            $data['job'] = $entry->work->title;
            $data['full_name_ja'] = $entry->hr->full_name_ja;
            $data['status'] = ENTRY_STATUS_TEXTS[$entry->status];
            $data['date'] = Carbon::now()->format('Y/m/d');
            $this->send($user, $data, $partView['web'], $subject, '', self::TYPE_NOTIFY_ENTRY_CONFIRM);
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
