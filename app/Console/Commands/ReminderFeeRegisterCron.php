<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use Illuminate\Console\Command;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderFeeRegisterCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'reminderFeeRegister:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mail = new MailController();
        $mail->createNotificationFeeRegister();
    }
}
