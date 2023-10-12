<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use Illuminate\Console\Command;
use Exception;

class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check reminder spp payment daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Reminder Job running at ". now());

        $bill = new MailController;
        $bill->cronReminderPastDue('SPP');
    }
}