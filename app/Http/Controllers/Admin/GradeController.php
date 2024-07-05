<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;

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

         $data = Grade::with(['student', 'teacher', 'exam', 'subject'])
            ->withCount(['student as active_student_count', 'teacher as active_teacher_count', 'exam as active_exam_count', 'subject as active_subject_count'])
            ->get();

         // dd($data);
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

         $data = [
            'teacher' => Teacher::get(),
            'subject' => Subject::get(),
         ];

         Teacher::orderBy('id', 'ASC')->get();

         return view('components.grade.create-grade')->with('data', $data);
         
      } catch (Exception) {
         return abort(500);
      }
   }

   public function pageAddSubjectTeacher($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         $data = [
            'teacher' => Teacher::get(),
            'subject' => Subject::get(),
            'grade' => Grade::where('id', '=', $id)->get(),
         ];

         return view('components.grade.add-subject-grade')->with('data', $data);
         
      } catch (Exception) {
         return abort(500);
      }
   }
   
   public function actionPost(Request $request)
   {
      try {
         $rules = [
            'name' => $request->name,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            ],
         );

         $role = session('role');

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/'. $role .'/grades/create')->withErrors($validator->messages())->withInput($rules);
         }
         
         if(Grade::where('name', $request->name)->where('class', $request->class)->first())
         {
            DB::rollBack();
            return redirect('/'. $role .'/grades/create')->withErrors([
               'name' => 'Grades ' . $request->name . ' class ' . $request->class . ' is has been created ',
            ])->withInput($rules);
         }
            
         $post = [
            'name' => $request->name,
            'class' => $request->class,
            'created_at' => now(),
         ];
      
         Grade::create($post);
         DB::commit();

         $getIdLastGrade = Grade::latest('id')->value('id');
         // menyimpan class teacher
         $teacher_class = [
            'teacher_id' => $request->teacher_class_id,
            'grade_id'   => $getIdLastGrade,
            'created_at' => now(),
         ];
         $dataTeacherGrade = Teacher_grade::create($teacher_class);


         // menyimpan grade subject & subject teacher
         for($i = 0; $i < count($request->subject_id); $i++){
            // Simpan data subjek dan kelasnya
            $teacher_subject = [
               'teacher_id' => $request->teacher_subject_id[$i],
               'subject_id' => $request->subject_id[$i],
               'grade_id'   => $getIdLastGrade,
               'created_at' => now(),
            ];

            $grade_subject = [
               'grade_id' => $getIdLastGrade,
               'subject_id' => $request->subject_id[$i]
            ];

            $dataTeacherSubject = Teacher_subject::create($teacher_subject);
            $dataGradeSubject = Grade_subject::create($grade_subject);
         } 

      
         session()->flash('after_create_grade');
         return redirect('/' .$role. '/grades');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function actionPostAddSubjectGrade(Request $request)
   {
      dd($request);
      try {
         for ($i=0; $i < count($request->subject_id); $i++) { 
            if(Grade_subject::where('grade_id', $request->grade_id)->where('subject_id', $request->subject_id[$i])->exists())
            {
               DB::rollBack();
               return redirect('/'. session('role').'/grades/manageSubject/addSubject/' . $request->grade_id)
               ->with('sweetalert', [
                  'title' => 'Error',
                  'text' => 'Subject Grade has been created',
                  'icon' => 'error'
               ]);
            }
         
            $teacher_subject = [
               'teacher_id' => $request->teacher_subject_id[$i],
               'subject_id' => $request->subject_id[$i],
               'grade_id'   => $request->grade_id,
               'created_at' => now(),
            ];

            $grade_subject = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'created_at' => now(),
            ];

            $dataTeacherSubject = Teacher_subject::create($teacher_subject);
            $dataGradeSubject = Grade_subject::create($grade_subject);
         }
      
         session()->flash('after_add_subject_grade');
         return redirect('/' .session('role'). '/grades/manageSubject/' . $request->grade_id);

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function detailGrade($id){
      session()->flash('page',  $page = (object)[
         'page' => 'grades',
         'child' => 'database grades',
      ]);

      $gradeTeacher = Teacher_grade::where('grade_id',$id)
         ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
         ->get();

      $gradeExam = Grade_exam::join('exams', 'exams.id','=', 'grade_exams.exam_id')
         ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
         ->select('exams.*', 'type_exams.name as type_exam_name')
         ->where('grade_exams.grade_id', $id)
         ->get();

      $gradeSubject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
         ->where('grade_subjects.grade_id', $id)
         ->pluck('name_subject')->toArray();
         // ->select('subjects.id as subject_id','subjects.name_subject as subject_name')
         // ->get();

      // dd($gradeSubject);

      $subjectTeacher = Teacher_subject::where('grade_id', $id)
         ->join('teachers', 'teachers.id', 'teacher_subjects.teacher_id')
         ->join('subjects', 'subjects.id', 'teacher_subjects.subject_id')
         ->select('subjects.id as subject_id','teachers.name as teacher_name')
         ->get();

      $gradeStudent = Student::where('grade_id', $id)->where('is_active', true)->get();
      
      try {
         $data = (object)[
            'gradeTeacher' => $gradeTeacher,
            'gradeStudent' => $gradeStudent,
            'gradeSubject' => $gradeSubject,
            'gradeExam' => $gradeExam, 
            'subjectTeacher' => $subjectTeacher, 
         ];

         // dd($data->gradeSubject);
         return view('components.grade.detail-grade')->with('data', $data);
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
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         // ambil data teacher yang mengajar di class
         // $teacherGrade = Grade::findOrFail($id)->teacher()->get();
         $teacherGrade = Teacher_grade::where('grade_id', $id)->pluck('teacher_id')->toArray();
         $subjectGrade = Grade_subject::where('grade_id', $id)->pluck('subject_id')->toArray();

         // dd(count($subjectGrade));
         $teacher = Teacher::orderBy('id', 'asc')->get();
         $subject = Subject::orderBy('id', 'asc')->get();         
         $data    = Grade::where('id', $id)->first();
         $gradeSubject = Subject::get();
         $allTeacher = Teacher::get();
         $gradeId = $id;
         
         // dd($subjectGrade);

         // dd($teacher);
         return view('components.grade.edit-grade')->with('data', $data)->with('teacher', $teacher)->with('subject', $subject)->with('teacherGrade', $teacherGrade)->with('subjectGrade', $subjectGrade)->with('allTeacher', $allTeacher)->with('gradeId', $gradeId);
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pageEditSubject($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         // ambil data teacher yang mengajar di class
         // $teacherGrade = Grade::findOrFail($id)->teacher()->get();
         $teacherGrade = Teacher_grade::where('grade_id', $id)->pluck('teacher_id')->toArray();
         $subjectGrade = Teacher_subject::where('grade_id', $id)
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.grade_id as grade_id',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->get();

         // dd(count($subjectGrade));
         $teacher = Teacher::orderBy('id', 'asc')->get();
         $subject = Subject::orderBy('id', 'asc')->get();         
         $data    = Grade::where('id', $id)->get();
         $gradeSubject = Subject::get();
         $allTeacher = Teacher::get();
         $gradeId = $id;


         // dd($subjectGrade);
         return view('components.grade.edit-subject')->with('data', $data)->with('teacher', $teacher)->with('subject', $subject)->with('teacherGrade', $teacherGrade)->with('subjectGrade', $subjectGrade)->with('allTeacher', $allTeacher)->with('gradeId', $gradeId);
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pageEditSubjectTeacher($id, $subjectId, $teacherId)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
               'page' => 'database grades',
               'child' => 'database grades',
         ]);
         
         $data = Teacher_subject::where('grade_id', $id)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacherId)
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as teacher_subject_id',
               'grades.name as grade_name', 'grades.id as grade_id',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->first();

         $teacher = Teacher::get();
         $subject = Subject::get();
         
         dd($data);
         return view('components.grade.page-edit-subject')->with('data', $data)->with('subject', $subject)->with('teacher', $teacher);
         
      } catch (Exception $err) {
         dd($err);
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

         $rules = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id? $request->teacher_id : null,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            ]
         );

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/'.session('role').'/grades/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
         }

         $data = [
            'teacher' => $request->teacher_id,
            'subject' => $request->subject_id,
         ];
         
         $check = Grade::where('name', $request->name)->where('class', $request->class)->first();

         if($check && $check->id != $id)
         {

            DB::rollBack();
            return redirect('/admin/grades/edit/' . $id)->withErrors(['name' => ["The grade " . $request->name . " with class " . $request->class ." is already created !!!"]])->withInput($rules);
         }


      // HANDLE TEACHER  //
               // Ambil semua teacher_id sebelum diupdate
               $teacherGradesBeforeUpdate = Teacher_grade::where('grade_id', $id)->pluck('teacher_id')->toArray();

               //check apakah teacher tidak kosong
               if($request->teacher_id != null)
               {
                  // Loop melalui teacher_id dari form
                  foreach ($request->teacher_id as $tc) 
                  {
                     // Jika teacher_id tidak ada di dalam array sebelumnya, tambahkan baru
                     if (!in_array($tc, $teacherGradesBeforeUpdate)) {
                        Teacher_grade::create([
                              'grade_id' => $id,
                              'teacher_id' => $tc,
                        ]);
                     }
                  }
                  // Hapus teacher_id yang tidak ada di dalam array yang dikirim melalui form
                  if($request->teacher_id == null)
                  {
                     foreach($teacherGradesBeforeUpdate as $tg) 
                     {
                        Teacher_grade::where('grade_id', $id)
                        ->where('teacher_id', $tg)
                        ->delete();
                     }
                  } 
                  else 
                  {
                     foreach ($teacherGradesBeforeUpdate as $tg) {
                        if (!in_array($tg, $request->teacher_id)) {
                           Teacher_grade::where('grade_id', $id)
                                 ->where('teacher_id', $tg)
                                 ->delete();
                        }
                     }
                  }
               }
               // apabila teacher kosong
               else 
               {
                  // Hapus teacher_id yang tidak ada di dalam array yang dikirim melalui form
                  if($request->teacher_id == null)
                  {
                     foreach($teacherGradesBeforeUpdate as $tg) {
                        Teacher_grade::where('grade_id', $id)
                        ->where('teacher_id', $tg)
                        ->delete();
                     }
                  } 
                  else 
                  {
                     foreach ($teacherGradesBeforeUpdate as $tg) {
                        if (!in_array($tg, $request->teacher_id)) {
                           Teacher_grade::where('grade_id', $id)
                                 ->where('teacher_id', $tg)
                                 ->delete();
                        }
                     }
                  }
               }
      // END HANDLE TEACHER //


      // HANDLE SUBJECT //
               $subjectGradesBeforeUpdate = Grade_subject::where('grade_id', $id)->pluck('subject_id')->toArray();
               
               // check apakah subject tidak kosong
               if($request->subject_id != null)
               {
                  foreach($request->subject_id as $su) 
                  {
                     // Jika subject_id tidak ada di dalam array sebelumnya, tambahkan baru
                     if (!in_array($su, $subjectGradesBeforeUpdate)) 
                     {
                        Grade_subject::create([
                              'grade_id' => $id,
                              'subject_id' => $su,
                        ]);
                     }
                  }
                  // Hapus subject_id yang tidak ada di dalam array yang dikirim melalui form
                  if($request->subject_id == null)
                  {
                     foreach ($subjectGradesBeforeUpdate as $sg) 
                     {
                        Grade_subject::where('grade_id', $id)
                        ->where('subject_id', $sg)
                        ->delete();
                     }
                  }
                  else 
                  {
                     foreach ($subjectGradesBeforeUpdate as $sg) 
                     {
                        if (!in_array($sg, $request->subject_id)) 
                        {
                           Grade_subject::where('grade_id', $id)
                                 ->where('subject_id', $sg)
                                 ->delete();
                        }
                     }
                  }
               }
               // apabila subject kosong
               else 
               {
                  // Hapus subject_id yang tidak ada di dalam array yang dikirim melalui form
                  if($request->subject_id == null)
                  {
                     foreach ($subjectGradesBeforeUpdate as $sg) 
                     {
                        Grade_subject::where('grade_id', $id)
                        ->where('subject_id', $sg)
                        ->delete();
                     }
                  }
                  else 
                  {
                     foreach ($subjectGradesBeforeUpdate as $sg) 
                     {
                        if (!in_array($sg, $request->subject_id)) 
                        {
                           Grade_subject::where('grade_id', $id)
                                 ->where('subject_id', $sg)
                                 ->delete();
                        }
                     }
                  }
               }
      // END HANDLE SUBJECT //

         
         DB::commit();

         session()->flash('after_update_grade');

         if(session('role') == 'superadmin'){
            return redirect('/superadmin/grades');
         }
         else {
            return redirect('/admin/grades');
         }


      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         // return abort(500);
      }
   }

   public function actionPutSubjectTeacher(Request $request, $id)
   {
         // dd($id);
        DB::beginTransaction();

        try {
            session()->flash('page',  $page = (object)[
                'page' => 'grades',
                'child' => 'database grades',
            ]);

            $rules = [
                'subject_id' => $request->subject,
                'teacher_id' => $request->teacher,
                'updated_at' => now(),
            ];

            $role = session('role');

            if(Grade_subject::where('grade_id', $request->grade_id)
            ->where('subject_id', $request->subject_id)
            ->exists()){
               DB::rollBack();
               return redirect()->back()->with('role', session('role'))->withErrors(['subject_id' => ["The subject is already created !!!"]])->withInput($rules);
            }

            $gradeSubject = [
               'subject_id' => $request->subject_id,
               'updated_at' => now(),
            ];

            Grade_subject::where('grade_id', $request->grade_id)
               ->where('subject_id', $request->before_subject_id)
               ->update($gradeSubject);

            if(Teacher_subject::where('subject_id', $request->subject_id)
            ->where('grade_id', $request->grade_id)
            ->exists())
            {
               DB::rollBack();
               return redirect()->back()->with('role', session('role'))->withErrors(['subject_id' => ["The subject is already created !!!"]])->withInput($rules);
            }

            Teacher_subject::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_subject_teacher');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

   

   public function teacherGrade($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'teacher grades',
            'child' => 'database teacher grades',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');

         $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
            ->select('grades.*', )
            ->get();

            foreach ($gradeTeacher as $gt) {
               $gt->students = Student::where('grade_id', $gt->id)->get();
           }

         $data = [
            'gradeTeacher' => $gradeTeacher,
         ];

         // dd($data);

         return view('components.teacher.data-grade-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function studentGrade($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'student grades',
            'child' => 'database student grades',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');

         $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
            ->select('grades.*', )
            ->get();

            foreach ($gradeTeacher as $gt) {
               $gt->students = Student::where('grade_id', $gt->id)->get();
           }

         $data = [
            'gradeTeacher' => $gradeTeacher,
         ];

         // dd($data);

         return view('components.teacher.data-grade-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function delete($id)
   {
      try {
         // Hapus data grade
         $teacher = Grade::findOrFail($id);
         $teacher->delete();

         // Hapus data terkait (Teacher_grade, Teacher_subject)
         Teacher_grade::where('teacher_id', $id)->delete();
         Teacher_subject::where('teacher_id', $id)->delete();

         session()->flash('after_delete_grade');

         return redirect('/superadmin/grades');
      } catch (Exception $err) {
         dd($err);
         return redirect('/superadmin/grades');
      }
   }

   public function deleteSubjectGrade($gradeId, $subjectId, $teacherId)
   {
      try {
         // Hapus data terkait (Teacher_subject dan Grade_subject)
         
         Grade_subject::where('grade_id', $gradeId)
            ->where('subject_id', $subjectId)
            ->delete();

         Teacher_subject::where('teacher_id', $teacherId)
            ->where('grade_id', $gradeId)
            ->where('subject_id', $subjectId)
            ->delete();


         session()->flash('after_delete_subject_grade');

         return redirect('/'. session('role') .'/grades/manageSubject/' . $gradeId);
      } catch (Exception $err) {
         dd($err);
         return redirect('/'. session('role') .'/grades/manageSubject/' . $gradeId);
      }
   }
}