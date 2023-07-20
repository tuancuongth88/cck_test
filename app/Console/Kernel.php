<?php

namespace App\Console;

use App\Jobs\AutoUpdateStatusWorkJob;
use App\Jobs\RemindAccountJob;
use App\Jobs\RemindEntryJob;
use App\Jobs\RemindInterViewJob;
use App\Jobs\RemindOfferJob;
use App\Jobs\RemindResultJob;
use App\Models\Company;
use App\Models\HrOrganization;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
//        $schedule->command('service:update-value-future-date')->daily()->withoutOverlapping();
        $schedule->job(new RemindAccountJob(HR_MANAGER, HrOrganization::class))->weeklyOn(1, '0:00');
        $schedule->job(new RemindAccountJob(COMPANY, Company::class))->weeklyOn(1, '0:00');
        $schedule->job(new RemindEntryJob())->weeklyOn(1, '0:00');
        $schedule->job(new RemindInterViewJob())->weeklyOn(1, '0:00');
        $schedule->job(new RemindOfferJob())->weeklyOn(1, '0:00');
        $schedule->job(new AutoUpdateStatusWorkJob())->dailyAt('0:00');
        $schedule->job(new RemindResultJob())->dailyAt('0:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
