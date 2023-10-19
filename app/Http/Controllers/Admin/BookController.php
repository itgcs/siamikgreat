<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Grade;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        try {

            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);

            $form = (object) [
                'book_name' => $request->book_name,
                'grade_id' => $request->grade_id,
            ];
            
            
            if($form->book_name && $form->grade_id)
            {
                $data = Book::with(['grade' => function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                }])
                ->whereHas('grade', function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                })
                ->where('name', 'LIKE', '%'.$form->book_name.'%')
                ->get();
            } else if ($form->book_name) {
                $data = Book::with(['grade'])->where('name', 'LIKE', '%'.$form->book_name.'%')->get();
            } else if ($form->grade_id) {
                $data = Book::with(['grade' => function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                }])
                ->whereHas('grade', function($query) use ($form) {
                    $query->where('id', $form->grade_id);
                })
                ->get();
            } else {
                
                $data = Book::with(['grade'])->orderBy('id', 'desc')->get();
            }

            $grades = Grade::orderBy('id', 'asc')->get();

            return view('components.book.data-book-grade')->with('data', $data)->with('form', $form)->with('grades', $grades);
            
        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);

            $grade = Grade::orderBy('id', 'asc')->get();


            return view('components.book.create-book')->with('grade', $grade);

        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function postCreate(Request $request)
    {
        try {
            //code...

            session()->flash('page', (object) [
                'page' => 'books',
                'child' => 'database book',
            ]);

            $rules = [
                'name' => $request->name,
                'grade_id' => (int)$request->grade_id,
                'amount' => (int)str_replace(".", "", $request->amount),
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string|min:3',
                'grade_id' => 'required|integer',
                'amount' => 'required|integer|min:10000',
            ]);


            if($validator->fails())
            {
                return redirect('/admin/books/create')->withErrors($validator->messages())->withInput($rules);
            }

            Book::create($rules);

            return redirect('/admin/books');
        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function pageEdit($id)
    {
        try {
            //code...

            $book = Book::where('id', $id)->first();
            $grade = Grade::orderBy('id', 'asc')->get();

            return view('components.book.update-book')->with('book', $book)->with('grade', $grade);
            
        } catch (Exception $err) {
            //throw $th;
            return dd($err);
        }
    }

    public function actionUpdate(Request $request, $id) 
    {
        try {
            //code...
            $rules = [
                'name' => $request->name,
                'grade_id' => (int)$request->grade_id,
                'amount' => (int)str_replace(".", "", $request->amount),
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string|min:3',
                'grade_id' => 'required|integer',
                'amount' => 'required|integer|min:10000',
            ]);


            if($validator->fails())
            {
                return redirect('/admin/books/edit' .'/'. $id)->withErrors($validator->messages())->withInput($rules);
            }

            Book::where('id', $id)->update($rules);
            
            return redirect('/admin/books');

        } catch (Exception $err) {
            //throw $th;
            return dd($err);
        }
    }


    public function detail($id) 
    {
        try {
            //code...
            $data = Book::with('grade')->where('id', $id)->first();


            return view('components.book.detail-book')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function destroy($id)
    {
        try {
            //code...

            if(!Book::where('id', $id)->first())
            {
                return abort(404);
            }

            Book::where('id', $id)->delete();

            return (object)[
                'status' => true,
             ];

        } catch (Exception $err) {
            //throw $th;
            return (object)[
                'status' => false,
             ];
        }
    }
}
