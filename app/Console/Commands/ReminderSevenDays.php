<?php

namespace App\Console\Commands;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
    }
}
