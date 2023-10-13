<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        date_default_timezone_set('Asia/Jakarta');
        $schedule->command('spp:cron')->monthlyOn(5, '8:00')->timezone('Asia/Jakarta');
        $schedule->command('reminderPastDueMinusOneDays:cron')->dailyAt('15:00')->timezone('Asia/Jakarta');
        $schedule->command('reminderPastDueMinusSevenDays:cron')->dailyAt('10:40')->timezone('Asia/Jakarta');
        $schedule->command('reminderPastDue:cron')->dailyAt('17:00')->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}