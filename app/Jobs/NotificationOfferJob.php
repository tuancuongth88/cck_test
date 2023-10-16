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
                case OFFER_STATUS_REQUESTING :
                    $this->sendNotificationOfferStatusRequesting($company, $hrOrg, $this->offer, $this->status, $this->note);
                    break;
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
            $this->sendNotification($user, $company, $hrOrg, $offer, self::TYPE_NOTIFY_OFFER_DECLINE);
        }
    }

    //send noti HR change status TYPE_NOTIFY_offer_REJECTION = 4
    private function sendNotificationOfferStatusConfirm($company, $hrOrg, $offer, $status, $note)
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
            $this->sendNotification($user, $company, $hrOrg, $offer, self::TYPE_NOTIFY_OFFER_CONFIRM);
        }
    }

    private function sendNotificationOfferStatusRequesting($company, $hrOrg, $offer, $status, $note)
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
            $this->sendNotification($user, $company, $hrOrg, $offer, self::TYPE_NOTIFY_OFFER_CONFIRM);
        }
    }
    private function sendNotification($user, $company, $offer, $typeNoti, $listNameJa = [])
    {
        $data['permission'] = User::getPermissionName($user);
        $data['type'] = $user->type;
        $data['type_noti'] = NOTI_TYPE_OFFER;
        $data['company'] = $company->company_name;
        $data['entry_code'] = null;
        $data['job'] = $offer->work->title;
        $data['full_name_ja'] = $offer->hr->full_name_ja;
        $data['full_name'] = $offer->hr->full_name;
        $data['date'] = Carbon::now()->format('Y/m/d');
        $data['dataNameHr'] = $listNameJa;
        $data['status'] = OFFER_STATUS_TEXTS[$offer->status];
        $data['job_id'] = $offer->work_id;
        $data['company_id'] = $company->id;
        $data['hrs_id'] = $offer->hr_id;
        $this->send($user, $data, null, null, null, $typeNoti);
    }
}
