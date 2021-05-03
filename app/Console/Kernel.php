<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Schema;
use Modules\Announcement\Models\Announcement;
use Modules\Setting\Models\Setting;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\WordOfTheDay::class,
        Commands\DatabaseBackupCustom::class,
        Commands\EstimateDueReminder::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (file_exists(storage_path('installed'))) {
            if (Schema::hasTable(config('core.acl.users_settings_table'))) {
                $setting = Setting::first();
                if (!empty($setting) && $setting->active_cronjob) {

                    // Send mails in queue
                    $schedule->command('queue:restart')->everyFiveMinutes();
                    $schedule->command('queue:work --tries=3')
                        ->everyMinute()
                        ->withoutOverlapping();

                    // Daily send task report mail
                    $schedule->command('work:day')->dailyAt('20:00');

                    // --
                    // Backup database
                    if ($setting->automatic_backup) {
                        $schedule->command('db:backup-custom')->weeklyOn(1, '02:00');
                    }

                    // Send Estimate Expiration Reminder Email to Customer.
                    $schedule->command('estimate:due-reminder')->dailyAt('01:00');

                    // Send Invoice Expiration Reminder Email to Customer.
                    $schedule->command('invoice:due-reminder')->dailyAt('01:05');

                    // Completed overdue announcement.
                    $schedule->call(function () {
                        $currnetDate = date("Y-m-d");
                        $matchThese = [['end_date','<',$currnetDate],['status','!=','2']];
                        $Announcement = Announcement::where($matchThese)->update(['status' => 2]);
                    })->dailyAt('01:10');

                    $setting->last_cronjob_run = time();
                    $setting->save();
                }
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        include base_path('routes/console.php');
    }
}
