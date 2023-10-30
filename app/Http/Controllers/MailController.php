<?php
  
namespace App\Http\Controllers;

use App\Mail\BookEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\FeeRegisMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;


// use Illuminate\Bus\Queueable;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

   // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function index()
    {
      try {
         //code...

         $student = Student::with(
            ['bill' => function($query){
               $query->where('type', 'Uang Gedung')->get();
            }, 
             'relationship' => function($query){
               $query->get(['email']);
            },])->whereHas('bill', function($query) {
               $query->where('type', 'Uang Gedung')->where('paidOf', false)->where('deadline_invoice', '>', date('y-m-d'));
            })->where('id', 10)->first();
         
            // return $student;
         $mailData = [
            'student' => $student,
            'bill' => $student->bill,
            'past_due' => false
        ];

        Mail::to('tkeluarga111@gmail.com')->send(new FeeRegisMail($mailData, 'Berikut pembayaran Uang Gedung cicilan ke 1 untuk' .$student->name));
           
        return dd("Email is sent successfully.");
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
        
    }

    public function cronCreateSpp()
   {
      DB::beginTransaction();

      try {
         //code...
         date_default_timezone_set('Asia/Jakarta');
         
         $data = Student::with(['relationship', 'spp_student' => function($query) {
            $query->where('type', 'SPP')->get();
         }, 
         'grade' => function($query) {
            $query->with(['spp' => function($query) {
               $query->where('type', 'SPP')->get();
            }]);
         }])->where('is_active', true)->orderBy('id', 'asc')->get();
         
         foreach($data as $student)
         {
            $createBill = Bill::create([
               'student_id' => $student->id,
               'type' => 'SPP',
               'subject' => 'SPP - ' . date('M Y'),
               'amount' => $student->spp_student? $student->spp_student->amount : $student->grade->spp->amount,
               'paidOf' => false,
               'discount' => $student->spp_student ? ($student->spp_student->discount? $student->spp_student->discount : null) : null,
               'deadline_invoice' => date("Y-m-t", strtotime(now())),
               'installment' => 0,
            ]);
            
            $mailData = [
               'student' => $student,
               'bill' => [$createBill],
               'past_due' => false,
            ];
            
            
            
            foreach($student->relationship as $el)
            {
               Mail::to($el->email)->send(new SppMail($mailData, "Tagihan SPP " . $student->name.  " bulan ini, ". date('l, d F Y') ." sudah dibuat."));
               
            }
            
         }
         info("Cron Job create spp success at ". date('d-m-Y'), []);
         DB::commit();
      } catch (Exception $err) {
         //throw $th;
         DB::rollBack();
         info("Cron Job create spp error: ". $err, []);
         return dd($err);
      }
   }


   public function cronReminderPastDue($type = "SPP")
   {
      try {
         date_default_timezone_set('Asia/Jakarta');
         
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
         })->where('is_active', true)->get();
             

         foreach ($data as $value) {

            foreach ($value->relationship as $value2) {

               $mailData = [
                  'student' => $value,
                  'bill' => $value->bill,
                  'past_due' => true,
               ];
               
               Mail::to($value2->email)->send(new SppMail($mailData, "Berikut adalah total " . $type . " tagihan anda yang sudah jatuh tempo"));
            }
            
         }
         info("Cron Job reminder success at ". date('d-m-Y'));
         
     } catch (Exception $err) {
         info("Cron Job reminder Error at: " . $err);
     }
   }


   public function  cronReminderMinusSevenDay($type = "SPP")
   {
      try {
         //code...
         $data = Student::with([
            'bill' => function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(7)->format('y-m-d'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(7)->format('y-m-d'))
               ->where('paidOf', false);
         })
         ->where('is_active', true)->get();

      
         foreach ($data as  $student)
         {
            $mailData = [
               'student' => $student,
               'bill' => $student->bill,
               'past_due' => 'H-7',
            ];

            foreach($student->relationship as $parent)
            {

               if($type === 'SPP') {

                  Mail::to($parent->email)->send(new SppMail($mailData, 'Reminder h-7 pembayaran '. strtolower($student->bill[0]->subject) .' sebelum jatuh tempo'));
               } else if ($type === 'Uang Gedung'){
                  Mail::to($parent->email)->send(new FeeRegisMail($mailData, 'Reminder h-7 pembayaran '. strtolower($student->bill[0]->subject) .' sebelum jatuh tempo'));
               }
            }
         }

         Info("Cron Job reminder H-7 success at ". date('d-m-Y'));
         
         return 'success';
      } catch (Exception $err) {
         Info("Cron Job reminder H-7 error: ". $err);
         
         return dd($err);
      }
   }

   public function cronReminderMinusOneDay($type = "SPP")
   {
      try {
         //code...
         $data = Student::with([
            'bill' => function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'))
               ->where('paidOf', false);
         })
         ->where('is_active', true)
         ->get();

      
         foreach ($data as  $student)
         {
            $mailData = [
               'student' => $student,
               'bill' => $student->bill,
               'past_due' => 'H-1',
            ];

            foreach($student->relationship as $parent)
            {
               Mail::to($parent->email)->send(new SppMail($mailData, 'Reminder h-1 pembayaran '. strtolower($student->bill[0]->subject) .' sebelum jatuh tempo'));
            }
         }

         Info("Cron Job reminder H-1 success at ". date('d-m-Y'));
         
         return 'success';
      } catch (Exception $err) {
         Info("Cron Job reminder H-1 error: ". $err);
         
         return dd($err);
      }
   }


   public function addBookEmail($mailData)
   {
      try {


         foreach ($mailData['student']->relationship as $parent)
         {
            Mail::to($parent->email)->send(new BookEmail($mailData, "Tagihan Buku yang telah dipesan " . $mailData['student']->name.  " ". date('l, d F Y') ." sudah dibuat."));
         }


         return [
            'status' => true,
         ];
         
      } catch (Exception $err) {
         

         return [
            'status' => false,
         ];
      }
   }

   public function cronCreatePaketAfterGraduate($array =  [])
   {

      DB::beginTransaction();
      try {
         //code...

         
         // return $students;

         foreach($array as $studentId)
         {

            $student = Student::with(['relationship', 'grade' => function($query) {
               $query->with(['bundle' => function($query) {
                  $query->where('type', 'Paket')->get();
               }, 'uniform' => function($query) {
                  $query->where('type', 'Uniform')->get();
               }]);
            }])
            ->where('id', (int)$studentId)
            ->where('is_active', true)
            ->first();
            //lakukan validasi ketika admin lupa memasukkan static payment paket disini;
            
            
            
            $amount = $student->grade->bundle? $student->grade->bundle->amount : null;
            $uniformAmount = $student->grade->uniform? $student->grade->uniform->amount : 0; 

            if(!$amount)
            {
               $bookAmount = Book::where('grade_id', (int)$student->grade_id)->get();
               $amountTotal = sizeof($bookAmount) > 0 ? $bookAmount->sum('amount') : 0;
               $amount = $uniformAmount + $amountTotal;
            }
            
            $bill = Bill::create([
               'student_id' => $student->id,
               'type' => 'Paket',
               'subject' => 'Paket '. $student->grade->name. ' ' .$student->grade->class,
               'amount' => $amount,
               'discount' => null,
               'installment' => null,
               'paidOf' => false,
               'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->addDays(30)->format('y-m-d'),
            ]);


            $mailData = [
               'student' => $student,
               'bill' => $bill,
            ];

            // setelah ini harus handle kirim email
         }


         DB::commit();
         info('Create paket success at '. date('y-m-d'));
         // return redirect('/admin/bills');
         
      } catch (Exception $err) {
         DB::rollBack();
         info('Create paket error at '. date('y-m-d'));
         // return dd($err);
      }
   }



   public function createNotificationFeeRegister()
   {
      try {
         //sementara gabisa kirim email push array dulu

         $arr = [];

         $data = Student::with([
            'bill' => function($query)  {
               $query
               ->where('type', "Uang Gedung")
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(30)->format('y-m-d'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) {
               $query
               ->where('type', "Uang Gedung")
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(30)->format('y-m-d'))
               ->where('paidOf', false);
         })
         ->where('is_active', true)
         ->get();


         foreach ($data as $student) {
            
            foreach($student->relationship as $parent)
            {
               //ini besok ganti dengan cara mengirim email ke parents
               
               array_push($arr, $parent->email);
            }
         }



         // return $arr;

         info('Cron reminder h-30 (notifications create bill)');

      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}