<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Mail\BookEmail;
use App\Models\Bill;
use App\Models\Book;
use App\Models\Book_student;
use App\Models\Grade;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentBookController extends Controller
{
    public function index(Request $request)
    {
        try {
            
            session()->flash('page',  (object)[
                'page' => 'payments',
                'child' => 'payment-books',
             ]);

             $form = (object) [
                'sort' => $request->sort? $request->sort : null,
                'order' => $request->order? $request->order : null,
                'status' => $request->status? $request->status : null,
                'search' => $request->search? $request->search : null,
                'grade_id' => $request->grade_id? $request->grade_id : null,
             ];

             $grade = Grade::orderBy('id', 'asc')->get(['id', 'name', 'class']);

             $order = $request->sort ? $request->sort : 'desc';
             $status = $request->status? ($request->status == 'true' ? true : false) : true;

            if($form->search && $form->order && $form->status && $form->sort && $form->grade_id){
            
                $data = Student::with(['grade'])
                ->withCount('book')
                ->orderBy($form->order, $order)
                ->where('name', 'LIKE', '%'.$form->search.'%')
                ->where('grade_id', (int)$form->grade_id)
                ->where('is_active', $status)
                ->get();

            } else if($form->search && $form->order && $form->status && $form->sort) {

                $data = Student::with(['grade'])
                ->withCount('book')
                ->where('name', 'LIKE', '%'.$form->search.'%')
                ->where('is_active', $status)
                ->orderBy($form->order, $order)
                ->get();

            } else if($form->order && $form->status && $form->sort && $form->grade_id){

                $data = Student::with(['grade'])
                ->withCount('book')
                ->where('grade_id', (int)$form->grade_id)
                ->where('is_active', $status)->orderBy($form->order, $order)
                ->get();
                
            } else if($form->order && $form->status && $form->sort) {
                
                $data = Student::with(['grade'])->withCount('book')
                ->where('is_active', $status)
                ->orderBy($form->order, $order)
                ->get();

            } else {

                $data = Student::with('grade')->withCount('book')->where('is_active', $status)->orderBy('id', 'desc')->get();
            }


            return view('components.book.payments.data-payment-book')->with('data', $data)->with('form', $form)->with('grade', $grade);

        } catch (Exception $err) {
            return abort(500, $err);
        }
    }

    public function studentBook($id)
    {
        try {
            //code...

            session()->flash('page',  (object)[
                'page' => 'payments',
                'child' => 'payment-books',
             ]);    

            $data = Student::with(['book', 'grade'])->where('unique_id', $id)->first();
            
            return view('components.book.payments.data-book-by-id')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function pageAddBook($id)
    {
        try {
            //code...
            session()->flash('page',  (object)[
                'page' => 'payments',
                'child' => 'payment-books',
             ]);    

             $student = Student::with('grade')
             ->where('unique_id', $id)
             ->first(['id', 'name', 'grade_id']);

             $data = Book::whereNotIn('id', function($query) use ($student) {
                $query
                ->select('book_id')
                ->from('book_students')
                ->where('student_id', $student->id);
             })
             ->where('grade_id', $student->grade_id)
             ->get();

            // return $data;
            return view('components.book.payments.add-student-book')->with('data', $data)->with('student', $student);

        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function actionAddBook(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            //code...
            $bill = [];
            $data = $request->except(['_token', '_method']);
            $student = Student::with(['grade', 'relationship'])->where('id', $id)->first();
            
            foreach($data as $el)
            {

                $book = Book::where('id', (int)$el)->first();

                if($book) {

                    $temp = [
                        'type' => 'Book',
                        'subject' => $book->name,
                        'student_id' => $student->id,
                        'amount' => (int)$book->amount,
                        'paidOf' => false,
                        'discount' => null,
                        'deadline_invoice' => Carbon::now()->addDay(30)->format('y-m-d'),
                        'installment' => null,
                    ];

                    Book_student::create([
                        'student_id' => (int)$id,
                        'book_id' => (int)$el,
                    ]);

                    Bill::create($temp);

                    array_push($bill, $temp);
                }
                
            }

            if(sizeof($bill)<=0)
            {
                DB::rollBack();
                return redirect('/admin/payment-books/'.$student->unique_id.'/add-books')->withErrors([
                    'bill' => 'Checklist book is required',
                ]);
            }
            
            $mailData = [
                'student' => $student,
                'bill' => $bill,
            ];

            $mail = new MailController;
            $mail->addBookEmail($mailData);  
            

            DB::commit();
            return redirect('/admin/payment-books/'.$student->unique_id);

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }
}
