<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment_student;
use App\Models\Student;
use Exception;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class PaymentStudentController extends Controller
{
   public function index()
   {
      try {
         //code...
         $data = Student::with([
            'payment_student' => function($query) {
               $query->whereNot('type', 'SPP');            
            }, 
            'spp_student' => function($query){
               $query->where('type', 'SPP')->get();
         },
         'grade'
         ])
         ->get();

         // return  $data;

         return view('components.student.payment.data-payment-student')->with('data', $data)->with('form', null);
         
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }


   public function choosePayment(Request $request, $id)
   {
      try {
         //code...
         $data = Student::where('unique_id', $id)->first();

         return view('components.student.payment.data-create-student')->with('data', $data);
         
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }



   public function createPage($id, $type)
   {
      try {
         //code...
         $type = $type;
         $data = Student::with([
         'grade' => function ($query) use ($type) {
               $query->with(['type' => function ($query) use ($type) {
               $query->where('type', $type);
            }]);
         },
      ])->where('unique_id', $id)->first();


         return view('components.student.payment.create-static-payment')->with('data', $data)->with('type', $type);
         
      } catch (Exception $err) {
         //throw $th;
         return abort(400);
      }
   }


   public function actionCreatePayment(Request $request, $id, $type){
      try {
         //code...
         
         $rules = [
            'type' => $type,
            'amount' => (int)str_replace(".", "", $request->amount),
            'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
            'installment' => $request->installment &&  $request->installment>0? (int)$request->installment : 0,
         ];


         $validator = Validator::make($rules, [
            'type' => 'required|string',
            'amount' => 'required|integer',
            'discount' => 'nullable|integer|max:99',
            'installment' => 'nullable|nullable|max:12',
         ]);


         if($validator->fails())
         {
            return redirect('/admin/payment-students/create/'. $id . '/' .$type)->withErrors($validator->messages())->withInput($rules);
         }

         $student = Student::where('unique_id', $id)->first();

         $check_unique = Payment_student::where('student_id', $student->id)->where('type', $type)->first();


         if($check_unique){
            
            return $check_unique;
            Payment_student::where('id', $check_unique->id)->update([
               'type' => $type,
               'amount' => (int)str_replace(".", "", $request->amount),
               'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
            ]);
            
         } else  {

            for($i = 0; $i <= $request->installment; $i++)
            {
               Payment_student::create([
                  'type' => $type,
                  'student_id' => $student->id,
                  'amount' => (int)str_replace(".", "", $request->amount),
                  'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
               ]);
            }
         }


         return redirect('/admin/payment-students');

      } catch (Exception $err) {
         return dd($err);
      }
   }
}