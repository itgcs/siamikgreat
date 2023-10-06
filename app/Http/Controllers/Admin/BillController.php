<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Payment_semester;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{

   public function index(Request $request)
   {
      try {
         
         $data = Bill::with(['student' => function ($query) {
               $query->with('grade')->get();
         }])->orderBy('id', 'desc')->get();
          

         $form = (object) [
            'sort' => $request->sort? $request->sort : null,
            'order' => $request->order? $request->order : null,
            'status' => $request->status? $request->status : null,
            'search' => $request->search? $request->search : null,
         ];

         return view('components.bill.data-bill')->with('data', $data)->with('form', $form);

      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }

   public function chooseStudent(Request $request){

      try {
         //code...
         session()->flash('preloader', false);

         $form = (object) [
            'sort' => $request->sort? $request->sort : null,
            'order' => $request->order? $request->order : null,
            'status' => $request->status? $request->status : null,
            'search' => $request->search? $request->search : null,
         ];

         $data = [];
         $order = $request->sort ? $request->sort : 'desc';
         $status = $request->status? ($request->status == 'true' ? true : false) : true;
         
         if($request->type && $request->search && $request->order){
            
            $data = Student::with('grade')->where('is_active', $status)->where($request->type,'LIKE','%'. $request->search .'%')->orderBy($request->order, $order)->get();
         } else if($request->type && $request->search)
         {
            $data = Student::with('grade')->where('is_active', $status)->where($request->type,'LIKE','%'. $request->search .'%')->orderBy('created_at', $order)->get();
         } else if($request->order) {
            $data = Student::with('grade')->where('is_active', $status)->orderBy($request->order, $order)->get();
         } else {

            $data = Student::with('grade')->withCount('bill as total_bill')->orderBy('created_at', $order)->get();
         }

         
         return view('components.bill.choose-bill')->with('data', $data)->with('form', $form);
      } catch (Exception $err) {
         //throw $th;
         return abort(500, 'Internal server error');
      }
   }


   public function pageSPP($id)
   {
      try {
         //code...
         $data = Student::with(['grade' => function ($query) {
            $query->with(['spp' => function ($query2) {
               $query2->where('type', 'SPP')->first();
            }])->first();
         }])->where('unique_id', $id)->first();

         // return $data;

         return view('components.bill.spp.create-spp')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pagePayment($id)
   {
      try {
         
         return 'Ketrigger';

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function actionSPP(Request $request, $id)
   {
      try {
         //code...
         $student = Student::with('relationship')->where('id', $id)->first();
         date_default_timezone_set('Asia/Jakarta');
         $rules = [
            'student_id' => $id,
            'subject' => $request->subject,
            'description' => $request->description,
            'amount' => $request->amount ? (int)str_replace(".", "", $request->amount) : null,
            'discount' => $request->discount ?  (int)$request->discount : null,
            
         ];

         $validator = Validator::make($rules, [
            'subject' => 'nullable|string|min:3',
            'description' => 'nullable|string|min: 10',
            'amount' => 'required|integer|min:10000',
            'discount' => 'nullable|integer|max:99',
         ]);

         if($validator->fails())
         {
            return redirect('/admin/bills/create-spp/'. $student->unique_id)->withErrors($validator->errors())->withInput($rules);
         }

         Bill::create([
            'type' => 'SPP',
            'student_id' => $id,
            'subject' => $request->subject,
            'description' => $request->description,
            'amount' => (int)$request->amount ? (int)str_replace(".", "", $request->amount) : null,
            'discount' => (int)$request->discount > 0 ?  (int)$request->discount : null,
            'deadline_invoice' => date('Y-m-d'),
         ]);   

         $bill = Bill::where('student_id', $student->id)->where('paidOf', false)->get();

         $mailData = [
            'name' => $student->name,
            'student' => $student,
            'bill' => $bill,
        ];
         
         foreach($student->relationship as $el)
         {
            Mail::to($el->email)->send(new SppMail($mailData));
         }

         return redirect('/admin/bills');

      } catch (Exception $err) {
         //throw $th;
         return dd($err);
         return abort(500);
      }
   }


   public function detailPayment($id)
   {
      try {
         //code...

         $data = Bill::with(['student' => function($query) {

               $query->with('grade')->get();

         }])->where('id', $id)->first();


         return view('components.bill.spp.detail-spp')->with('data', $data);
         
      } catch (Exception $err) {
         return abort(500);
      }
   }
}