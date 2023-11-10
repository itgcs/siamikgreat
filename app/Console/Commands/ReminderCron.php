<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use Illuminate\Console\Command;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'cronReminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder past due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Reminder h-1 Job running at ". now());

        $bill = new MailController;
        $bill->cronReminder('SPP');
        // $bill->cronReminderMinusOneDay('Uang Gedung');
        // $bill->cronReminderMinusOneDay('Book');
        // $bill->cronReminderMinusOneDay('Uniform');
    }
}
