<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemindOfferJob extends RemindJob
{
    protected $offer_id;
    protected $hr_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($offer_id = null, $hr_id = null)
    {
        $this->offer_id = $offer_id;
        $this->hr_id = $hr_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if($this->offer_id && $this->hr_id) {
                $listOfferRemind = Offer::query()
                    ->where('id', $this->offer_id)
                    ->where(Offer::STATUS, OFFER_STATUS_REQUESTING)
                    ->get();
            } else {
                $listOfferRemind = Offer::query()
                    ->where(Offer::STATUS, OFFER_STATUS_REQUESTING)
                    ->whereDate(Offer::UPDATED_AT, '<=', Carbon::now()->subDay(5))
                    ->where(Offer::DISPLAY, 'on')
                    ->get();
            }
            foreach ($listOfferRemind as $offer)
            {
                $user = $offer->hr->user;
                $users = User::query()->whereIn(User::TYPE, [SUPER_ADMIN, HR_MANAGER])->orWhere('id', $user->id)->get();
                foreach ($users as $user) {
                    $data['email']  = @$user->mail_address;
                    $data['job']    = @$offer->work->title;
                    $data['company']= @$offer->work->company->company_name_jp;
                    $data['full_name']= @$offer->hr->full_name;
                    $data['full_name_ja']= @$offer->hr->full_name_ja;
                    $data['date']= Carbon::now()->format('Y/m/d');
                    $partViewWeb = 'messages.entry-offer.offer';
                    $subjectEmail = trans('messages.mes.subject_offer_stagnant_status');
                    $viewEmail = 'email.entry-offer.offer';
                    $this->send($user, $data, $partViewWeb, $subjectEmail, $viewEmail);
                }
            }

        }catch (\Exception $exception){
            Log::error($exception);
        }
    }
}
