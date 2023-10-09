<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\BillController;
use App\Jobs\SendMailReminder;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SppMail;
use App\Models\Bill;
use Exception;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\DB;

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

      $create_spp = new BillController;
      $create_spp->cronCreateSpp();
    }
}