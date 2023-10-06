<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
         date_default_timezone_set('Asia/Jakarta');
         info("Cron Job running at ". now());

         
    }
}