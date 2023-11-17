<?php
  
namespace App\Jobs;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmailTest;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\statusInvoiceMail;
use Exception;
use Illuminate\Support\Facades\Mail;
  
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $email, $type, $mailData, $subject, $bill_id;
  
    /**
     * Create a new job instance.
     */
    public function __construct($email, $type, $mailData, $subject, $bill_id)
    {
        $this->email = $email;
        $this->type = $type;
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->bill_id = $bill_id;
    }
  
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdfBill = Bill::with(['student' => function ($query) {
            $query->with('grade');
        }, 'bill_collection', 'bill_installments'])
        ->where('id', $this->bill_id)
        ->first();
          
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 
        
        Mail::to($this->email[0])->cc($this->email[1])->send(new SppMail($this->mailData, $this->subject, $pdf));

        statusInvoiceMail::create([
            'bill_id' => $this->bill_id,
        ]);
        // info('Email job spp success at '. now());
    }

    public function failed(\Exception $exception) :void
    {
        statusInvoiceMail::create([
            'bill_id' => $this->bill_id,
            'status' => false,
        ]);
        // info('Email job spp failed at ' . $exception->getMessage());
    }
}