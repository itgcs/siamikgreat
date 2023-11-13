<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Mail\BookMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\FeeRegisMail;
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
               
           // return $data;
  
           foreach ($data as $student) {
  
              foreach ($student->bill as $bill) {
                 # code...
                 $mailData = [
                    'student' => $student,
                    'bill' => [$bill],
                    'past_due' => true,
                 ];
  
                 $pdfBill = Bill::with(['student' => function ($query) {
                    $query->with('grade');
                 }, 'bill_installments'])
                 ->where('id', $bill->id)
                 ->first();
                  
                 $pdf = app('dompdf.wrapper');
                 $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
                 $pdfReport = null;
                 
                 if($pdfBill->installment)
                 {
                    $pdfReport = app('dompdf.wrapper');
                    $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
                 }
  
                 try {
                    //code...
                    foreach ($student->relationship as $relationship) {
                       $mailData['name'] = $relationship->name;
  
                       if($type == 'SPP') {
  
                          Mail::to($relationship->email)->send(new SppMail($mailData, "Charge " . $type . " tagihan anda yang sudah jatuh tempo", $pdf, $pdfReport));
                       } else {
  
                          // return view('emails.fee-regis-mail')->with('mailData', $mailData);
                          Mail::to($relationship->email)->send(new FeeRegisMail($mailData, "Charge " . $type . " tagihan anda yang sudah jatuh tempo", $pdf, $pdfReport));
                       }
                    }
  
                    statusInvoiceMail::create([
                       'bill_id' => $bill->id,
                       'charge' => true,
                       'past_due' => true,
                    ]);
  
                 } catch (Exception) {
  
                    statusInvoiceMail::create([
                       'bill_id' => $bill->id,
                       'status' => false,
                       'charge' => true,
                       'past_due' => true,
                    ]);
                 }
              }
  
              
           }

           DB::commit();

           info("Cron Job charge success at ". date('d-m-Y'));
           
       } catch (Exception $err) {
          DB::rollBack();
          info("Cron Job reminder Error at: " . $err);
       }
    }
}
