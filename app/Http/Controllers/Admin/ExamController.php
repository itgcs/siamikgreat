<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Teacher_subject;
use App\Models\Teacher_grade;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Subject_exam;
use App\Models\Exam_relation;
use App\Models\Type_exam;
use App\Models\Score;
use App\Models\Student_exam;
use App\Models\Relationship;
use App\Models\Student_relationship;


use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
   public function index(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $form = (object) [
            'sort' => $request->sort? $request->sort : null,
            'order' => $request->order? $request->order : null,
            'status' => $request->status? $request->status : null,
            'search' => $request->search? $request->search : null,
            'type' => $request->type? $request->type:  null,
         ];

         $sort = $request->sort ? $request->sort : 'asc';
         $status = $request->status? ($request->status == 'true' ? true : false) : true;

         $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', $status)
            
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->paginate(15);

         return view('components.exam.data-exam')->with('data', $data)->with('form', $form);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pageCreate()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $dataTeacher = Teacher::get();
         $dataSubject = Subject::get();
         $dataGrade   = Grade::get();
         $dataType    = Type_exam::get();

         $data = [
            'teacher' => $dataTeacher,
            'subject' => $dataSubject,
            'grade' => $dataGrade,
            'type_exam' => $dataType,  
         ];

         // dd($data);

         return view('components.exam.create-exam')->with('data', $data);
         
      } catch (Exception) {
         return abort(500);
      }
   }
   
   public function actionPost(Request $request)
   {
      // DB::beginTransaction();
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $rules = [
            'type_exam' => $request->type_exam,
            'name_exam' => $request->name,
            'is_active' => 1,
            'date_exam' => $request->date_exam,
            'materi' => $request->materi,
            'teacher_id' => $request->teacher_id,
            'created_at' => now(),
         ];

         $validator = Validator::make($rules, [
               'type_exam' => 'required|string',
               'name_exam' => 'required|string',
               'date_exam' => 'required|date',
               'materi' => 'required|string',
               'teacher_id' => 'required|string',
            ],
         );

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/'.session('role').'/exams/create')->withErrors($validator->messages())->withInput($rules);
         }
      
         if(Exam::where('name_exam', $request->name)->where('teacher_id', $request->teacher_id)->first())
         {
            DB::rollBack();
            return redirect('/'.session('role').'/exams/create')->withErrors([
               'name' => 'Exams ' . $request->name . $request->subject_id . $request->grade_id . 'for' . $request->teacher_id .' is has been created ',
            ])->withInput($rules);
         }
         
         $post = [
            'type_exam' => $request->type_exam,
            'name_exam' => $request->name,
            'date_exam' => $request->date_exam,
            'materi' => $request->materi,
            'teacher_id' => $request->teacher_id,
            'created_at' => now(),
            'is_active' => 1,
            'semester' => $request->semester,
            'academic_year' => session('academic_year'),
         ];

         Exam::create($post);

         $getLastIdExam = DB::table('exams')->latest('id')->value('id');

         $postSubjectExam = [
            'subject_id' => $request->subject_id,
            'exam_id' => $getLastIdExam,
            'academic_year' => session('academic_year'),
            'created_at' => now(),
         ];

         $postGradeExam = [
            'grade_id' => $request->grade_id,
            'exam_id' => $getLastIdExam,
            'academic_year' => session('academic_year'),
            'created_at' => now(),
         ];

         Subject_exam::create($postSubjectExam);
         Grade_exam::create($postGradeExam);

         $checkSubject = Subject_exam::where('subject_exams.exam_id', '=', $getLastIdExam)->value('subject_id');
         $subject = Subject::where('id', $checkSubject)->value('name_subject');



         if (strtolower($subject) == "religion islamic") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
                                    ->where('religion', '=', 'islam')
                                    ->pluck('id')->toArray();

            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "religion catholic") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
                                    ->where('religion', '=', 'Catholic Christianity')
                                    ->pluck('id')->toArray();

            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "religion christian") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
                                    ->where('religion', '=', 'Protestant Christianity')
                                    ->pluck('id')->toArray();

            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "religion buddhism") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
                                    ->where('religion', '=', 'Buddhism')
                                    ->pluck('id')->toArray();

            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "religion hinduism") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
                                    ->where('religion', '=', 'Hinduism')
                                    ->pluck('id')->toArray();

            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "religion confucianism") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
                                    ->where('religion', '=', 'Confucianism')
                                    ->pluck('id')->toArray();

            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "chinese lower") {
            $chineseLowerStudent = Chinese_lower::where('grade_id', $request->grade_id)->pluck('student_id')->toArray();

            // $getStudentId = Student::where("grade_id", $request->grade_id)->pluck('id')->toArray();
            
            for ($i=0; $i < sizeof($chineseLowerStudent); $i++) { 
               $postStudentExam = [
                  'student_id' => $chineseLowerStudent[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $chineseLowerStudent[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         elseif (strtolower($subject) == "chinese hingher") {
            $chineseHigherStudent = Chinese_higher::where('grade_id', $request->grade_id)->pluck('student_id')->toArray();

            // $getStudentId = Student::where("grade_id", $request->grade_id)->pluck('id')->toArray();
            
            for ($i=0; $i < sizeof($chineseHigherStudent); $i++) { 
               $postStudentExam = [
                  'student_id' => $chineseHigherStudent[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $chineseHigherStudent[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }
         else {
            $getStudentId = Student::where("grade_id", $request->grade_id)->pluck('id')->toArray();
   
            for ($i=0; $i < sizeof($getStudentId); $i++) { 
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
   
               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
               
               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }

         session()->flash('after_create_exam');

         if (session('role') == 'superadmin') {
            return redirect('/superadmin/exams');
         }
         elseif (session('role') == 'admin') {
            return redirect('/admin/exams');
         }
         elseif (session('role') == 'teacher') {
            return redirect('/teacher/dashboard/exam/teacher');
         }

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function getById($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'database teacher exams',
         'child' => 'database teacher exams',
      ]);

      try {
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         if(session('role') == 'admin'){
            return view('components.exam.detail-exam')->with('data', $data);
         }
         elseif (session('role') == 'teacher') {
            return view('components.teacher.detail-exam-teacher')->with('data', $data);
         }
      } catch (Exception $err) {  
         dd($err);
         return abort(404);
      }
   }

   public function getByIdSession()
   {
      session()->flash('page',  $page = (object)[
         'page' => 'database exam',
         'child' => 'exams',
      ]);

      $id = session('exam_id');

      try {
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         if (session('role') == 'student' || 'parent'){
            return view('components.student.detail-exam-student')->with('data', $data);
         }
      } catch (Exception $err) {  
         dd($err);
         return abort(404);
      }
   }

   public function pageEdit($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'database teacher exams',
            'child' => 'database teacher exams',
         ]);

         $dataExam = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class','subjects.id as subject_id', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.id as type_exam_id','type_exams.name as type_exam')
            ->first();

         $teacher    = Teacher::orderBy('id', 'ASC')->get();
         $subject    = Subject::orderBy('id', 'ASC')->get();
         $grade      = Grade::orderBy('id', 'ASC')->get();
         $typeExam   = Type_exam::orderBy('id', 'ASC')->get();

         $data = [
            'teacher'   => Teacher::orderBy('id', 'ASC')->get(),
            'subject'   => Subject::orderBy('id', 'ASC')->get(),
            'grade'     => Grade::orderBy('id', 'ASC')->get(),
            'typeExam'  => Type_exam::orderBy('id', 'ASC')->get(),
            'dataExam'  => $dataExam,
         ];
         
         // dd($data);
         if(session('role') == 'admin' || session('role') == 'superadmin'){
            return view('components.exam.edit-exam')->with('data', $data);
         }
         elseif (session('role') == 'teacher') {
            return view('components.teacher.edit-exam-teacher')->with('data', $data);
         }
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function actionPut(Request $request, $id)
   {
      // DB::beginTransaction();
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $rules = [
            'name_exam'  => $request->name,
            'type_exam'  => $request->type_exam,
            'date_exam'  => $request->date_exam,
            'materi'     => $request->materi,
            'teacher_id' => $request->teacher_id,
            'semester'   => $request->semester,
            'academic_year' => session('academic_year'),
            'updated_at' => now(),
         ];

         $validator = Validator::make($rules, [
            'name_exam'  => 'required|string',
            'type_exam'  => 'required|string',
            'date_exam'  => 'required|date',
            'materi'     => 'required|string',
            'teacher_id' => 'required|string',
         ]);

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/teacher/dashboard/exams/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
         }
         
         $check = Exam::where('name_exam', $request->name)->where('teacher_id', $request->teacher_id)->first();

         if($check && $check->id != $id)
         {
            DB::rollBack();
            return redirect('/teacher/dashboard/exams/edit/' . $id)->withErrors(['name' => ["The exam name " . $request->name . " with grade " . $request->grade_name . " subject " . $request->Grade_subject . " teacher " . $request->teacher_name ." is already created !!!"]])->withInput($rules);
         }

         // dd($rules);

         Subject_exam::where('exam_id', $id)->update([
            'subject_id' => $request->subject_id,
         ]);
         Grade_exam::where('exam_id', $id)->update([
            'grade_id' => $request->grade_id,
         ]);
         Exam::where('id', $id)->update($rules);
         
         DB::commit();

         session()->flash('after_update_exam');

         if(session('role') == 'superadmin'){
            return redirect('/superadmin/exams');
         }
         elseif(session('role') == 'admin'){
            return redirect('/admin/exams');
         }
         elseif (session('role') == 'teacher') {
            return redirect('/teacher/dashboard/exam/teacher');
         }

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function teacherExam()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database teacher exams',
            'child' => 'database teacher exams',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');

         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.teacher_id', $getIdTeacher)
            ->where('exams.semester', session('semester'))
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->orderBy('exams.created_at', 'desc')
            ->paginate(10);

         // dd($data);

         return view('components.teacher.data-exam-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function detailTeacherExam($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'database exam',
         'child' => 'database exam',
      ]);

      try {
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         // dd($data);

         return view('components.teacher.detail-exam')->with('data', $data);
      } catch (Exception $err) {  
         dd($err);
         return abort(404);
      }
   }

   public function createTeacherExam()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'database teacher exams',
            'child' => 'database teacher exams',
         ]);

         $id = session('id_user');

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');
         
         $dataTeacher = Teacher::where('id', $getIdTeacher)->get();

         $dataSubject = Teacher_subject::join('subjects','subjects.id','=','teacher_subjects.subject_id')
            ->where('teacher_subjects.teacher_id', $getIdTeacher)
            ->select('subjects.*')
            ->orderBy('subjects.name_subject', 'asc')
            ->get();

         $dataGrade = Teacher_subject::join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
            ->where('teacher_subjects.teacher_id', $getIdTeacher)
            ->select('grades.*')
            ->distinct('grades.name')
            ->orderBy('grades.id', 'asc')
            ->get();

         $dataType    = Type_exam::orderBy('name', 'asc')->get();

         $data = [
            'teacher' => $dataTeacher,
            'subject' => $dataSubject,
            'grade' => $dataGrade,
            'type_exam' => $dataType,  
         ];

         // dd($data);

         return view('components.teacher.create-exam-teacher')->with('data', $data);
         
      } catch (Exception $err) {
         dd($err);
         return abort(500);
      }
   }

   public function gradeExam()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'exams',
        ]);

         if(session('role') == 'parent')
         {
            $getIdUser         = session('id_user');
            $id                = Relationship::where('user_id', $getIdUser)->value('id');
            $getIdStudent      = session('studentId');
            $gradeIdStudent    = Student::where('id', $getIdStudent)->value('grade_id');

            // $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            //    ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            //    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            //    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            //    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            //    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            //    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            //    ->join('scores', function($join) {
            //       $join->on('student_exams.student_id', '=', 'scores.student_id')
            //           ->on('exams.id', '=', 'scores.exam_id');
            //   })
            //    ->where('grade_exams.grade_id', $gradeIdStudent)
            //    ->where('scores.student_id', $getIdStudent)
            //    ->where('grade_exams.academic_year', session('academic_year'))
            //    ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            //    ->paginate(15);

            // $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            //    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            //    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            //    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            //    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            //    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            //    ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
            //    ->join('students', 'student_exams.student_id', '=', 'students.id')
            //    ->join('scores', function($join) {
            //        $join->on('student_exams.student_id', '=', 'scores.student_id')
            //            ->on('exams.id', '=', 'scores.exam_id');
            //    })
            //    ->where('scores.student_id', $getIdStudent)
            //    ->where('exams.academic_year', session('academic_year'))
            //    ->where('exams.semester', session('semester'))
            //    ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class',
            //     'subjects.name_subject as subject_name', 'teachers.name as teacher_name',
            //      'type_exams.name as type_exam' , 'scores.score as score')
            //    ->paginate(15);

            $data = Score::join('exams', 'exams.id', '=', 'scores.exam_id')
               ->join('grades', 'scores.grade_id', '=', 'grades.id')
               ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
               ->join('teachers', 'scores.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'scores.type_exam_id', '=', 'type_exams.id')
               ->join('students', 'scores.student_id', '=', 'students.id')
               ->where('scores.student_id', $getIdStudent)
               ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class',
                  'subjects.name_subject as subject_name', 'teachers.name as teacher_name',
                  'type_exams.name as type_exam' , 'scores.score as score', 'students.name as student_name')
               ->orderBy('created_at', 'asc')
               ->paginate(15);
         } 
         elseif (session('role') == 'student') 
         {
            $getIdUser     = session('id_user');
            $id            = Student::where('user_id', $getIdUser)->value('grade_id');
            $getGradeId    = Grade::where('id', $id)->value('id');
            $getIdStudent  = Student::where('user_id', $getIdUser)->value('id');

            // dd($getGradeId);
   
            $data = Score::join('exams', 'exams.id', '=', 'scores.exam_id')
               ->join('grades', 'scores.grade_id', '=', 'grades.id')
               ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
               ->join('teachers', 'scores.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'scores.type_exam_id', '=', 'type_exams.id')
               ->join('students', 'scores.student_id', '=', 'students.id')
               ->where('scores.student_id', $getIdStudent)
               ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class',
                  'subjects.name_subject as subject_name', 'teachers.name as teacher_name',
                  'type_exams.name as type_exam' , 'scores.score as score', 'students.name as student_name')
               ->orderBy('date_exam', 'desc')
               ->paginate(15);
         }

         // dd($data);

         return view('components.student.data-exam-student')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function detailGradeExam($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'database exam',
         'child' => 'database exam',
      ]);

      try {
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         // dd($data);

         return view('components.teacher.detail-exam')->with('data', $data);
      } catch (Exception $err) {  
         dd($err);
         return abort(404);
      }
   }

   public function pagePDF($id)
   {
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

   public function doneExam($id){
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $rules = [
            'is_active' => 0,
         ];
         
         Exam::where('id', $id)->update($rules);

         session()->flash('after_done_exam');

         if(session('role') == 'superadmin'){
            return redirect('superadmin/exams');
         }
         elseif (session('role') == 'admin') {
            return redirect('admin/exams');
         }
         elseif (session('role') == 'teacher') {
            return redirect('teacher/dashboard/exam');
         }

      } catch (Exception $err) {
         dd($err);
      }
   }

   public function setAssessmentId(Request $request)
   {
      session(['exam_id' => $request->id]);

      return response()->json(['success' => true]);
   }

}