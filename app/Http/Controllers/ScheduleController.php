<?php

namespace App\Http\Controllers;

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
use App\Models\Type_schedule;
use App\Models\Schedule;
use App\Models\Subtitute_teacher;
use App\Models\Master_schedule_academic;
use App\Models\Master_academic;


use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{

   public function allScheduleSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'all schedules',
         ]);
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', 1)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule')
            ->where('type_schedule_id', '!=', '1')
            ->get();

         $typeSchedule = Type_schedule::where('name', '=', 'lesson')->value('id');

         // dd($typeSchedule);

         $gradeSchedules = Schedule::where('type_schedule_id', $typeSchedule)
            ->where('semester', '=', 1)
            ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->select('schedules.*', 'schedules.semester as semester',
            'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
            'subjects.id as subject_id', 'subjects.name_subject as subject_name',
            'teachers.id as teacher_id', 'teachers.name as teacher_name')
            ->get()
            ->groupBy(function($item) {
               return $item->grade_name . '-' . $item->grade_class;
            });

         $gradeSchedulestwo = Schedule::where('type_schedule_id', $typeSchedule)
            ->where('semester', '=', 2)
            ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->select('schedules.*', 'schedules.semester as semester',
            'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
            'subjects.id as subject_id', 'subjects.name_subject as subject_name',
            'teachers.id as teacher_id', 'teachers.name as teacher_name')
            ->get()
            ->groupBy(function($item) {
               return $item->grade_name . '-' . $item->grade_class;
            });

         $semester1 = Master_academic::value('semester1');
         $endsemester1 = Master_academic::value('end_semester1');
         $semester2 = Master_academic::value('semester2');
         $endsemester2 = Master_academic::value('end_semester2');

         // dd($gradeSchedules);

         return view('components.schedule.all-schedule', compact('exams', 'schedules', 'gradeSchedules', 'gradeSchedulestwo', 'semester1', 'semester2', 'endsemester1', 'endsemester2'));

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules academic',
         ]);
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', 1)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $getIdLesson = Type_schedule::where('name', '=', 'Lesson')->value('id');

         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '!=', $getIdLesson)
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();

         $typeSchedule = Type_schedule::where('name', '!=', 'Lesson')->get();

         // dd($schedules);

         return view('components.schedule.schedule', compact('exams', 'schedules'))->with('data', $typeSchedule);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleTeacherSchools($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules academic',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');         
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', 1)
            ->where('exams.teacher_id', $getIdTeacher)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule')
            ->get();

         $typeSchedule = Type_schedule::where('name', '!=', 'Lesson')->get();

         // dd($schedules);

         return view('components.schedule.schedule', compact('exams', 'schedules'))->with('data', $typeSchedule);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleStudentSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules academic',
         ]);

         $id = session('id_user');

         $getGradeStudent = Student::where('user_id', $id)->value('grade_id');      
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', 1)
            ->where('grade_exams.grade_id', $getGradeStudent)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $getIdLesson = Type_schedule::where('name', '=', 'Lesson')->value('id');
         
         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '!=', $getIdLesson)   
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();

         $typeSchedule = Type_schedule::where('name', '!=', 'Lesson')->get();

         return view('components.schedule.schedule', compact('exams', 'schedules'))->with('data', $typeSchedule);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleGrades()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $dataGrade = Grade::with(['student', 'teacher', 'subject', 'schedule'])
            ->withCount(['student as active_student_count', 'teacher as active_teacher_count', 
            'subject as active_subject_count', 'schedule as active_schedule_count'])
            ->get();
         
         $typeSchedule = Type_schedule::get();

         $dataSchedule = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule_name')
            ->where('type_schedule_id', '!=', 1)
            ->get();

         $data = [
            'grade' => $dataGrade,
            'typeSchedule' => $typeSchedule,
            'otherSchedule' => $dataSchedule,
         ];

         // dd($data);
         return view('components.schedule.data-schedule')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   // menampilkan schedule tiap class
   public function detail($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $semester = Master_academic::first()->value('now_semester');
         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }
         
         $gradeSchedule = Schedule::where('grade_id', $id)
         ->where('semester', $semester)
         ->join('grades', 'grades.id', '=', 'schedules.grade_id')
         ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
         ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
         ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
         ->select(
            'schedules.*',
            'grades.id as grade_id',
            'grades.name as grade_name',
            'grades.class as grade_class',
            't1.name as teacher_name',
            't2.name as teacher_companion',
            't2.id as teacher_companion_id',
            'subjects.id as subject_id',
            'subjects.name_subject as subject_name'
         )
         ->get();

         $subtituteTeacher = Subtitute_teacher::where('grade_id', $id)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
               'grades.id as grade_id',
               'grades.name as grade_name', 
               'grades.class as grade_class', 
               't1.name as teacher_name',
               't2.name as teacher_companion',
               't2.id as teacher_companion_id',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name')
            ->get();

         // dd($gradeSchedule);

         $data = Grade::where('id', $id)
            ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
            ->first();

         $teacher = Teacher::get();
         $grade   = Grade::get();

         // dd($subtituteTeacher);
         // dd($startSemester);

         return view('components.schedule.detail-schedule', compact('gradeSchedule', 'subtituteTeacher', 'endSemester', 'startSemester'))->with('data', $data)->with('teacher', $teacher)->with('grade', $grade);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function managePage($gradeId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules grade',
      ]);

      try {
         $data = Schedule::where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     't1.id as teacher_id',
                     't1.name as teacher_name',
                     't2.id as teacher_companion_id',
                     't2.name as teacher_companion_name',   
                     'subjects.name_subject as subject_name')
            ->get();

         $dataSubtitute = Subtitute_teacher::where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     't1.id as teacher_id',
                     't1.name as teacher_name',
                     't2.id as teacher_companion_id',
                     't2.name as teacher_companion_name',   
                     'subjects.name_subject as subject_name')
            ->get();

            // dd($dataSubtitute);
         return view('components.schedule.data-schedule-grade')->with('data', $data)->with('subtituteTeacher', $dataSubtitute);

         // dd($data);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function manageOtherSchedulePage(){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules academic',
      ]);

      try {
         $data = Schedule::where('type_schedule_id', '!=', 11)
            ->leftJoin('type_schedules','type_schedules.id', '=', 'schedules.type_schedule_id')
            ->select('schedules.*', 'type_schedules.name as type_schedule')
            ->get();

         $typeSchedule = Type_schedule::get();
         return view('components.schedule.data-other-schedule')->with('data', $data)->with('typeSchedule', $typeSchedule);

      } catch (Exception $err) {
         dd($err);
      }
   }

   public function editPage($gradeId, $scheduleId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules grade',
      ]);

      try {
         $data = Schedule::where('schedules.id', $scheduleId)
         ->leftJoin('type_schedules', 'type_schedules.id', '=', 'schedules.type_schedule_id')
         ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
         ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
         ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
         ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
         ->select('schedules.*', 
            'type_schedules.id as type_schedules_id', 
            'type_schedules.name as type_schedule_name',
            'grades.id as grade_id',
            'grades.name as grade_name',
            'grades.class as grade_class',
            'subjects.id as subject_id',
            'subjects.name_subject as subject_name',
            't1.id as teacher_id',
            't1.name as teacher_name',
            't2.id as teacher_companion_id',
            't2.name as teacher_companion_name')
         ->get();

         // dd($data);
         $teacher = Teacher::get();

         return view('components.schedule.edit-schedule')->with('data', $data)->with('gradeId', $gradeId)->with('teacher', $teacher);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function editPageSubtitute($gradeId, $scheduleId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules grade',
      ]);

      try {
         $data = Subtitute_teacher::where('subtitute_teachers.id', $scheduleId)
         ->leftJoin('type_schedules', 'type_schedules.id', '=', 'subtitute_teachers.type_schedule_id')
         ->leftJoin('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
         ->leftJoin('subjects', 'subjects.id', '=', 'subtitute_teachers.subject_id')
         ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
         ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
         ->select('subtitute_teachers.*', 
            'type_schedules.id as type_schedules_id', 
            'type_schedules.name as type_schedule_name',
            'grades.id as grade_id',
            'grades.name as grade_name',
            'grades.class as grade_class',
            'subjects.id as subject_id',
            'subjects.name_subject as subject_name',
            't1.id as teacher_id',
            't1.name as teacher_name',
            't2.id as teacher_companion_id',
            't2.name as teacher_companion_name')
         ->get();

         // dd($data);
         $teacher = Teacher::get();
         $grade   = Grade::get();

         return view('components.schedule.edit-schedule-subtitute')->with('data', $data)->with('gradeId', $gradeId)->with('teacher', $teacher)->with('grade', $grade);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function actionUpdateOtherSchedule(Request $request, $scheduleId)
   {
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules academic',
         ]);

         $rules = [
            'type_schedule_id' => 'required',
            'date'             => 'required|date',
            'end_date'         => 'nullable|date',
            'note'             => 'required',
            'updated_at'       => 'nullable',
        ];
        
        $data = [
            'type_schedule_id' => $request->type_schedule,
            'date'             => $request->date,
            'end_date'         => $request->end_date,
            'note'             => $request->notes,
            'updated_at'       => now(),
        ];

         $role = session('role');

         $check = Schedule::where('type_schedule_id', $request->type_schedule)
            ->where('date', $request->date)
            ->where('end_date', $request->end_date)
            ->where('note', $request->notes)
            ->where('id', '!=', $scheduleId)
            ->first();

        if ($check) {
            DB::rollBack();
            return redirect('/' . $role . '/schedules/schools/manage/otherSchedule')
                ->withErrors(['subject_id' => ["The schedule for this subject, grade, start time, and end time is already created."]])
                ->withInput($data);
        }

         Schedule::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_schedule');

         return redirect('/'.$role.'/schedules/schools/manage/otherSchedule' );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }
   
   public function actionUpdateGradeSchedule(Request $request, $gradeId, $scheduleId)
   {
      // dd($request);
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules academic',
         ]);
        
        $data = [
            'teacher_companion' => $request->teacher_companion,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'note'             => $request->notes,
            'updated_at'        => now(),
        ];

         $role = session('role');

         Schedule::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_grade_schedule');

         return redirect('/' . $role . '/schedules/manage'. '/' . $gradeId );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   public function actionUpdateGradeScheduleSubtitute(Request $request, $gradeId, $scheduleId)
   {
      // dd($request);
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules academic',
         ]);
        
        $data = [
            'teacher_id'        => $request->teacher_id,
            'teacher_companion' => $request->teacher_companion,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'note'             => $request->notes,
            'updated_at'        => now(),
        ];

         $role = session('role');

         Subtitute_teacher::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_grade_schedule');

         return redirect('/' . $role . '/schedules/manage'. '/' . $gradeId );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   // Menampilkan page tambah jadwal grade
   public function create($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'database schedules',
         ]);

         $gradeSubject = Grade_subject::where('grade_id', $id)
            ->join('subjects', 'subjects.id' , '=', 'grade_subjects.subject_id')
            ->get();
         $gradeTeacher = Teacher_grade::where('grade_id', $id)
            ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->get();
         $grade = Grade::where('id', $id)->get();
         $teacher = Teacher::get();
         $subject = Subject::get();

         $typeSchedule = Type_schedule::where('name', 'lesson')->get();

         $data = [
            'gradeSubject' => $gradeSubject,
            'gradeTeacher' => $gradeTeacher,
            'grade' => $grade,
            'teacher' => $teacher,
            'subject' => $subject,
            'typeSchedule' => $typeSchedule,
         ];


         if (strtolower($grade[0]['name']) === "primary" || strtolower($grade[0]['name']) === "seondary") {
            return view('components.schedule.create-schedule')->with('data', $data);
         }
         else {
            return view('components.schedule.create-schedule-except')->with('data', $data);   
         }


      } catch (Exception $err) {
         dd($err);
      }
   }

   // Menambahkan jadwal grade
   public function actionCreate(Request $request)
   {
      // dd($request);
      try {
         session()->flash('page', (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $role = session('role');


         for ($i=0; $i < count($request->notes) ; $i++) { 
            if ($request->teacher_id[$i] && $request->teacher_companion[$i]) {
               if (Schedule::where('day', $request->day[$i])
               ->where('teacher_id', $request->teacher_id[$i])
               ->where('teacher_companion', $request->teacher_companion[$i])
               ->where('start_time', $request->start_time[$i])
               ->where('end_time', $request->end_time[$i])
               ->exists()) {
                  return redirect('/' . $role . '/schedules/grade/create/' . $request->grade_id)
                  ->withErrors(['notes' => 'Teacher Subject Or Teacher Companion has same schedules in other grade.'])
                  ->withInput();
               }
            }
            
            if(Schedule::where('day', $request->day[$i])
                  ->where('grade_id', $request->grade_id)
                  ->where('note', $request->notes[$i])
                  ->where('start_time', $request->start_time[$i])
                  ->where('end_time', $request->end_time[$i])
                  ->where('semester', $request->semester[$i])
                  ->exists()) {
                  return redirect('/' . $role . '/schedules/grade/create/' . $request->grade_id)
                     ->withErrors(['notes' => 'Schedules has already been created for this day.'])
                     ->withInput();
            }

            $post = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'teacher_id' => $request->teacher_id[$i],
               'teacher_companion' => $request->teacher_companion[$i],
               'type_schedule_id' => $request->type_schedule,
               'note' => $request->notes[$i],
               'day' => $request->day[$i],
               'semester' => $request->semester,
               'start_time' => $request->start_time[$i],
               'end_time' => $request->end_time[$i],
            ];
            
            DB::beginTransaction();
            
            Schedule::create($post);
            
            DB::commit();
         } 

         session()->flash('after_create_grade_schedule');

         return redirect('/' . $role . '/schedules/detail/' . $request->grade_id);
      } catch (Exception $err) {
         dd($err);
         return redirect()->back()->withErrors(['error' => $err->getMessage()])->withInput();
      }
   }

   // Menambahkan jadwal lainnya (event,hari libur)
   public function actionCreateOther(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'database schedules',
         ]);

         if($request->end_date)
         {
            $rules = [
               'type_schedule_id' => $request->type_schedule,
               'date' => $request->date,
               'end_date' => $request->end_date,
               'note' => $request->notes,
            ];   
         }
         else {
            $rules = [
               'type_schedule_id' => $request->type_schedule,
               'date' => $request->date,
               'note' => $request->notes,
            ];
         }

         $validator = Validator::make($rules, [
               'note' => 'required|string',
            ],
         );

         $role = session('role');
         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/'.  $role .'/schedules/create')->withErrors($validator->messages())->withInput($rules);
         }

         if(Schedule::where('date', $request->date)->where('note', $request->note)->first())
         {
            DB::rollBack();
            return redirect('/'.  $role .'/schedules/create')->withErrors([
               'name_subject' => 'Schedules ' . $request->note .  ' is has been created ',
            ])->withInput($rules);
         }  

        session()->flash('after_create_otherSchedule');

        Schedule::create($rules);

        DB::commit();

        return redirect('/'. $role . '/schedules/schools');


      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   // Menampilkan jadwal guru
   public function scheduleGradeTeacher($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');
         $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->get();


         if (count($getGradeId) > 1) {

            $data = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
               ->get();
            $totalClass = count($getGradeId);
            return view('components.schedule.detail-grade-teacher')->with('data', $data)->with('totalClass', $totalClass);
         } else {
            $getIdTeacher = Teacher::where('user_id', $id)->value('id');
            $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');


            $gradeSchedule = Schedule::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'schedules.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'schedules.teacher_id')
                        ->whereNotNull('schedules.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'schedules.subject_id')
                        ->whereNotNull('schedules.subject_id');
               })
               ->select('schedules.*', 
                        'grades.id as grade_id',
                        'grades.name as grade_name', 
                        'grades.class as grade_class', 
                        'teachers.name as teacher_name', 
                        'subjects.id as subject_id',
                        'subjects.name_subject as subject_name')
               ->get();
   
            $subtituteTeacher = Subtitute_teacher::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'subtitute_teachers.teacher_id')
                        ->whereNotNull('subtitute_teachers.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                        ->whereNotNull('subtitute_teachers.subject_id');
               })
               ->select('subtitute_teachers.*', 
                        'grades.id as grade_id',
                        'grades.name as grade_name', 
                        'grades.class as grade_class', 
                        'teachers.name as teacher_name', 
                        'subjects.id as subject_id',
                        'subjects.name_subject as subject_name')
               ->get();
   
            $data = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
               ->first();
   
            $teacher = Teacher::get();
            $totalClass = 1;
            
            // dd($getGradeId);
   
            return view('components.schedule.detail-grade-teacher', compact('gradeSchedule', 'subtituteTeacher'))->with('data', $data)->with('teacher', $teacher)->with('totalClass', $totalClass);
         }


      } catch (Exception $err) {
         dd($err);
      }
   }

   public function scheduleSubjectTeacher($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules subject',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');
         $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');

         $gradeSchedule = Schedule::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $subtituteTeacher = Subtitute_teacher::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         // dd($subtituteTeacher);

         return view('components.schedule.detail-subject-teacher', compact('gradeSchedule', 'subtituteTeacher'))->with('data', $gradeSchedule);

      } catch (Exception $err) {
         dd($err);
      }
   }

   public function detailScheduleTeacher($teacherId, $gradeId)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $getIdTeacher = Teacher::where('user_id', $teacherId)->value('id');

         $gradeSchedule = Schedule::where('teacher_id', $getIdTeacher)
            ->where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->join('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->join('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->select('schedules.*', 'grades.name as grade_name', 'grades.class as grade_class', 
            'teachers.name as teacher_name', 'subjects.name_subject as subject_name')
            ->get();

         // dd($gradeSchedule);

         $data = Schedule::where('teacher_id', $getIdTeacher)
            ->where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->join('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->join('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class' ,'subjects.id as subject_id')
            ->get();

         return view('components.schedule.detail-schedule', compact('gradeSchedule'))->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleStudent()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         // dd(session('id_user'));

         $checkRole = session('role');

         if($checkRole == 'parent'){
            $userId = session('id_user');
            $parentId = Relationship::where('user_id', $userId)->value('id');
            $getIdStudent = Student_relationship::where('relationship_id', $parentId)->value('student_id');
            $getGradeId= Student::where('id', $getIdStudent)->value('grade_id');
         }
         elseif($checkRole == 'student'){
            $userId = session('id_user');
            $getIdStudent = Student::where('user_id', $userId)->value('id');
            $getGradeId = Student::where('id', $getIdStudent)->value('grade_id');
         }
         
         // dd($getGradeId);
         
         $gradeSchedule = Schedule::where('grade_id', $getGradeId)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers', function($join) {
               $join->on('teachers.id', '=', 'schedules.teacher_id')
                     ->whereNotNull('schedules.teacher_id');
            })
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     'teachers.name as teacher_name', 
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $subtituteTeacher = Subtitute_teacher::where('grade_id', $getGradeId)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers', function($join) {
               $join->on('teachers.id', '=', 'subtitute_teachers.teacher_id')
                     ->whereNotNull('subtitute_teachers.teacher_id');
            })
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     'teachers.name as teacher_name', 
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $data = Grade::where('id', $getGradeId)
            ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
            ->first();

         return view('components.student.detail-grade-student', compact('gradeSchedule', 'subtituteTeacher'))->with('data', $data);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   // Menghapus data jadwal grade
   public function delete($id)
   {
      try {
         session()->flash('after_delete_schedule');
         $gradeId = Schedule::where('id', $id)->value('grade_id');
         Schedule::where('id', $id)->delete();


         return redirect('/'. session('role') .'/schedules/manage/'. $gradeId);
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/' . session('role'). '/subjects')->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function deleteSubtitute($id)
   {
      try {
         session()->flash('after_delete_schedule_subtitute');
         $gradeId = Subtitute_teacher::where('id', $id)->value('grade_id');
         Subtitute_teacher::where('id', $id)->delete();

         return redirect('/'. session('role') .'/schedules/manage/'. $gradeId);
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/' . session('role'). '/subjects')->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function deleteOtherSchedule($id)
   {
      try {

         session()->flash('after_delete_schedule');
         Schedule::where('id', $id)->delete();

         return redirect('/' . session('role') .'/schedules/schools/manage/otherSchedule');
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/'. session('role') .'/schedules/schools/manage/otherSchedule')->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function subtituteTeacher(Request $request)
   {
      // Validate the request data
      $getSubjectId = Subject::where('name_subject', $request->subject_id)->value('id');
      $getTypeSchedule = Type_schedule::where('name', 'like', '%lesson%')->value('id');

      $data = [
         'grade_id' => $request->grade_id,
         'subject_id' => $getSubjectId,
         'teacher_id' => $request->teacher_id,
         'teacher_companion' => $request->teacher_companion,
         'type_schedule_id' =>  $getTypeSchedule,
         'note' => null,
         'date' => $request->date,
         'day' => $request->day,
         'start_time' => $request->start_time,
         'end_time' => $request->end_time,
         'created_at' => now(),
      ];

      session()->flash('after_subtitute_teacher_schedule');
      
      DB::beginTransaction();
      Subtitute_teacher::create($data);
      DB::commit();  

      return response()->json(['Successfully subtitute teacher']);
   }

   public function scheduleGradeTeacherOther($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

            $getIdTeacher = Teacher_grade::where('grade_id', $id)->value('teacher_id');
            $getGradeId = $id;
            
            $gradeSchedule = Schedule::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'schedules.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'schedules.teacher_id')
                        ->whereNotNull('schedules.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'schedules.subject_id')
                        ->whereNotNull('schedules.subject_id');
               })
               ->select('schedules.*', 
                        'grades.id as grade_id',
                        'grades.name as grade_name', 
                        'grades.class as grade_class', 
                        'teachers.name as teacher_name', 
                        'subjects.id as subject_id',
                        'subjects.name_subject as subject_name')
               ->get();
   
            $subtituteTeacher = Subtitute_teacher::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'subtitute_teachers.teacher_id')
                        ->whereNotNull('subtitute_teachers.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                        ->whereNotNull('subtitute_teachers.subject_id');
               })
               ->select('subtitute_teachers.*', 
                        'grades.id as grade_id',
                        'grades.name as grade_name', 
                        'grades.class as grade_class', 
                        'teachers.name as teacher_name', 
                        'subjects.id as subject_id',
                        'subjects.name_subject as subject_name')
               ->get();
   
            $data = Teacher_grade::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
               ->first();
   
            $teacher = Teacher::get();

            // dd($data);
   
            return view('components.schedule.detail-grade-teacher-other', compact('gradeSchedule', 'subtituteTeacher'))->with('data', $data)->with('teacher', $teacher);

      } catch (Exception $err) {
         dd($err);
      }
   }
}
