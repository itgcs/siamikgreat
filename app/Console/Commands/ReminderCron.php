<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
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
    protected $description = 'Check reminder spp or other payment daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Reminder Job running at ". now());

        try {

            $data = Bill::with(['student' => function($query) {
                $query->with('relationship');
             }])->whereHas('student', function($query){
                $query->where('is_active', true);
             })->whereDate('deadline_invoice', '<', date('Y-m-d'))->orderBy('id', 'asc')->get();
                
            foreach($data as $el) 
            {
                if($el->student && sizeof($el->student->relationship)<=0)
                {
                    foreach($el->student->relationship as $item)
                    {
                        
                    }
                }
            }
        } catch (Exception $err) {
            info("Reminder Job Error: " . $err);
        }
    }
}