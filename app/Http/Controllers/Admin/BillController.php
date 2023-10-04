<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;

class BillController extends Controller
{

   public function index()
   {
      try {
         
         $data = Bill::with('student')->get();

         return view('components.bill.data-bill')->with('data', $data);

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
}