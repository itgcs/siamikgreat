<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use Illuminate\Console\Command;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ReminderSevenDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
     

    protected $signature = 'reminderSevenDays:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder h-7 payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Reminder h-7 Job running at ". now());

        $bill = new MailController;
    }
}
