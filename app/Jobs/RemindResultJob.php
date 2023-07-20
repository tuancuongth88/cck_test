<?php

namespace App\Jobs;

use App\Models\Result;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemindResultJob extends RemindJob
{

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $results = Result::query()->where(Result::STATUS_SELECTION, RESULT_STATUS_SELECTION_OFFER)
            ->where(Result::TIME, '<', Carbon::now()->format('Ymd'))
            ->where('time', '<>', -1)
            ->get();
        foreach ($results as $result){
            $result->time = -1;
            $result->save();
            $this->sendNotify($result);
        }
    }

    private function sendNotify($result){
        try {
            $user = $result->hr->user;
            $users = User::query()->whereIn(User::TYPE, [SUPER_ADMIN, HR_MANAGER])->orWhere('id', $user->id)->get();
            foreach ($users as $user) {
                $data['title']  =trans('messages.mes.official_offer_has_expired');
                $data['email']  = @$user->mail_address;
                $data['job']    = @$result->work->title;
                $data['company']= @$result->work->company->company_name_jp;
                $data['full_name']= @$result->hr->full_name;
                $data['full_name_ja']= @$result->hr->full_name_ja;
                $data['date']= Carbon::now()->format('Y/m/d');
                $data['content'] = self::getContent(19);
                $data['entry_code'] = $result->code;
                $data['status'] = RESULT_STATUS_LIST [$result->status_selection];
                $data['interview_date'] = @$result->interview->interview_date;
                $data['interview_or_group_interview'] = @$result->interview->type == INTERVIEW_TYPE_GROUP ? '集団面接' :'個別面接';
                $partViewWeb = 'messages.remind.format_e';
                $subjectEmail = trans('messages.mes.official_offer_has_expired');
                $viewEmail = 'email.remind.format_e';
                $this->send($user, $data, $partViewWeb, $subjectEmail, $viewEmail);
            }
        }catch (\Exception $e){
            Log::error($e);
        }
    }
}
