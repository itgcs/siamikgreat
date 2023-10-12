<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\MailController;
use Illuminate\Console\Command;

class CreateSppCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spp:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create spp every month';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
       
      info("Spp Job running at ". now());

      $create_spp = new MailController;
      $create_spp->cronCreateSpp();
    }
}