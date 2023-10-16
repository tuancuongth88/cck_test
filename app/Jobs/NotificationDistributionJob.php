<?php

namespace App\Jobs;

use App\Models\Interview;
use App\Models\Result;
use App\Models\User;
use App\Notifications\DistributionNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationDistributionJob extends RemindJob
{

    private $authLogin;
    private $title;
    private $text;
    private $urlImage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $authLogin = null, $title, $text, $image)
    {
        $this->authLogin = $authLogin;
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
//            $partView = $this->partViewRemind(13);
//            $subject = 'リマインドのお知らせ';
//            $role = 0;
            $users = User::query()->get();
            foreach ($users as $user) {
                $data['title'] = $this->title;
                $data['text'] = $this->text;
                $data['image'] = $this->urlImage;
                $data['date'] = Carbon::now()->format('Y/m/d');
                $userNoti = $this->authLogin->notifications()->where('type',DistributionNotification::class)->latest()->first();
                if ($userNoti){
                    $userNoti->markAsRead();
                }
                $this->send($user, $data, null, null, null,self::TYPE_NOTIFY_DISTRIBUTION);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

}
