<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;
  
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
         $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ];
         
        Mail::to('tkeluarga111@gmail.com')->send(new DemoMail($mailData));
           
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


   public function cronReminderPastDue($type)
   {
      try {
         date_default_timezone_set('Asia/Jakarta');
         
         $data = Student::with(['bill' => function($query) use ($type){
            $query->where('type', $type)->get();
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
}