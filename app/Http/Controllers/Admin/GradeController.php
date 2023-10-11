<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
   public function index()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         $data = Grade::with(['teacher'])->withCount(['student as active_student_count' => function ($query) {
            $query->where('is_active', true);
         }])->get();;
         
         // return $data;

         return view('components.grade.data-grade')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pageCreate()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         $data = Teacher::orderBy('id', 'ASC')->get();

         return view('components.grade.create-grade')->with('data', $data);
         
      } catch (Exception) {
         return abort(500);
      }
   }
   
   public function actionPost(Request $request)
   {

      DB::beginTransaction();

      try {

         $rules = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string|unique:grades',
            'class' => 'required|string|max:15',
            'teacher_id' => 'required|integer',
         ],
         [
            'name.unique' => "The grade " . $request->name . " with class " . $request->class ." is already create !!!",
         ]
      );

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/admin/grades/create')->withErrors($validator->messages())->withInput($rules);
         }

         if(Grade::where('teacher_id', $request->teacher_id)->first())
         {
            Grade::where('teacher_id', $request->teacher_id)->update([
               'teacher_id' => null,
            ]);
         }



         $post = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'class' => $request->class,
         ];
         Grade::create($post);

         DB::commit();
         
         return redirect('/admin/grades');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function detailGrade($id){
      
      try {
         //code...
         
         $data = (object)[
            'gradeTeacher' => Grade::with(['teacher'])->where('id', $id)->first(),
            'gradeStudent' => Student::where('grade_id', $id)->where('is_active', true)->get(),
         ];
         
         // return $data;

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         return view('components.grade.detail-grade')->with('data', $data);
      } catch (Exception $err) {
         
         return abort(404);
      }

   }


   public function pageEdit($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         $teacher = Teacher::orderBy('id', 'asc')->get();
         $data = Grade::where('id', $id)->first();

         return view('components.grade.edit-grade')->with('data', $data)->with('teacher', $teacher);
         
      } catch (Exception $err) {
         return abort(404);
      }
   }


   public function actionPut(Request $request, $id)
   {

      DB::beginTransaction();
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         
         $gradeName = $request->name ? $request->name . ' - ' . $request->class : null;

         $rules = [
            'name' => $gradeName,
            'teacher_id' => $request->teacher_id? $request->teacher_id : null,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            'teacher_id' => 'nullable|integer',
            ]
         );
         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/admin/grades/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
         }
         
         $check = DB::table('grades')->where('name', $gradeName)->first();

         if($check) if($check->id != $id)
         {

            DB::rollBack();
            return redirect('/admin/grades/edit/' . $id)->withErrors(['name' => ["The grade " . $request->name . " with class " . $request->class ." is already create !!!"]])->withInput($rules);
         }
      

         if(Grade::where('teacher_id', $request->teacher_id)->first())
         {
            Grade::where('teacher_id', $request->teacher_id)->update([
               'teacher_id' => null,
            ]);
         }

         $post = [
            'name' => $gradeName,
            'teacher_id' => $request->teacher_id,
         ];

         


         Grade::where('id', $id)->update($post);
         
         DB::commit();

         return redirect('/admin/grades');

      } catch (Exception $th) {
         DB::rollBack();
         return abort(500);
      }
   }


   public function pagePromotion($id)
   {
      try {
         //code...
         $data = Student::with('grade')->where('grade_id', $id)->orderBy('name', 'asc')->get();
         $grade = Grade::where('id', $id)->first();
         
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         return view('components.grade.promotion.page')->with('data', $data)->with('grade', $grade);
         
      } catch (Exception $err) {
         return abort(404);
      }
   }


   public function actionPromotion(Request $request)
   {
      DB::beginTransaction();
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         
         $promoteId = $request->except(['_token', '_method']);

         if(sizeof($promoteId)<=0)
         {
            DB::rollBack();
            return redirect()->back()->withErrors('error');
         }

         $grade = Student::with('grade')->where('id', reset($promoteId))->first();
         $lastest_grade = DB::table('grades')->where('name', $grade->grade->name)->orderBy('id', 'desc')->first();

         
         if($grade->grade->id < $lastest_grade->id)
         {
            foreach ($promoteId as $value) {
               
               Student::where('id', $value)->update([
                  'grade_id' => (int)$grade->grade->id + 1,
               ]);
            }
         } else {
            foreach ($promoteId as $value) {
               
               Student::where('id', $value)->update([
                  'is_active' => 0,
               ]);
            }
         }


         DB::commit();

         return redirect('/admin/grades/');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function pagePDF($id){
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         
         $data = Grade::with(['student' => function ($query) {
               $query->where('is_active', true)->orderBy('name', 'asc');
         }])->find($id);

         $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999).'_'.date('d-m-Y').'_'.$data->name.'_'.$data->class.'.pdf';

	      $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.grade.pdf.dom-pdf', ['data' => $data])->setPaper('a4', 'portrait');
         return $pdf->stream($nameFormatPdf);

      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}