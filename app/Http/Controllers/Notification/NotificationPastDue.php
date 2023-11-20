<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\BookMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\FeeRegisMail;
use App\Mail\PaketMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Book;
use App\Models\statusInvoiceMail;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;

class NotificationPastDue extends Controller
{
    public function cronChargePastDue($type = 'SPP', $charge = false)
    {
      DB::beginTransaction();
        try {

           date_default_timezone_set('Asia/Jakarta');
  
           if($charge) 
           {
                $billCharge = Bill::where('paidOf', false)->where('deadline_invoice', '<', date('Y-m-d'))->where('type', $type)->get(['id', 'amount', 'charge', 'installment', 'amount_installment']);
            
                foreach ($billCharge as $bill) {
                   # code...
                   Bill::where('id', $bill->id)->update([
                      'amount'=> $bill->amount + 100_000,
                      'charge'=> $bill->charge + 100_000,
                      'amount_installment' => $bill->installment? $bill->amount_installment + 100_000 : $bill->amount_installment,
                   ]);
                }
           }

         
         if($type == 'etc') 
         {

            $data = Student::with(['bill' => function($query) use ($type){
               $query
               ->whereNotIn('type', ['SPP', "Capital Fee", "Paker", "Book", "uniform"])
               ->where('deadline_invoice', '<', date('Y-m-d'))
               ->where('paidOf', false)
               ->get();
            }, 'relationship'])->whereHas('bill', function($query) use ($type) {
               $query
               ->whereNotIn('type', ['SPP', "Capital Fee", "Paker", "Book", "uniform"])
               ->where('paidOf', false)
               ->where('deadline_invoice', '<', date('Y-m-d'));
            })->get();

         } else {
            
            $data = Student::with(['bill' => function($query) use ($type){
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '<', date('Y-m-d'))
               ->where('paidOf', false)
               ->get();
            }, 'relationship'])->whereHas('bill', function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('paidOf', false)
               ->where('deadline_invoice', '<', date('Y-m-d'));
            })->get();
         }


               
         info($data);
  
           foreach ($data as $student) {
  
              foreach ($student->bill as $bill) {
                 # code...
                 $mailData = [
                    'student' => $student,
                    'bill' => strtolower($type) == 'book'? $bill : [$bill],
                    'past_due' => true,
                    'charge' => $charge,
                    'change' => false,
                    'is_paid' => false,
                 ];
  
                 try {
                    //code...
                     $subs = $charge? "Tagihan " . $bill->type . " ". $student->name ." terkena charge karena sudah melewati jatuh tempo" : "Tagihan " . $bill->type . " " . $student->name ." sudah melewati jatuh tempo";

                     $array_email = [];

                    foreach ($student->relationship as $key => $parent) {
                        
                     if($key == 0) $mailData['name'] = $parent->name;
                     
                     array_push($array_email, $parent->email);
                        //   return view('emails.spp-mail')->with('mailData', $mailData);
                           
                        //   return view('emails.fee-regis-mail')->with('mailData', $mailData);
                        
                        //   return view('emails.paket-mail')->with('mailData', $mailData);

                        // return view('emails.book-mail')->with('mailData', $mailData);
                     }

                     dispatch(new SendEmailJob($array_email, strtolower($type), $mailData, $subs, $bill->id));
  
  
                 } catch (Exception) {
  
                    statusInvoiceMail::create([
                       'bill_id' => $bill->id,
                       'status' => false,
                       'charge' => $charge,
                       'past_due' => true,
                    ]);
                 }
              }
  
              
           }

           DB::commit();

           info("Cron Job past due success at ". date('d-m-Y'));
           
       } catch (Exception $err) {
          DB::rollBack();
          info("Cron Job pastdue Error at: " . $err);
       }
    }
}