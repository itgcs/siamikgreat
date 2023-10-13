<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\FeeRegisMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Console\View\Components\Info;
use Illuminate\Support\Carbon;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
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
            $query->where('type', $type)->where('deadline_invoice', '<', date('Y-m-d'))->get();
         }, 'relationship'])->whereHas('bill', function($query) {
            $query->where('paidOf', false)->where('deadline_invoice', '<', date('Y-m-d'));
         })->where('is_active', true)->get();
             

         foreach ($data as $value) {

            foreach ($value->relationship as $value2) {

               $mailData = [
                  'student' => $value,
                  'bill' => $value->bill,
                  'past_due' => true,
               ];
               
               Mail::to($value2->email)->send(new SppMail($mailData, "Berikut adalah total tagihan anda yang sudah jatuh tempo"));
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
               $query->where('type', $type)->where('deadline_invoice', '=', Carbon::now()->addDays(7)->format('y-m-d'))->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query->where('type', $type)->where('deadline_invoice', '=', Carbon::now()->addDays(7)->format('y-m-d'));
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
               $query->where('type', $type)->where('deadline_invoice', '=', Carbon::now()->addDays(1)->format('y-m-d'))->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query->where('type', $type)->where('deadline_invoice', '=', Carbon::now()->addDays(1)->format('y-m-d'));
         })
         ->where('is_active', true)->get();

      
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
}