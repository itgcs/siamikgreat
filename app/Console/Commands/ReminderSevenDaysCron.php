<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use Illuminate\Console\Command;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ReminderSevenDaysCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
     

    protected $signature = 'reminderPastDueMinusSevenDays:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder past due h-7 payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Reminder h-7 Job running at ". now());

        $bill = new MailController;
        $bill->cronReminderMinusSevenDay('SPP');
        $bill->cronReminderMinusSevenDay('Uang Gedung');
        // $bill->cronReminderBook');
        // $bill->cronReminderMinusSevenDay('Uniform');
    }
}
