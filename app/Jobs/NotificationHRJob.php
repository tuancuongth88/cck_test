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
        $listNameJa = $this->getListNameJa($data);
        $users = User::query()->where(function ($query) use ($company, $hrOrg) {
            $query->whereIn('type', [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])
                ->orWhereHas('company', function ($q) use ($company) {
                    $q->where('id', $company->id);
                })
                ->orWhereHas('hrOrganization', function ($q) use ($hrOrg) {
                    $q->where('id', $hrOrg->id);
                });
        })->get();
        foreach ($users as $user) {
            $this->sendNotification($user,$company, $hrOrg, $entry,self::TYPE_NOTIFY_ENTRY_REQUESTING,$listNameJa);
        }
    }

    //send noti HR change status TYPE_NOTIFY_ENTRY_DECLINE = 3
    private function sendNotificationEntryStatusDecline($company, $hrOrg, $entry, $status, $note)
    {
        $users = User::query()->where(function ($query) use ($company, $hrOrg) {
            $query->whereIn('type', [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])
                ->orWhereHas('company', function ($q) use ($company) {
                    $q->where('id', $company->id);
                })
                ->orWhereHas('hrOrganization', function ($q) use ($hrOrg) {
                    $q->where('id', $hrOrg->id);
                });
        })->get();
        foreach ($users as $user) {
            $this->sendNotification($user,$company, $hrOrg, $entry,self::TYPE_NOTIFY_ENTRY_DECLINE);
        }
    }

    //send noti HR change status TYPE_NOTIFY_ENTRY_REJECTION = 2

    private function sendNotificationEntryStatusReject($company, $hrOrg, $entry, $status, $note)
    {
        $users = User::query()->where(function ($query) use ($hrOrg, $company) {
            $query->whereIn('type', [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])
                ->orWhereHas('company', function ($q) use ($company) {
                    $q->where('id', $company->id);
                })
                ->orWhereHas('hrOrganization', function ($q) use ($hrOrg) {
                    $q->where('id', $hrOrg->id);
                });
        })->get();
        foreach ($users as $user) {
            $this->sendNotification($user,$company, $hrOrg, $entry,self::TYPE_NOTIFY_ENTRY_REJECTION);
        }
    }

    //send noti HR change status TYPE_NOTIFY_ENTRY_REJECTION = 4
    private function sendNotificationEntryStatusConfirm($company, $hrOrg, $entry, $status, $note)
    {
        $users = User::query()->where(function ($query) use ($hrOrg, $company) {
            $query->whereIn('type', [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])
                ->orWhereHas('hrOrganization', function ($q) use ($hrOrg) {
                    $q->where('id', $hrOrg->id);
                })
                ->orWhereHas('company', function ($q) use ($company) {
                    $q->where('id', $company->id);
                });
        })->get();
        foreach ($users as $user) {
            $this->sendNotification($user,$company, $hrOrg, $entry,self::TYPE_NOTIFY_ENTRY_CONFIRM);
        }
    }

    private function sendNotification($user,$company, $hrOrg, $entry,$typeNoti,$listNameJa = []){
        $data['permission'] = User::getPermissionName($user);
        $data['type'] = $user->type;
        $data['type_noti'] = NOTI_TYPE_ENTRY;
        $data['company'] = $company->company_name;
        $data['entry_code'] = $entry->code;
        $data['job'] = $entry->work->title;
        $data['full_name_ja'] = $entry->hr->full_name_ja;
        $data['full_name'] = $entry->hr->full_name;
        $data['date'] = Carbon::now()->format('Y/m/d');
        $data['status'] = $entry->status;
        $data['status_text'] = ENTRY_STATUS_TEXTS[$entry->status];
        $data['dataNameHr'] = $listNameJa;
        $data['job_id'] = $entry->work_id;
        $data['company_id'] = $company->id;
        $data['hrs_id'] = $entry->hr_id;
        $this->send($user, $data, null, null, null, $typeNoti);

    }
    private function getListNameJa($data)
    {
        $listName = [];
        foreach ($data as $value) {
            $listName[] = [
                'hrs_id' =>  $value->hr->id,
                'full_name_ja' =>  $value->hr->full_name_ja,
                'full_name' =>  $value->hr->full_name,
            ];
        }
        return $listName;
    }
}
