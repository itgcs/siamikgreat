<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\BillCollection;
use App\Models\Book;
use App\Models\Book_student;
use App\Models\Grade;
use App\Models\Payment_grade;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class BillController extends Controller
{

   public function index(Request $request)
   {
      try {

         session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'database bills'
         ]);
         
         $bill = Bill::select('type', DB::raw('count(*) as total'))->groupBy('type')->get();

         $grades = Grade::orderBy('id', 'asc')->get();
         
         $form = (object) [
            'grade' => $request->grade && $request->grade !== 'all'? $request->grade : null,
            'type' => $request->type && $request->type !== 'all'? $request->type : null,
            'invoice' => $request->invoice && $request->invoice !== 'all'? $request->invoice : null,
            'status' => $request->status && $request->status !== 'all'? $request->status : null,
            'search' => $request->search? $request->search : null,
            'page' => $request->page? $request->page : null,
         ];
         

         if($form->search || $request->page
         || $request->grade && $request->type && $request->invoice && $request->status)
         {
            
            $data = new Bill();
            $data = $data->with(['student' => function ($query) {
               $query->with('grade')->get();
            }]);
            
            if($form->grade) {
               $data = $data->whereHas('student', function($query) use ($form) {
                  $query
                  ->where('name','LIKE', '%'.$form->search.'%')
                  ->where('grade_id', (int)$form->grade);
               });
            }
            
            if ($form->search) {
               
               $data = $data->whereHas('student', function($query) use ($form) {
                  $query->where('name', 'LIKE' ,'%'.$form->search.'%');
               });
            }
            
            
            if($form->type)
            {
               $data = $data->where('type', $form->type);
            }
            
            if($form->status)
            {
               $statusPaid = $form->status == 'true'? true : false;
               
               $data = $data->where('paidOf', $statusPaid);
            }
            
            if($form->invoice)
            {
               
               if (is_numeric($form->invoice))
               {
                  $data = $data
                  ->where('deadline_invoice', '<=' ,Carbon::now()->setTimezone('Asia/Jakarta')->addDays((int)$form->invoice)->format('y-m-d'))
                  ->where('deadline_invoice', '>=' ,Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'));
               } else {
                  
                  
                  if($form->invoice == 'tommorow')
                  {
                     $data = $data->where('deadline_invoice', '=' , Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'));
                  } else {
                     
                     $operator = $form->invoice == 'today' ? '=' : '<';
                     
                     $data = $data->where('deadline_invoice', $operator, Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'));
                  }
                  
               }
            }
            
            
            $data = $data->orderBy('id', 'desc')->paginate(15);
         }
         else {
            $data = Bill::with(['student' => function ($query) {
               $query->with('grade')->get();
            }])
            ->orderBy('updated_at', 'desc')
               ->paginate(15);
               
            }
            
            
            // return $data;
         return view('components.bill.data-bill')->with('data', $data)->with('grade', $grades)->with('form', $form)->with('bill', $bill);
         
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }
   
   public function chooseStudent(Request $request){
      
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);
      
      try {
         //code...
         
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
      
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);
      
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
   
   
   public function actionSPP(Request $request, $id)
   {
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      session()->flash('preloader', true);

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
         
         // foreach($student->relationship as $el)
         // {
         //    Mail::to($el->email)->send(new SppMail($mailData));
         // }

         return redirect('/admin/bills');

      } catch (Exception $err) {
         //throw $th;
         return dd($err);
         return abort(500);
      }
   }

   public function detailPayment($id)
   {

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      
      try {
         //code...


         $data = Bill::with(['student' => function($query) {

               $query->with('grade')->get();

         }, 'bill_collection' 
         ])->where('id', $id)->first();

         $now = Carbon::now()->setTimezone('Asia/Jakarta');
         $explode = explode('-', $data->deadline_invoice);
         $targetDate = Carbon::create($explode[0], $explode[1], $explode[2])->setTimezone('Asia/Jakarta');
         $diff = $now->diff($targetDate);
         $invoice = $diff->days;

         return view('components.bill.spp.detail-spp')->with('data', $data)->with('invoice', $invoice);
         
      } catch (Exception $err) {
         // return dd($err);
         return abort(500);
      }
   }


   public function paidOf($id)
   {
      DB::beginTransaction();

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         //code...
         

         $bill = Bill::with('student')->where('id', $id)->first();

         if(!$bill)
         {

            DB::rollBack();
            return redirect('/admin/bills/detail-payment/'.$id)->withErrors([
               'id' => 'Id not found !!!'
            ]);
         }

         Bill::where('id', $id)->update([
               'paidOf' => true,
         ]);

         if(strtolower($bill->type) === 'paket')
         {
            $books = Book::where('grade_id', $bill->student->grade_id)->get();
            
            foreach($books as $book) {
               
               Book_student::create([
                  'student_id' => $bill->student_id,
                  'book_id' => $book->id,
               ]);
            }
         }

         DB::commit();

         return (object) [
            'success' => true,
         ];

      } catch (Exception $err) {
         //throw $th;
         DB::rollBack();
         return (object) [
            'success' => false,
         ];
      }
   }

   public function paidOfBook($bill_id, $student_id)
   {

      DB::beginTransaction();
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         //code...
         $bill = Bill::with(['bill_collection' => function($query) use ($bill_id) {
                  
               $query->where('bill_id', $bill_id)->get();
         }])
         ->where('id', $bill_id)
         ->first();


         foreach($bill->bill_collection as $el)
         {   
            Book_student::create([
               'book_id' => $el->id,
               'student' => $student_id,
            ]);
         }

         Bill::where('id', $bill_id)->update([
            'paidOf' => true,
         ]);

         return redirect('/admin/bills');
         
      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function pageChangePaket($student_id, $bill_id)
   {
      try {
         //code...
         session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'database bills'
         ]);    

         $checkBill = Bill::where('id', $bill_id)->first();

         
         if(!$checkBill || $checkBill->type !== 'Paket')
         {
           return redirect('/admin/bills/detail-payment/'.$bill_id)->withErrors([
              'bill' => 'This is not a paket type of bills so you can`t edit it !!!',
            ]);   
         }
         
         $student = Student::with(['grade' => function($query) {
            $query->with(['uniform' => function($query) {
               $query->where('type', 'Uniform')->get();
            }]);
         },'bill' => function($query) use ($bill_id) {
            $query->where('id', $bill_id);
         }])
         ->where('unique_id', $student_id)
         ->first(['id', 'name', 'grade_id']);

         if(sizeof($student->bill)<1)
         {
            return redirect('/admin/bills')->withErrors([
               'bill' => 'This is wrong paket from id '. $student->unique_id .' so you can`t edit it !!!',
            ]);   
         }

         $uniform = $student->grade->uniform? $student->grade->uniform : null;

         $data = Book::whereNotIn('id', function($query) use ($student) {
             $query
             ->select('book_id')
             ->from('book_students')
             ->where('student_id', $student->id);
          })
          ->where('grade_id', $student->grade_id)
          ->get();


         // return $data;
         return view('components.bill.change-paket.select-book')
         ->with('data', $data)
         ->with('student', $student)
         ->with('bill_id', $bill_id)
         ->with('uniform', $uniform);

     } catch (Exception $err) {
         return dd($err);
     }
   }


   public function actionChangePaket(Request $request, $bill_id, $student_id)
   {
      DB::beginTransaction();

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);
      session()->flash('preloader', true);

      try {

         
         $bookArray = $request->except(['_token', '_method', 'uniform', 'installment_book', 'installment_uniform']);
         $uniform = $request->only('uniform');
         $installment = (object) [
            'book' => $request->installment_book && $request->installment_book > 1? $request->installment_book : NULL,
            'uniform' => $request->installment_uniform && $request->installment_uniform > 1? $request->installment_uniform : NULL, 
         ];

         $totalAmountBook = 0;

         if(sizeof($bookArray)<1 && sizeof($uniform)<1)
         {
            return redirect()->back()->withErrors([
               'bill' => 'Checklist book or uniform are required !!!',
            ]);
         }
         
         $checkBill = Bill::where('id', $bill_id)->first();
         
         if(!$checkBill)
         {
            return redirect()->back()->withErrors([
               'bill' => 'Bill id not found !!!',
            ]);
         }
         
         if($installment->book > 12 || $installment->uniform > 12)
         {
            return redirect()->back()->withErrors([
               'bill' => 'Installments must not exceed 12 months',
            ]);
         }

         $bookName = [];
         $bookId = [];

         foreach ($bookArray as $el) {
            
               $book = Book::where('id', (int)$el)->first();

               BillCollection::create([
                  'bill_id' => $bill_id,
                  'book_id' => $book->id,
                  'type' => 'Book',
                  'name' => $book->name,
                  'amount' => $book->amount,
               ]);

               $totalAmountBook += $book->amount;
               array_push($bookName, $book->name);
               array_push($bookId, $book->id);
         }


         if($installment->book) {
            
            
            if (($totalAmountBook/$installment->book) % 10_000 === 0) {
               $billPerMonth = (int)$totalAmountBook / (int)$installment->book;
            } else {
               
               $billPerMonth = ceil((int)$totalAmountBook / (int)$installment->book);
               $billPerMonth += (10_000 - ($billPerMonth % 10_000));
            }

            for($i=1; $i<=$installment->book; $i++)
            {
               if($i === 1)
               {
                  Bill::where('id', $bill_id)->update([
                     'type' => 'Book',
                     'subject' => $i,
                     'amount' => $billPerMonth,
                     'installment' => $installment->book,
                  ]);
                  
               } else {
                  
                  $currentDate = $checkBill->deadline_invoice;
                  $newDate = date('Y-m-d', strtotime('+'.($i-1).' month', strtotime($currentDate)));



                  $bill = Bill::create([
                     'student_id' => $student_id,
                     'type' => 'Book',
                     'subject' =>  $i,
                     'amount' => $billPerMonth,
                     'paidOf' => false,
                     'discount' => NULL,
                     'installment' => $installment->book,
                     'deadline_invoice' => $newDate,
                  ]);

                  foreach($bookId as $id)
                  {
                     $book = Book::where('id', (int)$id)->first();

                     BillCollection::create([
                        'bill_id' => $bill->id,
                        'book_id' => $book->id,
                        'type' => 'Book',
                        'name' => $book->name,
                        'amount' => $book->amount,
                     ]);
                  }

               }
            }


            // add book directly when users choose installment

            foreach($bookId as $id)
            {
               Book_student::create([
                  'book_id' => $id,
                  'student_id' => $student_id,
               ]);
            }

         } else { 
            sizeof($bookName) > 0 ? Bill::where('id', $bill_id)
            ->update([
               'type' => 'Book',
               'subject' => 'Book '.implode(",",$bookName),
               'amount' => $totalAmountBook,
            ]) : '' ;
         }


         foreach($uniform as $el)
         {

            $uniform = Payment_grade::where('id', (int)$el)->first();

            if(!$uniform)
            {
               return redirect()->back()->withErrors([
                  'bill' => 'Uniform id not found !!!',
               ]);
            }


            if($installment->uniform){
               
               if (($uniform->amount/$installment->uniform) % 10_000 == 0) {
                  $billPerMonthUniform = (int)$uniform->amount / (int)$installment->uniform;
               } else {
                  
                  $billPerMonthUniform = ceil((int)$uniform->amount / (int)$installment->uniform);
                  $billPerMonthUniform += (10_000 - ($billPerMonthUniform % 10_000));
               }

               for($i = 1; $i<=$installment->uniform; $i++){


                  $currentDate = $checkBill->deadline_invoice;
                  $newDate = date('Y-m-d', strtotime('+'.($i-1).' month', strtotime($currentDate)));

                  Bill::create([
                     'student_id' => $student_id,
                     'type' => 'Uniform',
                     'subject' => $i,
                     'amount' => $billPerMonthUniform,
                     'paidOf' => false,
                     'discount' => null,
                     'installment' => $installment->uniform,
                     'deadline_invoice' => $i > 1 ? $newDate : $checkBill->deadline_invoice, 

                  ]);
               }


            } else {

               Bill::create([
                  'student_id' => $student_id,
                  'type' => 'Uniform',
                  'subject' => 'Uniform '. date("Y"),
                  'amount' => $uniform->amount,
                  'paidOf' => false,
                  'discount' => null,
                  'installment' => null,
                  'deadline_invoice' => $checkBill->deadline_invoice, 
               ]);
            }


         }

         //kurang create notification dan email,

         DB::commit();

         session()->flash('change_type_paket');

         return redirect('/admin/bills');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function pagePaketInstallment($bill_id) {

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         //code...

         $bill = Bill::with('student')->where('id', $bill_id)->first();

         if(!$bill){
            
            return redirect()->back()->withErrors([
               'bill' => [
                  'Bill not found !!!',
               ],
            ]);
         }

         return view('components.bill.change-paket.intallment-paket')->with('data', $bill);
         

      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function actionPaketInstallment( Request $request, $bill_id)
   {

      DB::beginTransaction();

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      session()->flash('preloader', true);

      try {

         $bill = Bill::with('student')->where('id', $bill_id)->first();

         if(!$bill){
            
            DB::rollBack();
            return redirect()->back()->withErrors([
               'bill' => [
                  'Bill not found !!!',
               ],
            ]);
         }

         $rules = $request->only('installment');
         
         $validator = Validator::make($rules, [
            'installment' => 'required|integer|max:12|min:2',
         ]);
         
         if($validator->fails())
         {
            DB::rollBack();
            return redirect()->back()->withErrors($validator->messages())->withInput($rules);
         }


         $billPerMonth = ceil((int)$bill->amount / (int)$request->installment);
         $billPerMonth += (10_000 - ($billPerMonth % 10_000));

         for($i = 1; $i <= $request->installment; $i++)
         {
            
            if($i == 1)
            {
               
               Bill::where('id', $bill->id)->update([
                  'subject' => $i,
                  'installment' => $request->installment,
                  'amount' => $billPerMonth,
               ]);



            } else {
               

                  $currentDate = $bill->deadline_invoice;
                  $newDate = date('Y-m-d', strtotime('+'.($i-1).' month', strtotime($currentDate)));

                  Bill::create([
                     'student_id' => $bill->student_id,
                     'type' => 'Paket',
                     'subject' => $i,
                     'amount' => $billPerMonth,
                     'paidOf' => false,
                     'discount' => null,
                     'installment' => $request->installment,
                     'deadline_invoice' => $newDate, 

                  ]);
            }
         }


         $books = Book::where('grade_id', $bill->student->grade_id)->get();

         foreach($books as $book) {
            
            Book_student::create([
               'student_id' => $bill->student_id,
               'book_id' => $book->id,
            ]);
         }

         session()->flash('create_installment_bill');

         DB::commit();
         return redirect('/admin/bills');

      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}