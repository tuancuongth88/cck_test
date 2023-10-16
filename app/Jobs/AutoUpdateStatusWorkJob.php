<?php

namespace App\Jobs;

use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoUpdateStatusWorkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $today = Carbon::now()->format('Y-m-d');
        Work::query()
            ->whereDate(Work::APPLICATION_PERIOD_TO, '<', $today)
            ->update([Work::STATUS => WORK_STATUS_OUT_OF_RECRUITMENT_PERIOD]);

        //update status recruting
        Work::query()->whereDate(Work::APPLICATION_PERIOD_FROM, '<=', $today)
            ->where(Work::APPLICATION_PERIOD_TO, '>=', $today)
            ->update([Work::STATUS => WORK_STATUS_RECRUITING]);
    }
}
