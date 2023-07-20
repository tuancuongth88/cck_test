<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\RemindAccountNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RemindAccountJob extends RemindJob
{

    protected $type;
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $model)
    {
        $this->type = $type;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $object = $this->model::query()
                ->where('status', EXAMINATION_PENDING)
                ->whereDate('updated_at', '<=', Carbon::now()->subDay(5))
                ->get();
            if (count($object)) {
                $users = User::query()->whereIn('type', [SUPER_ADMIN, $this->type])->get();
                foreach ($users as $user){
                    $data['email'] = $user->mail_address;
                    $data['date'] = Carbon::now()->format('Y/m/d');
                    $data['contentHTML'] = $this->getView($data, $this->getPartView());
                    $data['subject'] = trans('messages.account_subject_examination_email',
                        ['type' => $this->type == COMPANY ? trans('messages.account_type_company'):
                            trans('messages.account_type_hr')]);
                    $data['viewEmail'] = $this->type == COMPANY ? 'email.account-examination.company': 'email.account-examination.hr';
                    $this->send($user, $data, $this->getPartView(), $data['subject'], $data['viewEmail']);
                }
            }
        }catch (\Exception $exception){
            Log::error($exception);
        }
    }

    public function getPartView(){
        if ($this->type == COMPANY){
            return'messages.account-examination.company';
        }else{
            return'messages.account-examination.hr';
        }
    }
}
