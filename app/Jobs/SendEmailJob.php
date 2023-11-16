<?php
  
namespace App\Jobs;

use App\Mail\BookMail;
use App\Mail\DemoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmailTest;
use Illuminate\Support\Facades\Mail;
  
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
    // protected $email, $mailData, $subject, $bill;
    protected $detail;
    /**
     * Create a new job instance.
     */
    public function __construct($detail)
    {
        $this->detail;
        // $this->email = $email;
        // $this->mailData = $mailData;
        // $this->subject = $subject;
        // $this->bill = $bill;
    }
  
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $pdf = app('dompdf.wrapper');
        // $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $this->bill])->setPaper('a4', 'portrait'); 

        // $target = new BookMail($this->mailData, $this->subject, $pdf);
        $target = new SendEmailTest();
        Mail::to('fyans665@gmail.com')->send($target);
    }
}