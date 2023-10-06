<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Payment_grade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentGradeController extends Controller
{
   

   public function index($id)
   {
      try {
         //code...
         session()->flash('page', 'payment');

         $data = Grade::with(['payment_grade' => function($query) {
            $query->orderBy('type', 'asc');
         }])->where('id', $id)->first();

         return $data;

         return view('components.grade.payment.data-payment')->with('data', $data);

         
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }


   public function pageCreate($id) 
   {
      try {
         //code...

         $data = Grade::where('id', $id)->first();

         return view('components.grade.payment.form-payment')->with('data', $data);
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function actionCreate(Request $request, $id)
   {

      DB::beginTransaction();

      try {
         //code...
         $rules  = [
            'type' => $request->type,
            'amount' => $request->amount,
            'grade_id' => $id,
         ];
         
         $backupError = $rules['amount'];
         $rules['amount'] = (int)str_replace(".", "", $rules['amount']);
         
         $validator = Validator::make($rules, [
            'type' => 'required|string', 
            'amount' => 'required|integer', 
            'grade_id' => 'required|integer', 
         ]);
         
         if($validator->fails())
         {
            DB::rollBack();
            $rules['amount'] = $backupError;
            return redirect('/admin/grades/payment-grades'.'/' . $id . '/'.'create')->withErrors($validator->messages())->withInput($rules);
         }
         
         Payment_grade::where('type', $rules['type'])->delete();
         Payment_grade::create($rules);



         DB::commit();

         return redirect('/admin/grades/payment-grades' . '/' . $id);
         
      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function pageEdit($id){
      try {
         //code...

         $data = Payment_grade::with('grade')->where('id', $id)->first();

         return view('components.grade.payment.edit-payment')->with('data', $data);
         
      } catch (Exception $err) {
         

         return dd($err);
      }
   }
   

   public function actionEdit(Request $request, $id){
      try {
         //code...

         $rules = $request->only('amount');

         $rules['amount'] = (int)str_replace(".", "", $rules['amount']);

         $validator = Validator::make($rules, [
            'amount' => 'integer|required',
         ]);

         if($validator->fails())
         {
            return redirect('/admin/grades/payment-grades/'. $id .'/edit')->withErrors($validator->messages())->withInput($rules);
         }
         
         Payment_grade::where('id', $id)->update([
            'amount' => $rules['amount']
         ]);

         $data = Payment_grade::where('id', $id)->first();

         return redirect('/admin/grades/payment-grades' . '/' . $data->grade_id);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function deletePayment($id)
   {
      try {
         
         Payment_grade::where('id', $id)->delete();

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         return response()->json([
            'success' => false,
         ]);
      }
   }
}