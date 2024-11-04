<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Grade_exam;
use App\Models\Grade_subject;
use App\Models\Subject_exam;
use App\Models\Exam;
use App\Models\Student_relationship;
use App\Models\Relationship;
use App\Models\Attendance;
use App\Models\Student_eca;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   
   public function index()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'dashboard',
            'child' => 'dashboard',
         ]);
      
         $checkRole = session('role');
         // dd(session('id_user'));

         if($checkRole == 'admin' ||$checkRole == 'superadmin')
         {
            $totalStudent  = Student::where('is_active', true)->orderBy('created_at', 'desc')->get()->count('id');
            $totalTeacher  = Teacher::where('is_active', true)->get()->count('id');
            $totalGrade    = Grade::all()->count('id');
            $totalExam     = Exam::get()->count('id');
            
            $studentData   = Student::where('is_active', true)
            ->join('grades', 'grades.id', '=', 'students.grade_id')
            ->select('students.*', 'grades.name as grade_name', 'grades.class as grade_class')
            ->get();
            
            $teacherData   = Teacher::where('is_active', true)->get();
            
            $examData  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
               ->get();

            foreach ($examData as $ed ) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
               ->where('exam_id', $ed->id)
               ->value('name_subject');
            };

            $gradeData     = Grade::all();
            $subjectData   = Subject::all();

            $data = [
               'totalStudent' => (int)$totalStudent,
               'totalTeacher' => (int)$totalTeacher,
               'totalGrade'   => (int)$totalGrade,
               'totalExam'    => (int)$totalExam,
               'grade' => $gradeData,
               'subject' => $subjectData,
               'exam' => $examData,
               'dataTeacher' => $teacherData,
               'dataStudent' => $studentData,
            ];

            // dd($data);
            return view('components.dashboardtes')->with('data', $data);
         }
         elseif($checkRole == 'teacher')
         {
            $id = Teacher::where('user_id', session('id_user'))->value('id');
            
            $dataTeacher  = Teacher::where('id', $id)->get();
            $gradeTeacher = Teacher_grade::join('grades', 'teacher_grades.grade_id', '=', 'grades.id')
               ->where('teacher_id', $id)
               ->select('grades.*')
               ->get();
            
            $teacherSubject = Teacher_subject::join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
               ->join('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
               ->where('teacher_id', $id)
               ->select('subjects.*',
                  DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name"))
               ->get();

            $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
               ->where('exams.teacher_id', $id)
               ->where('exams.semester', session('semester'))
               ->where('exams.academic_year', session('academic_year'))
               ->get();

            foreach ($dataExam as $ed ) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
               ->where('exam_id', $ed->id)
               ->value('name_subject');
            };

            $student = Teacher_grade::join('students','students.grade_id', '=', 'teacher_grades.grade_id')
               ->join('grades', 'grades.id', '=', 'students.grade_id')
               ->where('teacher_id', $id)
               ->where('is_active', true)
               ->select('students.*', 'grades.name as grade_name', 'grades.class as grade_class')
               ->get();

            $totalStudent = Teacher_grade::join('students','students.grade_id', '=', 'teacher_grades.grade_id')
               ->where('teacher_id', $id)
               ->get()
               ->count('id');

            $totalExam = Exam::where('teacher_id', $id)
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->get()->count('id');

            $totalGrade     = Teacher_grade::where('teacher_id', $id)->get()->count('id');
            $totalSubject   = Teacher_subject::where('teacher_id', $id)->get()->count('id');
            
            $data = [
               'dataTeacher'    => $dataTeacher,
               'gradeTeacher'   => $gradeTeacher,
               'teacherSubject' => $teacherSubject,
               'student'        => $student,
               'exam'           => $dataExam,
               'totalStudent'   => (int)$totalStudent,
               'totalExam'      => (int)$totalExam,
               'totalGrade'     => (int)$totalGrade,
               'totalSubject'   => (int)$totalSubject,
            ];

            // dd($data);

            return view('components.dashboard-teacher')->with('data',$data);
         }
         elseif($checkRole == 'student')
         {
            $id = Student::where('user_id', session('id_user'))->value('id');
            $gradeIdStudent = Student::where('user_id', session('id_user'))->value('grade_id');


            $dataStudent       = Grade::with(['subject', 'exam', 'teacher', 'student'])->where('id', $gradeIdStudent)->first();
            
            $totalExam         = Grade_exam::join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->where('grade_id', $gradeIdStudent)
               ->get()
               ->count('id');

            $totalSubject      = Grade_subject::where('grade_id', $gradeIdStudent)->get()->count('id');
            $totalStudentGrade = Student::where('grade_id', $gradeIdStudent)->get()->count('id');
            $totalAbsent       = Attendance::where('student_id', $id)
            ->where('present', 0)
            ->get()
            ->count();

            $eca = Student_eca::where('student_id', $id)
               ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
               ->get();

            
            $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
               ->where('grades.id', $gradeIdStudent)
               ->get();

            foreach ($dataExam as $ed ) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
               ->where('exam_id', $ed->id)
               ->value('name_subject');
            };

            $data = [
               'eca'          => $eca,
               'dataStudent'  => $dataStudent,
               'exam'         => $dataExam,
               'totalExam'    => (int)$totalExam,
               'totalSubject' => (int)$totalSubject,
               'totalStudent' => (int)$totalStudentGrade, 
               'totalAbsent'  => (int)$totalAbsent,  
            ];

            // dd($data);

            return view('components.dashboard-student')->with('data',$data);
         }
         elseif($checkRole == 'parent')
         {
            $id              = Relationship::where('user_id', session('id_user'))->value('id');
            $setStudentFirst = session('studentId');
            $getIdStudent    = Student_relationship::where('relationship_id', $id)->pluck('student_id')->toArray();

            $getDataStudent = Student::whereIn('students.id', $getIdStudent)
               ->leftJoin('grades', 'grades.id', '=', 'students.grade_id')
               ->select('students.name as student_name', 'students.id as student_id',
               'grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class')
               ->get();

            $detailStudent = Student::where('students.id', $setStudentFirst)
               ->leftJoin('grades', 'grades.id', '=', 'students.grade_id')
               ->select('students.name as student_name', 'students.id as student_id',
               'grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class')
               ->first();

            $eca = Student_eca::where('student_id', $setStudentFirst)
               ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
               ->select('ecas.name as eca_name')
               ->get();

            // dd($detailStudent);
            // dd($getDataStudent);

            $gradeIdStudent  = Student::where('students.id', $setStudentFirst)->value('grade_id');

            $dataStudent       = Grade::with(['subject', 'exam', 'teacher', 'student'])->where('grades.id', $gradeIdStudent)->first();
            $totalExam         = Grade_exam::where('grade_id', $gradeIdStudent)->get()->count('id');
            $totalSubject      = Grade_subject::where('grade_id', $gradeIdStudent)->get()->count('id');
            $totalStudentGrade = Student::where('grade_id', $gradeIdStudent)->get()->count('id');

            $totalAbsent       = Attendance::where('student_id', $setStudentFirst)
               ->where('present', 0)
               ->get()
               ->count();
            
            $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
               ->where('grades.id', $gradeIdStudent)
               ->where('exams.is_active', 1)
               ->orderBy('date_exam', 'asc')
               ->get();

            foreach ($dataExam as $ed ) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
               ->where('exam_id', $ed->id)
               ->value('name_subject');
            };

            $data = [
               'eca'           => $eca,
               'totalRelation' => $getDataStudent,
               'detailStudent' => $detailStudent,
               'dataStudent'   => $dataStudent,
               'exam'          => $dataExam,
               'totalExam'     => (int)$totalExam,
               'totalSubject'  => (int)$totalSubject,
               'totalStudent'  => (int)$totalStudentGrade, 
               'totalAbsent'   => (int)$totalAbsent,  
            ];

            // dd($data);

            return view('components.dashboard-parent')->with('data',$data);
         }

      } catch (Exception $err) {
         return dd($err);
      }
   }
}