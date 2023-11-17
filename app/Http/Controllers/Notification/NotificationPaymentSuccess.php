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

class NotificationPaymentSuccess extends Controller
{

    public function paymentSuccess($type = 'SPP')
    {
        DB::beginTransaction();
        date_default_timezone_set('Asia/Jakarta');
        try {
           //code...

         if($type == 'etc') {

            $students = Student::with([
               'bill' => function($query) {
                  $query
                  ->whereNotIn('type', ["SPP", "Capital Fee", "Paket", "Book", "Uniform"])
                  ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('paidOf', true)
                  ->get();
            },
               'relationship'
            ])
            ->whereHas('bill', function($query) {
                  $query
                  ->whereNotIn('type', ["SPP", "Capital Fee", "Paket", "Book", "Uniform"])
                  ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('paidOf', true);
            })->get();


         } else {
            
            $students = Student::with([
               'bill' => function($query) use ($type) {
                  $query
                  ->where('type', $type)
                  ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('paidOf', true)
                  ->get();
            },
               'relationship'
            ])
            ->whereHas('bill', function($query) use ($type) {
                  $query
                  ->where('type', $type)
                  ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('paidOf', true);
            })->get();

         }

         //   return $students;
  
           foreach ($students as $student) {
  
              foreach ($student->bill as $bill) {
                 # code...
                 $mailData = [
                    'student' => $student,
                    'bill' => [$bill],
                    'past_due' => true,
                 ];
                 
                 $pdfBill = Bill::with(['student' => function ($query) {
                    $query->with('grade');
                 }, 'bill_collection', 'bill_installments'])
                 ->where('id', $bill->id)
                 ->first();
                 
                  $pdf = app('dompdf.wrapper');
                  $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');

                  $pdfReport = null;

                  if($pdfBill->installment){
                     
                     $pdfReport = app('dompdf.wrapper');
                     $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
                  }
  
                  // return view('emails.payment-success')->with('mailData', $mailData);
                  try {
                     //code...
                     foreach ($student->relationship as $relationship) {
                        $mailData['name'] = $relationship->name;
                        Mail::to($relationship->email)->send(new PaymentSuccessMail($mailData, "Payment " . $type . " ". $student->name ." has confirmed!", $pdf, $pdfReport));
                        statusInvoiceMail::create([
                           'bill_id' => $pdfBill->id,
                           'status' => false,
                           'is_paid' => true,
                        ]);
                     }
                     } catch (Exception) {
                        
                        statusInvoiceMail::create([
                           'bill_id' => $pdfBill->id,
                           'status' => false,
                           'is_paid' => true,
                        ]);
                     }
              }
           } 

           DB::commit();
           info('Cron job send notification payment success at ' . now());
           
        } catch (Exception $err) {
           
            DB::rollBack();
            info('Cron job send notification payment error at ' . $err);
        }
    }
}
