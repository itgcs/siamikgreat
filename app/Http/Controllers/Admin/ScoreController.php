<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Subject_exam;
use App\Models\Score;
use App\Models\Acar;
use App\Models\Teacher;
use App\Models\Teacher_subject;
use App\Models\Major_subject;
use App\Models\Minor_subject;
use App\Models\Supplementary_subject;
use App\Models\Type_exam;
use App\Models\Grade;
use App\Models\Comment;

use App\Models\Sooa_primary;
use App\Models\Sooa_secondary;
use App\Models\Chinese_higher;
use App\Models\Chinese_lower;
use App\Models\Teacher_grade;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
   public function score($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database scorings',
            'child' => 'scorings',
         ]);

         $checkSubject = Subject_exam::where('subject_exams.exam_id', '=', $id)->value('subject_id');
         $subject = Subject::where('id', $checkSubject)->value('name_subject');

         // dd($subject);

         if (strtolower($subject) == "religion islamic") {
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->where('students.religion', '=', 'islam')
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
         }
         elseif (strtolower($subject) == "religion catholic") {
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->where('students.religion', '=', 'Catholic Christianity')
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
         }
         elseif (strtolower($subject) == "religion christian") {
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->where('students.religion', '=', 'Protestant Christianity')
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
         }
         elseif (strtolower($subject) == "religion buddhism") {
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->where('students.religion', '=', 'Buddhism')
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
         }
         elseif (strtolower($subject) == "religion hinduism") {
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->where('students.religion', '=', 'Hinduism')
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
         }
         elseif (strtolower($subject) == "religion confucianism") {
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->where('students.is_active', true)
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->where('students.religion', '=', 'Confucianism')
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
         }
         elseif (strtolower($subject) == "chinese lower") {
            $chineseLowerStudent = Chinese_lower::pluck('student_id')->toArray();
            
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->whereIn('students.id', $chineseLowerStudent)
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();
        }
        elseif (strtolower($subject) == "chinese higher") {
            $chineseHigherStudent = Chinese_higher::pluck('student_id')->toArray();

            // dd($chineseHigherStudent);
            
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
               ->join('students', 'student_exams.student_id', '=', 'students.id')
               ->join('scores', function($join) {
                  $join->on('student_exams.student_id', '=', 'scores.student_id')
                     ->on('exams.id', '=', 'scores.exam_id');
               })
               ->where('exams.id', $id, 'exams.is_active')
               ->whereIn('students.id', $chineseHigherStudent)
               ->where('students.is_active', true)
               ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
               'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id', 
               'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
               'students.id as student_id', 'students.name as student_name',
               'scores.score as score')
               ->orderBy('student_name', 'asc')
               ->get();

            // dd($data);
        }
         else{
            $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
            ->join('students', 'student_exams.student_id', '=', 'students.id')
            ->join('scores', function($join) {
               $join->on('student_exams.student_id', '=', 'scores.student_id')
                  ->on('exams.id', '=', 'scores.exam_id');
            })
            ->where('exams.id', $id, 'exams.is_active')
            ->where('students.is_active', true)
            ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
            'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
            'subjects.name_subject as subject_name', 'subjects.id as subject_id',
            'teachers.name as teacher_name', 'teachers.id as teacher_id', 
            'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
            'students.id as student_id', 'students.name as student_name',
            'scores.score as score')
            ->orderBy('student_name', 'asc')
            ->get();
         }

         return view('components.exam.data-exam-score')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }
  
   public function actionUpdateScore(Request $request)
    {
        try {
            session()->flash('page', $page = (object)[
                'page' => 'exams',
                'child' => 'database exams',
            ]);

            $students = $request->student_id;
            $scores = $request->score;
            // Update scores for each student
            for ($i = 0; $i < count($students); $i++) {
                $post = [
                    'score' => $scores[$i],
                    'updated_at' => now(),
                ];

                Score::where('student_id', $students[$i])
                    ->where('exam_id', $request->exam_id)
                    ->update($post);
            }
            // Mark exam as inactive
            Exam::where('id', $request->exam_id)->update(['is_active' => 0]);

            // Calculate and store final scores for Academic Assessment Report
                $userId = session('id_user');
                $gradeId = $request->grade_id;
                $subjectId = $request->subject_id;

                // check apakah major subject
                $majorSubject = Major_subject::select('subject_id')->get();
                $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

                if(session('role') == 'teacher'){
                    $teacherId = Teacher::where('user_id', $userId)->value('id');
                }
                else{
                    $teacherId = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->value('teacher_id');
                }

                $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->first();

                $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->first();

                $subject = Subject::where('id', $subjectId)
                    ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                    ->first();

                // check apakah major subject
                $majorSubject = Major_subject::select('subject_id')->get();
                $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

                $homework = Type_exam::where('name', '=', 'homework')->value('id');
                $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                $participation = Type_exam::where('name', '=', 'participation')->value('id');
                $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                $finalExam = Type_exam::where('name', '=', 'final exam')->value('id'); 
                $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final assessment', 'final exam'])
                    ->pluck('id')
                    ->toArray();

                $semester       = session('semester');
                $academic_year  = session('academic_year');

                if (strtolower($subject) == "religion islamic") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'islam')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                }
                elseif (strtolower($subject) == "religion catholic") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                        ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->leftJoin('subject_exams', function($join){
                            $join->on('subject_exams.exam_id', '=', 'exams.id');
                        })
                        ->leftJoin('scores', function ($join) {
                            $join->on('scores.student_id', '=', 'students.id')
                                ->on('scores.exam_id', '=', 'exams.id');
                        })
                        ->select(
                            'students.id as student_id',
                            'students.name as student_name',
                            'exams.id as exam_id',
                            'exams.type_exam as type_exam',
                            'scores.score as score',
                        )
                        ->where('students.religion', '=', 'catholic christianity')
                        ->where('students.is_active', true)
                        ->where('grades.id', $gradeId)
                        ->where('subject_exams.subject_id', $subjectId)
                        ->where('exams.semester', $semester)
                        ->where('exams.academic_year', $academic_year)
                        ->where('exams.teacher_id', $teacherId)
                        ->orderBy('students.name', 'asc')
                        ->get();
                    
                }
                elseif (strtolower($subject) == "religion christian") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'protestant christianity')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject) == "religion buddhism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'buddhism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject) == "religion hinduism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'hinduism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject) == "religion confucianism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'confucianism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                else{
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                }
                // dd($request);

                // Perhitungan ACAR PRIMARY
                if ($gradeId < 11){
                    // dd($request);
                    // Perhitungan ACAR Primary Major Subject
                    if ($isMajorSubject) {
                        $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalExam) {
                            $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                $subQuery->where('subject_id', $subjectId);
                            });
                        }])
                        ->where('grades.id', $gradeId)
                        ->withCount([
                            'exam as total_homework' => function ($query) use ($subjectId, $homework, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $homework)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_exercise' => function ($query) use ($subjectId, $exercise, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $exercise)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_quiz' => function ($query) use ($subjectId, $quiz, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $quiz)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_final_exam' => function ($query) use ($subjectId, $finalExam, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $finalExam)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_participation' => function ($query) use ($subjectId, $participation, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $participation)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                        ])
                        ->first();
                    
                        $type = "major_subject_assessment";
    
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                            $finalExam = Type_exam::where('name', '=', 'final exam')->value('id');
    
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                            $finalExamScores    = $scores->where('type_exam', $finalExam)->pluck('score');
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework'      => round($homeworkScores->avg()),
                                'avg_exercise'      => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_quiz'          => round($quizScores->avg()),
    
                                'percent_homework'      => round($homeworkScores->avg() * 0.1),
                                'percent_exercise'      => round($exerciseScores->avg() * 0.15),
                                'percent_participation' => round($participationScore->avg() * 0.05),
                                'h+e+p'                 => (round($homeworkScores->avg() * 0.1)) + round(($exerciseScores->avg() * 0.15)) + round(($participationScore->avg() * 0.05)),
                            
                                'percent_quiz' => round($quizScores->avg() * 0.3),
                                'percent_fe'   => round($finalExamScores->avg() * 0.4),
                                'total_score'  => round(($homeworkScores->avg() * 0.1) + ($exerciseScores->avg() * 0.15) + ($participationScore->avg() * 0.05) + ($quizScores->avg() * 0.3) + ($finalExamScores->avg() * 0.4)),
                                
                                'comment' => '',
                            ];
                        })->values()->all();
    
                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
    
                    } 
                    // Perhitungan ACAR Primary Minor & Supplementary Subject
                    else {
                        $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester) {
                            $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                $subQuery->where('subject_id', $subjectId);
                            });
                        }])
                        ->where('grades.id', $gradeId)
                        ->withCount([
                            'exam as total_homework' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $homework)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_exercise' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $exercise)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_quiz' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $quiz)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_final_exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->whereIn('type_exam', $finalAssessment)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_participation' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $participation)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                        ])
                        ->first();
    
                        $type = "minor_subject_assessment";
    
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                            
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $quiz = Type_exam::where('name', '=', 'quiz')->value('id'); 
                            $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final exam', 'final assessment'])
                                ->pluck('id')
                                ->toArray();
    
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalAssessment)->pluck('score');
    
                            $homeworkAvg       = round($homeworkScores->avg()) ?: 0;
                            $exerciseAvg       = round($exerciseScores->avg()) ?: 0;
                            $participationAvg  = round($participationScore->avg()) ?: 0;
                            $quizAvg          = round($quizScores->avg()) ?: 0;
                            $finalExamAvg     = round($finalExamScores->avg()) ?: 0;
    
    
                            $final_score = round(($homeworkAvg * 0.2) + ($exerciseAvg * 0.35) + ($participationAvg * 0.10) + ($finalExamAvg * 0.35));
                            $grade = $this->determineGrade($final_score);
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework' => round($homeworkScores->avg()),
                                'avg_exercise' => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_fe' => round($finalExamScores->avg()),
                                
                                'percent_homework' => round($homeworkScores->avg() * 0.2),
                                'percent_exercise' => round($exerciseScores->avg() * 0.35),
                                'percent_participation' => round($participationScore->avg() * 0.1, 2),
                                'percent_fe' => round($finalExamScores->avg() * 0.35, 2),
                                'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),
    
                                'grades' => $grade,
                                'comment' => '',
                            ];
                        })->values()->all();

                        $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                        $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
            
                        if (strtolower($subject) == "religion islamic" || 
                            strtolower($subject) == "religion catholic" || 
                            strtolower($subject) == "religion christian" || 
                            strtolower($subject) == "religion buddhism" || 
                            strtolower($subject) == "religion hinduism" || 
                            strtolower($subject) == "religion confucianism") {
                            $subjectId = $getReligionId;
                        } else {
                            $subjectId = $request->subject_id;
                        }
    
                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                }
                // Perhitungan ACAR SECONDARY
                else{
                    $checkSubject = Subject::where('id', $request->subject_id)->value('name_subject');

                    if (strtolower($checkSubject) == "chinese higher" || strtolower($checkSubject) == "chinese lower") {
                        $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                        $getChineseId = Subject::where('name_subject', '=', 'chinese')->value('id');
            
                        if (strtolower($subject) == "chinese lower" || 
                            strtolower($subject) == "chinese higher") {
                            $subjectId = $getChineseId;
                        }
                    }
                    elseif (strtolower($checkSubject) == "religion islamic" || 
                            strtolower($checkSubject) == "religion catholic" || 
                            strtolower($checkSubject) == "religion christian" || 
                            strtolower($checkSubject) == "religion buddhism" || 
                            strtolower($checkSubject) == "religion hinduism" || 
                            strtolower($checkSubject) == "religion confucianism") {
                        $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                        $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
            
                        if (strtolower($subject) == "religion islamic" || 
                            strtolower($subject) == "religion catholic" || 
                            strtolower($subject) == "religion christian" || 
                            strtolower($subject) == "religion buddhism" || 
                            strtolower($subject) == "religion hinduism" || 
                            strtolower($subject) == "religion confucianism") {
                            $subjectId = $getReligionId;
                        }
                    }else {
                        $subjectId = $request->subject_id;
                    }
                    // Perhitungan ACAR Secondary Major Subject
                    if(strtolower($subject) !== 'science' &&
                        strtolower($subject) !== 'english' &&
                        strtolower($subject) !== 'mathematics' &&
                        strtolower($subject) !== 'chinese higher' &&
                        strtolower($subject) !== 'chinese lower')
                    {
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores){
                            
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final exam', 'final assessment'])
                                ->pluck('id')
                                ->toArray();
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalAssessment)->pluck('score');

                            $homeworkAvg       = round($homeworkScores->avg()) ?: 0;
                            $exerciseAvg       = round($exerciseScores->avg()) ?: 0;
                            $participationAvg  = round($participationScore->avg()) ?: 0;
                            $finalExamAvg     = round($finalExamScores->avg()) ?: 0;


                            $final_score = round(($homeworkAvg * 0.2) + ($exerciseAvg * 0.35) + ($participationAvg * 0.10) + ($finalExamAvg * 0.35));
                            $grade = $this->determineGrade($final_score);
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework' => round($homeworkScores->avg()),
                                'avg_exercise' => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_fe' => round($finalExamScores->avg()),
                                
                                'percent_homework' => round($homeworkScores->avg() * 0.2),
                                'percent_exercise' => round($exerciseScores->avg() * 0.35),
                                'percent_participation' => round($participationScore->avg() * 0.1),
                                'percent_fe' => round($finalExamScores->avg() * 0.35),
                                
                                'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),

                                'grades' => $grade,
                                'comment' => '',
                            ];
                        })->values()->all();

                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                    // Perhitungan ACAR Secondary selain Major Subject
                    else{
                        $tasks = Type_exam::whereIn('name', ['homework', 'small project', 'presentation', 'exercice', 'Exercise'])
                            ->pluck('id')
                            ->toArray();
                        $mid = Type_exam::whereIn('name', ['quiz', 'practical exam', 'project', 'exam'])
                            ->pluck('id')
                            ->toArray();
                        $finalExam = Type_exam::whereIn('name', ['written tes', 'big project', 'final assessment', 'final exam'])
                            ->pluck('id')
                            ->toArray();
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($tasks, $mid, $finalExam) {
                            $student            = $scores->first();
                            $tasks              = $scores->whereIn('type_exam', $tasks)->pluck('score');
                            $mid                = $scores->whereIn('type_exam', $mid)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalExam)->pluck('score');
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_tasks' => round($tasks->avg()),
                                'avg_mid'   => round($mid->avg()),
                                'avg_fe'    => round($finalExamScores->avg()),
            
                                'percent_tasks' => round($tasks->avg() * 0.25),
                                'percent_mid'  => round($mid->avg() * 0.35),
                                'percent_fe'    => round($finalExamScores->avg() * 0.4),
                                'total_score'   => (round(($tasks->avg() * 0.25)) +  round(($mid->avg() * 0.35)) + round(($finalExamScores->avg() * 0.4))),
                                
                                'comment' => '',
                            ];
                        })->values()->all();

                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                }


                // Perhitungan SOOA Academic
                $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                    ->where('acars.grade_id', $gradeId)
                    ->where('acars.semester', $semester)
                    ->where('acars.academic_year', $academic_year)
                    ->get();
                
                $sooaByStudent = $results->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
                    $majorSubject = Major_subject::pluck('subject_id')->toArray();
                    $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                    $supplementarySubject = Supplementary_subject::pluck('subject_id')->toArray();
                    $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                    $minorSubjectsScores = $scores->whereIn('subject_id', $minorSubject)->pluck('final_score');
                    $supplementarySubjectsScores = $scores->whereIn('subject_id', $supplementarySubject)->pluck('final_score');
                    
                    $sortedScores = $scores->sortBy('subject_id');
                    // dd($majorSubjectsScores);
    
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->map(function ($score) {
                            return [
                                'subject_id' => $score->subject_id,
                                'final_score' => $score->final_score,
                                'grades' => $score->grades,
                            ];
                        })->all(),
                        'percent_majorSubjects' => round($majorSubjectsScores->avg() * 0.7),
                        'percent_minorSubjects' => round($minorSubjectsScores->avg() * 0.2),
                        'percent_supplementarySubjects' => round($supplementarySubjectsScores->avg() * 0.1),
                        'total_score' => round((($majorSubjectsScores->avg() * 0.7) + ($minorSubjectsScores->avg() * 0.2) + $supplementarySubjectsScores->avg() * 0.1)),
                        'comment' => '',
                    ];
                })->values()->all();
                
                // dd($sooaByStudent);

                foreach($sooaByStudent as $sooa){
                    $matchingScoring = [
                        'student_id'         => $sooa['student_id'],
                        'grade_id'           => $gradeId,
                        'class_teacher_id'   => $classTeacher->teacher_id,
                        'semester'           => session('semester'),
                        'academic_year'      => session('academic_year'),
                    ];
                
                    // Data untuk diupdate atau disimpan
                    $updateScoring = [
                        'academic'           => $sooa['total_score'],
                        'grades_academic'    => $this->determineGrade($sooa['total_score']),
                    ];
                
                    // Gunakan updateOrCreate untuk tabel Acar
                    if($gradeId == 11 || $gradeId == 12 || $gradeId == 13){
                        Sooa_secondary::updateOrCreate($matchingScoring, $updateScoring);
                    }
                    else {
                        Sooa_primary::updateOrCreate($matchingScoring, $updateScoring);
                    }
                }


            
            if (session('role') == 'superadmin') {
                return redirect('/superadmin/exams');
            } elseif (session('role') == 'admin') {
                return redirect('/admin/exams');
            } elseif (session('role') == 'teacher') {
                return redirect('/teacher/dashboard/exam/teacher');
            }
        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }


   private function determineGrade($finalScore)
   {
       if ($finalScore >= 95 && $finalScore <= 100) {
           return 'A+';
       } elseif ($finalScore >= 85 && $finalScore <= 94) {
           return 'A';
       } elseif ($finalScore >= 75 && $finalScore <= 84) {
           return 'B';
       } elseif ($finalScore >= 65 && $finalScore <= 74) {
           return 'C';
       } elseif ($finalScore >= 45 && $finalScore <= 64) {
           return 'D';
       } else {
           return 'R';
       }
   }
}
