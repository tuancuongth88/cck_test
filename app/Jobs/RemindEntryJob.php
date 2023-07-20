<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\Entry;
use App\Models\User;
use App\Models\Work;
use App\Notifications\RemindAccountNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RemindEntryJob extends RemindJob
{

    public $entryCode;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($entryCode = null)
    {
        $this->entryCode = $entryCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->entryCode){
                $listEntryRemind = Entry::query()
                    ->where('entries.'.Entry::STATUS, ENTRY_STATUS_REQUESTING)
                    ->where(Entry::ENTRY_CODE, $this->entryCode)
                    ->get();
            }else{
                $listEntryRemind = Entry::query()
                    ->where('entries.'.Entry::STATUS, ENTRY_STATUS_REQUESTING)
                    ->whereDate(Entry::REQUEST_DATE, '<=', Carbon::now()->subDays(5))
                    ->get();
            }
            foreach ($listEntryRemind as $entry)
            {
                $user = $entry->work->user;
                $users = User::query()->whereIn(User::TYPE, [COMPANY_MANAGER, SUPER_ADMIN])
                    ->orWhere('id', $user->id)->get();
                foreach ($users as $user){
                    $data['entry_code']  = @$entry->code;
                    $data['email']  = @$user->mail_address;
                    $data['job']    = @$entry->work->title;
                    $data['company']= @$entry->work->company->company_name_jp;
                    $data['full_name_ja']= @$entry->hr->full_name_ja;
                    $data['date']= Carbon::now()->format('Y/m/d');
                    $partViewWeb = 'messages.entry-offer.remind-entry';
                    $subjectEmail = trans('messages.mes.subject_entry_stagnant_status');
                    $viewEmail = 'email.entry-offer.remind-entry';
                    $this->send($user, $data, $partViewWeb, $subjectEmail, $viewEmail);
                }
            }

        }catch (\Exception $exception){
            Log::error($exception);
        }
    }
}
