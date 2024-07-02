<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Relationship;
use App\Models\Student_relationship;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Major_subject;
use App\Models\Minor_subject;
use App\Models\Supplementary_subject;
use App\Models\Comment;
use App\Models\Acar_comment;
use App\Models\Acar;
use App\Models\Sooa_primary;
use App\Models\Sooa_secondary;
use App\Models\Report_card;
use App\Models\Report_card_status;
use App\Models\Scoring_status;
use App\Models\Acar_status;
use App\Models\Sooa_status;

use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index(){
        try {
            //code...
            session()->flash('page',  $page = (object)[
               'page' => 'reports',
               'child' => 'database reports',
            ]);
   
            $grade = grade::get();
   
                foreach ($grade as $gt) {
                    $gt->students = Student::where('grade_id', $gt->id)->get();
                }
   
            $primary  = Grade::with(['student', 'subject'])
                ->join('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
                ->leftJoin('teachers', function ($join) {
                    $join->on('teachers.id', '=', 'teacher_grades.teacher_id');
                })
                ->whereIn('grades.id', [5, 6, 7, 8, 9, 10])
                ->select('grades.id as id', 'grades.name as grade_name', 'grades.class as grade_class',
                'teachers.name as teacher_class')
                ->withCount(['student as active_student_count', 'subject as active_subject_count'])
                ->orderBy('grades.id', 'asc')
                ->get();


            $secondary = Grade::with(['student', 'subject'])
                ->join('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
                ->leftJoin('teachers', function ($join) {
                    $join->on('teachers.id', '=', 'teacher_grades.teacher_id');
                })
                ->whereIn('grades.id', [11, 12 ,13])
                ->select('grades.id as id', 'grades.name as grade_name', 'grades.class as grade_class',
                'teachers.name as teacher_class')
                ->withCount(['student as active_student_count', 'subject as active_subject_count'])
                ->orderBy('grades.id', 'asc')
                ->get();

            $data = [
               'grade'     => $grade,
               'primary'   => $primary,
               'secondary' => $secondary,
            ];

            // dd($data);
   
            return view('components.report.data-report')->with('data', $data);
   
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function detailSubjectClass($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);
  
            $subject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
                ->leftJoin('teacher_subjects', function ($join) {
                    $join->on('teacher_subjects.subject_id', '=', 'grade_subjects.subject_id')
                        ->on('teacher_subjects.grade_id', '=', 'grade_subjects.grade_id');
                })
                ->leftJoin('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->where('grade_subjects.grade_id', $id)
                ->select(
                    'subjects.id as subject_id',
                    'teachers.id as teacher_id',
                    'subjects.name_subject as subject_name',
                    'teachers.name as teacher_name',
                )
                ->get();

            $grade = grade::where('id', $id)->get();

            $data = [
                'grade' => $grade,
                'subject' => $subject,
            ];

            // dd($data);   
            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.subject-teacher')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail-report')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function detailSubjectClassSec($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);
  
            $subject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
                ->leftJoin('teacher_subjects', function ($join) {
                    $join->on('teacher_subjects.subject_id', '=', 'grade_subjects.subject_id')
                        ->on('teacher_subjects.grade_id', '=', 'grade_subjects.grade_id');
                })
                ->leftJoin('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->where('grade_subjects.grade_id', $id)
                ->select(
                    'subjects.id as subject_id',
                    'teachers.id as teacher_id',
                    'subjects.name_subject as subject_name',
                    'teachers.name as teacher_name',
                )
                ->get();

            $grade = grade::where('id', $id)->get();


            $data = [
                'grade' => $grade,
                'subject' => $subject,
            ];

            // dd($data);   
            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.subject-teacher-sec')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail-report-sec')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }
    
    // Melihat seluruh nilai siswa berdasarkan kelas & mapel Primary
    public function detailSubjectClassStudent($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();

            // check apakah major subject
            $majorSubject = Major_subject::select('subject_id')->get();
            $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

            $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId) {
                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                    $subQuery->where('subject_id', $subjectId);
                });
            }])
            ->where('grades.id', $gradeId)
            ->withCount([
                'exam as total_homework' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 1);
                },
                'exam as total_exercise' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 2);
                },
                'exam as total_quiz' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 3);
                },
                'exam as total_final_exam' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 4);
                },
                'exam as total_participation' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 5);
                },
            ])
            ->first();

            $semester = session('semester');

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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $subjectTeacher->teacher_id)
                ->get();

            if ($isMajorSubject) {
            
                $type = "major_subject_assessment";

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {

                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', 1)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', 2)->pluck('score');
                    $participationScore = $scores->where('type_exam', 5)->pluck('score');
                    $quizScores         = $scores->where('type_exam', 3)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', 4)->pluck('score');
                    
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
                        'total_score'  => (round(($homeworkScores->avg() * 0.1)) + round(($exerciseScores->avg() * 0.15)) + round(($participationScore->avg() * 0.05))) + round(($quizScores->avg() * 0.3)) + round(($finalExamScores->avg() * 0.4)),
                        
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            } else {

                $type = "minor_subject_assessment";

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
                    
                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', 1)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', 2)->pluck('score');
                    $participationScore = $scores->where('type_exam', 5)->pluck('score');
                    $quizScores         = $scores->where('type_exam', 3)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', 4)->pluck('score');

                    $homeworkAvg       = $homeworkScores->avg() ?: 0;
                    $exerciseAvg       = $exerciseScores->avg() ?: 0;
                    $participationAvg  = $participationScore->avg() ?: 0;
                    $quizAvg          = $quizScores->avg() ?: 0;
                    $finalExamAvg     = $finalExamScores->avg() ?: 0;

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
                        'avg_homework' => $homeworkScores->avg(),
                        'avg_exercise' => $exerciseScores->avg(),
                        'avg_participation' => $participationScore->avg(),
                        'avg_fe' => $finalExamScores->avg(),
                        
                        'percent_homework' => $homeworkScores->avg() * 0.2,
                        'percent_exercise' => $exerciseScores->avg() * 0.35,
                        'percent_participation' => $participationScore->avg() * 0.1,
                        'percent_fe' => $finalExamScores->avg() * 0.35,
                        
                        'total_score' => (($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + ($finalExamScores->avg() * 0.35),

                        'grades' => $grade,
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            }

            // dd($scoresByStudent);

            
            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('subject_id', $subject->subject_id)
                ->where('semester', $semester)
                ->where('teacher_id', $subjectTeacher->teacher_id)
                ->first();

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);   

            if($isMajorSubject){
                return view('components.report.detail_scoring_major_subject_primary')->with('data', $data);
            }
            else {
                return view('components.report.detail_scoring_subject_primary')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function scoringDecline($gradeId, $teacherId, $subjectId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' database reports',
                'child' => 'report class teacher',
            ]);

            Scoring_status::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->where('teacher_id', $teacherId)
                ->where('semester', $semester)
                ->delete();

            session()->flash('after_decline_scoring');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline Scoring',
                    'text' => 'Succesfully decline scoring'
                ]
            ]);


        } catch (Exception $err) {
            dd($err);
        }
    }

    // Melihat seluruh nilai siswa berdasarkan kelas & mapel Secondary
    public function detailSubjectClassStudentSec($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();

            // check apakah major subject

            $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId) {
                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                    $subQuery->where('subject_id', $subjectId);
                });
            }])
            ->where('grades.id', $gradeId)
            ->withCount([
                'exam as total_homework' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 1);
                },
                'exam as total_exercise' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 2);
                },
                'exam as total_quiz' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 3);
                },
                'exam as total_final_exam' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 4);
                },
                'exam as total_participation' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 5);
                },
            ])
            ->first();

            $semester = session('semester');

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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $subjectTeacher->teacher_id)
                ->get();

            
            $type = "subject_assessment_secondary";

            $comments = Comment::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                ->where('type', $type)
                ->get()
                ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {

                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', 1)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', 2)->pluck('score');
                    $participationScore = $scores->where('type_exam', 5)->pluck('score');
                    $quizScores         = $scores->where('type_exam', 3)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', 4)->pluck('score');

                    // dd($quizScores);
                    
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
                        'avg_fe'            => round($finalExamScores->avg()),

                        'percent_homework' => round($homeworkScores->avg() * 0.25),
                        'percent_quiz'  => round($quizScores->avg() * 0.35),
                        'percent_fe'    => round($finalExamScores->avg() * 0.4),
                        'total_score'   => (round(($homeworkScores->avg() * 0.25)) +  round(($quizScores->avg() * 0.35)) + round(($finalExamScores->avg() * 0.4))),
                        
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();

            // dd($scoresByStudent);

            
            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
            ];

            // dd($data);   

            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.detail_scoring_subject_secondary')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail_scoring_subject_secondary')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    //Academic Assessment Report
    public function acarPrimary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);

            $semester = session('semester');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.grade_id', $gradeId)
                ->where('acars.semester', $semester)
                ->get();

            $comments = Acar_comment::where('grade_id', $gradeId)
                ->where('type', 'academic_assessment_report')
                ->where('semester', $semester)
                ->get()
                ->keyBy('student_id');

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
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
                    'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();

            $status = Acar_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);
            
            return view('components.report.acar_primary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function acarSecondary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'repost class teacher',
            ]);

            $semester = session('semester');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();


            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.grade_id', $gradeId)
                ->where('acars.semester', $semester)
                ->get();

            $comments = Acar_comment::where('grade_id', $gradeId)
                ->where('type', 'academic_assessment_report')
                ->where('semester', $semester)
                ->get()
                ->keyBy('student_id');

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
                $student = $scores->first();
                $majorSubject = Major_subject::pluck('subject_id')->toArray();
                $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                $supplementarySubject = Supplementary_subject::pluck('subject_id')->toArray();

                $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                $minorSubjectsScores = $scores->whereIn('subject_id', $minorSubject)->pluck('final_score');
                $supplementarySubjectsScores = $scores->whereIn('subject_id', $supplementarySubject)->pluck('final_score');

                // dd($majorSubjectsScores);

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'subject_id' => $score->subject_id,
                            'final_score' => $score->final_score,
                            'grades' => $score->grades,
                        ];
                    })->all(),
                    'percent_majorSubjects' => $majorSubjectsScores->avg() * 0.7,
                    'percent_minorSubjects' => $minorSubjectsScores->avg() * 0.2,
                    'percent_supplementarySubjects' => $supplementarySubjectsScores->avg() * 0.1,
                    'total_score' => (($majorSubjectsScores->avg() * 0.7) + ($minorSubjectsScores->avg() * 0.2) + $supplementarySubjectsScores->avg() * 0.1),
                    'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();

            $status = Acar_status::where('grade_id', $grade->grade_id)
            ->where('semester', $semester)
            ->where('class_teacher_id', $classTeacher->teacher_id)
            ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);
            
            return view('components.report.acar_secondary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function acarDecline($gradeId, $teacherId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' database reports',
                'child' => 'report class teacher',
            ]);

            Acar_status::where('grade_id', $gradeId)
                ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->delete();

            session()->flash('after_decline_acar');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline ACAR Primary',
                    'text' => 'Succesfully decline ACAR primary'
                ]
            ]);

        } catch (Exception $err) {
            dd($err);
        }
    }
    //End Academic Assessment Report

    // Summary of Academic Assesment

    public function sooaPrimary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);

            $semester = session('semester');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();
    
            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();
    
            $results = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $gradeId)
                ->where('sooa_primaries.semester', $semester)
                ->get();
    
    
            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
    
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'academic' => $score->academic,
                            'grades_academic' => $score->grades_academic,
                            'choice' => $score->choice,
                            'grades_choice' => $score->grades_choice,
                            'language_and_art' => $score->language_and_art,
                            'grades_language_and_art' => $score->grades_language_and_art,
                            'self_development' => $score->self_development,
                            'grades_self_development' => $score->grades_self_development,
                            'eca_aver' => $score->eca_aver,
                            'grades_eca_aver' => $score->grades_eca_aver,
                            'behavior' => $score->behavior,
                            'grades_behavior' => $score->grades_behavior,
                            'attendance' => $score->attendance,
                            'grades_attendance' => $score->grades_attendance,
                            'participation' => $score->participation,
                            'grades_participation' => $score->grades_participation,
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                        ];
                    })->all(),
                ];
            })->values()->all();
    
            $status = Sooa_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();
    
            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];
    
            // dd($data);
            
            return view('components.report.sooa_primary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }
    
    public function sooaSecondary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $semester = session('semester');

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $gradeId)
                ->where('sooa_secondaries.semester', $semester)
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
                $majorSubject = Major_subject::pluck('subject_id')->toArray();
                $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                $minorSubjectsScores = $scores->whereIn('subject_id', [32, 7, 20, 4])->pluck('final_score');
                $supplementarySubjectsScores = $scores->whereIn('subject_id', [18, 6, 33, 16])->pluck('final_score');


                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'academic' => $score->academic,
                            'grades_academic' => $score->grades_academic,
                            'eca_1' => $score->eca_1,
                            'grades_eca_1' => $score->grades_eca_1,
                            'eca_2' => $score->eca_2,
                            'grades_eca_2' => $score->grades_eca_2,
                            'self_development' => $score->self_development,
                            'grades_self_development' => $score->grades_self_development,
                            'eca_aver' => $score->eca_aver,
                            'grades_eca_aver' => $score->grades_eca_aver,
                            'behavior' => $score->behavior,
                            'grades_behavior' => $score->grades_behavior,
                            'attendance' => $score->attendance,
                            'grades_attendance' => $score->grades_attendance,
                            'participation' => $score->participation,
                            'grades_participation' => $score->grades_participation,
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Sooa_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status
            ];

            // dd($data);
            
            return view('components.report.sooa_secondary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function sooaPrimaryDecline($gradeId, $teacherId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' database reports',
                'child' => 'report class teacher',
            ]);

            Sooa_status::where('grade_id', $gradeId)
                ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->delete();

            session()->flash('after_decline_sooa');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline SOOA',
                    'text' => 'Succesfully decline SOOA'
                ]
            ]);


        } catch (Exception $err) {
            dd($err);
        }
    }

    // End Summary of Academic Assesment

    public function tcopPrimary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $promoteGrade = Grade::where('id', $gradeId + 1)->first();

                
            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();


            $results = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $gradeId)
                ->get();

            $semester = session('semester');

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                // dd($majorSubjectsScores);
                $scoresBySemester = $scores->groupBy('semester')->map(function ($semesterScores) {
                    
                    return $semesterScores->map(function ($score) {
                        return [
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                            'semester' => $score->semester,
                        ];
                    })->all();
                });

                $finalScores = $scores->pluck('final_score');
                $averageFinalScore = $finalScores->count() > 0 ? round($finalScores->sum() / $finalScores->count(), 1) : 0;
                $marks = $this->determineGrade($averageFinalScore);

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scoresBySemester,
                    'average_final_score' => $averageFinalScore,
                    'marks' => $marks,
                ];
                
            })->values()->all();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'promote' => $promoteGrade,
            ];

            // dd($data);
            
            return view('components.report.tcop')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function tcopSecondary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $promoteGrade = Grade::where('id', $gradeId + 1)->first();

                
            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();


            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $gradeId)
                ->get();

            $semester = session('semester');

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                // dd($majorSubjectsScores);
                $scoresBySemester = $scores->groupBy('semester')->map(function ($semesterScores) {
                    
                    return $semesterScores->map(function ($score) {
                        return [
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                            'semester' => $score->semester,
                        ];
                    })->all();
                });

                $finalScores = $scores->pluck('final_score');
                $averageFinalScore = $finalScores->count() > 0 ? round($finalScores->sum() / $finalScores->count(), 1) : 0;
                $marks = $this->determineGrade($averageFinalScore);

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scoresBySemester,
                    'average_final_score' => $averageFinalScore,
                    'marks' => $marks,
                ];
                
            })->values()->all();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'promote' => $promoteGrade,
            ];

            // dd($data);
            
            return view('components.report.tcop')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detail(){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'scores',
                'child' => 'scores',
            ]);


            $getIdParent = Relationship::where('user_id', '=', session('id_user'))->value('id');

            $id = Student_relationship::where('relationship_id', $getIdParent)->value('student_id');
  
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
                ->where('scores.student_id', $id)
                ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                 'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                 'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                 'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                 'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                 'students.id as student_id', 'students.name as student_name',
                 'scores.score as score')
                ->get();

                return view('components.teacher.detail-report')->with('data', $data);
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    // TEACHER
    public function detailSubjectClassStudentTeacher($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);

            $userId = session('id_user');
            $teacherId = Teacher::where('user_id', $userId)->value('id');

            // dd($teacherId);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();

            // check apakah major subject
            $majorSubject = Major_subject::select('subject_id')->get();
            $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

            $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId) {
                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                    $subQuery->where('subject_id', $subjectId);
                });
            }])
            ->where('grades.id', $gradeId)
            ->withCount([
                'exam as total_homework' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 1);
                },
                'exam as total_exercise' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 2);
                },
                'exam as total_quiz' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 3);
                },
                'exam as total_final_exam' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 4);
                },
                'exam as total_participation' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 5);
                },
            ])
            ->first();

            $semester = session('semester');

            // dd($subject->subject_name);

            if (strtolower($subject->subject_name) == "religion islamic") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion catholic") {
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
                    ->where('students.religion', '=', 'catholic cristianity')
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.teacher_id', $teacherId)
                    ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion christian") {
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
                ->where('students.religion', '=', 'protestant cristianity')
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion buddhism") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion hinduism") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion confucianism") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();

            }

            if ($isMajorSubject) {
            
                $type = "major_subject_assessment";

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {

                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', 1)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', 2)->pluck('score');
                    $participationScore = $scores->where('type_exam', 5)->pluck('score');
                    $quizScores         = $scores->where('type_exam', 3)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', 4)->pluck('score');
                    
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
                        'total_score'  => (round(($homeworkScores->avg() * 0.1)) + round(($exerciseScores->avg() * 0.15)) + round(($participationScore->avg() * 0.05))) + round(($quizScores->avg() * 0.3)) + round(($finalExamScores->avg() * 0.4)),
                        
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            } else {

                $type = "minor_subject_assessment";

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
                    
                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', 1)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', 2)->pluck('score');
                    $participationScore = $scores->where('type_exam', 5)->pluck('score');
                    $quizScores         = $scores->where('type_exam', 3)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', 4)->pluck('score');

                    $homeworkAvg       = $homeworkScores->avg() ?: 0;
                    $exerciseAvg       = $exerciseScores->avg() ?: 0;
                    $participationAvg  = $participationScore->avg() ?: 0;
                    $quizAvg          = $quizScores->avg() ?: 0;
                    $finalExamAvg     = $finalExamScores->avg() ?: 0;

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
                        'avg_homework' => $homeworkScores->avg(),
                        'avg_exercise' => $exerciseScores->avg(),
                        'avg_participation' => $participationScore->avg(),
                        'avg_fe' => $finalExamScores->avg(),
                        
                        'percent_homework' => $homeworkScores->avg() * 0.2,
                        'percent_exercise' => $exerciseScores->avg() * 0.35,
                        'percent_participation' => $participationScore->avg() * 0.1,
                        'percent_fe' => $finalExamScores->avg() * 0.35,
                        
                        'total_score' => (($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + ($finalExamScores->avg() * 0.35),

                        'grades' => $grade,
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            }

            // dd($scoresByStudent);

            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('semester', $semester)
                ->where('teacher_id', $subjectTeacher->teacher_id)
                ->first();

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data); 

            if($isMajorSubject){
                return view('components.teacher.detail_scoring_major_subject_primary')->with('data', $data);
            }
            else {
                return view('components.teacher.detail_scoring_subject_primary')->with('data', $data);
            }
           
    
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function detailSubjectClassStudentSecTeacher($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'database reports',
            ]);

            $userId = session('id_user');
            $teacherId = Teacher::where('user_id', $userId)->value('id');

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();

            // check apakah major subject

            $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId) {
                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                    $subQuery->where('subject_id', $subjectId);
                });
            }])
            ->where('grades.id', $gradeId)
            ->withCount([
                'exam as total_homework' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 1);
                },
                'exam as total_exercise' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 2);
                },
                'exam as total_quiz' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 3);
                },
                'exam as total_final_exam' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 4);
                },
                'exam as total_participation' => function ($query) use ($subjectId) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', 5);
                },
            ])
            ->first();

            $semester = session('semester');

            if (strtolower($subject->subject_name) == "religion islamic") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion catholic") {
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
                    ->where('students.religion', '=', 'catholic cristianity')
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.teacher_id', $teacherId)
                    ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion christian") {
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
                ->where('students.religion', '=', 'protestant cristianity')
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion buddhism") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion hinduism") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "religion confucianism") {
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }

            
            $type = "subject_assessment_secondary";

            $comments = Comment::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                ->where('type', $type)
                ->get()
                ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {

                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', 1)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', 2)->pluck('score');
                    $participationScore = $scores->where('type_exam', 5)->pluck('score');
                    $quizScores         = $scores->where('type_exam', 3)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', 4)->pluck('score');

                    // dd($quizScores);
                    
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
                        'avg_fe'            => round($finalExamScores->avg()),

                        'percent_homework' => round($homeworkScores->avg() * 0.25),
                        'percent_quiz'  => round($quizScores->avg() * 0.35),
                        'percent_fe'    => round($finalExamScores->avg() * 0.4),
                        'total_score'   => (round(($homeworkScores->avg() * 0.25)) +  round(($quizScores->avg() * 0.35)) + round(($finalExamScores->avg() * 0.4))),
                        
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();

            // dd($scoresByStudent);
            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('semester', $semester)
                ->where('teacher_id', $subjectTeacher->teacher_id)
                ->first();

            
            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
            ];

            dd($data);   

            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.detail_scoring_subject_secondary')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail_scoring_subject_secondary')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function teacherReport($id){
        try {
            //code...
            session()->flash('page',  $page = (object)[
               'page' => 'reports',
               'child' => 'database reports',
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
   
            return view('components.teacher.data-report-teacher')->with('data', $data);
   
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function classTeacher($id){
        try {
            //code...
            session()->flash('page',  $page = (object)[
               'page' => 'reports',
               'child' => 'report class teacher',
            ]);
   
            $getIdTeacher = Teacher::where('user_id', $id)->value('id');
   
            $classTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.*', )
               ->get();

            $data = [
               'classTeacher' => $classTeacher,
            ];
   
            // dd($data);
   
            return view('components.teacher.data-report-teacher')->with('data', $data);
   
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function subjectTeacher($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report subject teacher',
            ]);

            $getIdTeacher = Teacher::where('user_id', $id)->value('id');

            $subjectTeacher = Teacher_subject::where('teacher_id', $getIdTeacher)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->get();

            $primaryGrades = $subjectTeacher->filter(function($item) {
                return stripos($item->name, 'primary') !== false;
            });
            
            // Filter secondary grades
            $secondaryGrades = $subjectTeacher->filter(function($item) {
                return stripos($item->name, 'secondary') !== false;
            });
            
            $data = $subjectTeacher;

            return view('components.teacher.data-report-subject-teacher', compact('primaryGrades', 'secondaryGrades'))->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function cardSemester1($id){
        // dd($id);
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));

            // dd($semester);

            if ($semester !== 1) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 2.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->get();

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'remarks' => $score->remarks,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester1')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester2($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);

            $gradeId = $id;
            $semester = intval(session('semester'));

            if ($semester !== 2) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 1.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->get();

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'promotion_status' => $score->promotion_status,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester2')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester1Sec($id){
        // dd($id);
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));

            // dd($semester);

            if ($semester !== 1) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 2.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->get();

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'remarks' => $score->remarks,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester1')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester2Sec($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);

            $gradeId = $id;
            $semester = intval(session('semester'));

            if ($semester !== 2) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 1.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->get();

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'promotion_status' => $score->promotion_status,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester2')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function reportCardDecline($gradeId, $teacherId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' database reports',
                'child' => 'report class teacher',
            ]);

            Report_card_status::where('grade_id', $gradeId)
                ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->delete();

            session()->flash('after_decline_report_card');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline Report Card',
                    'text' => 'Succesfully decline Report Card'
                ]
            ]);


        } catch (Exception $err) {
            dd($err);
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

    public function downloadPDFSemester1($id)
    {
        try {
            $semester = session('semester');
            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();

            $gradeId = Student::where('id', $id)->value('grade_id');

            $serial = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->orderBy('students.name', 'asc')
                ->select('students.id', 'students.name', 'grades.id as grade_id')
                ->get();

            // Tambahkan nomor urut ke setiap siswa
            $serial->each(function($serial, $index) {
                $serial->serial_number = $index + 1; // Nomor urut mulai dari 1
            });

            foreach ($serial as $student) {
                if ($student->id == $id) {
                    $getSerial = $student->serial_number;
                    break;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            $relation = Student_relationship::where('Student_relationships.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relationships.relationship_id')
                ->select('relationships.name as relationship_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalLate = $attendances->where('late', 1)->count();
                $timesLate = $attendances->whereNotNull('latest')->sum('latest');
                
                return [
                    'days_absent' => $totalAlpha,
                    'total_late' => $totalLate,
                    'times_late' => $timesLate,
                ];
            })->values()->all();

            $comments = comment::where('student_id', $id)
                ->get()
                ->keyBy('student_id');

            $resultsScore = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->leftJoin('subjects', function ($join) {
                    $join->on('subjects.id', '=', 'acars.subject_id')
                    ->select('subjects.name');
                })
                ->where('acars.student_id', $id)
                ->get();
                
                if(strtolower($student->grade_name) === "primary")
                {
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'General Knowledge',
                        'Art and Craft',
                        'PE',
                        'IT',
                        'English',
                        'Chinese'
                    ];
                } elseif (strtolower($student->grade_name) === "secondary") {
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'IPS',
                        'Art and Design',
                        'PE',
                        'IT',
                        'English',
                        'Chinese'
                    ];
                }

                $scoresByStudent = $resultsScore->groupBy('student_id')->map(function ($scores) use($comments, $order) {
                    $student = $scores->first();
                
                    // Sort scores based on the custom order
                    $sortedScores = $scores->sortBy(function($score) use ($order) {
                        return array_search($score->name_subject, $order);
                    });

                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->map(function ($score) {
                            return [
                                'subject_name' => $score->name_subject,
                                'subject_id' => $score->subject_id,
                                'final_score' => $score->final_score,
                                'grades' => $score->grades,
                                'comment' => $score->comment,
                            ];
                        })->all(),
                    ];
                })->values()->all();
                

            
            if (strtolower($student->grade_name) === "primary") {
                $resultsSooa = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                    ->where('sooa_primaries.student_id', $id)
                    ->get();

                $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
        
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'ranking' => $student->ranking,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'academic' => $score->academic,
                                'grades_academic' => $score->grades_academic,
                                'choice' => $score->choice,
                                'grades_choice' => $score->grades_choice,
                                'language_and_art' => $score->language_and_art,
                                'grades_language_and_art' => $score->grades_language_and_art,
                                'self_development' => $score->self_development,
                                'grades_self_development' => $score->grades_self_development,
                                'eca_aver' => $score->eca_aver,
                                'grades_eca_aver' => $score->grades_eca_aver,
                                'behavior' => $score->behavior,
                                'grades_behavior' => $score->grades_behavior,
                                'attendance' => $score->attendance,
                                'grades_attendance' => $score->grades_attendance,
                                'participation' => $score->participation,
                                'grades_participation' => $score->grades_participation,
                                'final_score' => $score->final_score,
                                'grades_final_score' => $score->grades_final_score,
                            ];
                        })->all(),
                    ];
                })->values()->all();
            }
            elseif (strtolower($student->grade_name) === "secondary") {
                $resultsSooa = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                    ->where('sooa_secondaries.student_id', $id)
                    ->get();

                $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
        
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'ranking' => $student->ranking,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'academic' => $score->academic,
                                'grades_academic' => $score->grades_academic,
                                'eca_1' => $score->eca_1,
                                'grades_eca_1' => $score->eca_1,
                                'eca_2' => $score->eca_2,
                                'grades_eca_2' => $score->grades_eca_2,
                                'self_development' => $score->self_development,
                                'grades_self_development' => $score->grades_self_development,
                                'eca_aver' => $score->eca_aver,
                                'grades_eca_aver' => $score->grades_eca_aver,
                                'behavior' => $score->behavior,
                                'grades_behavior' => $score->grades_behavior,
                                'attendance' => $score->attendance,
                                'grades_attendance' => $score->grades_attendance,
                                'participation' => $score->participation,
                                'grades_participation' => $score->grades_participation,
                                'final_score' => $score->final_score,
                                'grades_final_score' => $score->grades_final_score,
                            ];
                        })->all(),
                    ];
                })->values()->all();
            }
    
    
            

            $student->date_of_registration = Carbon::parse($student->date_of_registration);

            // dd($learningSkills);

            $data = [
                'student' => $student,
                'classTeacher' => $classTeacher,
                'learningSkills' => $learningSkills,
                'subjectReports' => $scoresByStudent,
                'sooa' => $scoresByStudentSooa,
                'attendance' => $attendancesByStudent,
                'relation' => $relation,
                'serial' => $getSerial,
            ];

            // dd($data);


            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.semester1-pdf', $data)->setPaper('a4', 'portrait');
            return $pdf->download($student->student_name . '_semester' . $semester . '.pdf');


            // $dompdf = new Dompdf();
            // $html = View::make('components.report.pdf.semester1-pdf')->render();

            // $dompdf->loadHtml($html);
            

            // // (Optional) Setup the paper size and orientation
            // $dompdf->setPaper('A4', 'portrait');

            // // Render the HTML as PDF
            // $dompdf->render();

            // // Output the generated PDF to Browser
        
            // return $dompdf->stream('tes.pdf');


        } catch (Exception $err) {
            dd($err);
        }
    }
    public function downloadPDFSemester2($id)
    {
        try {
            $semester = session('semester');
            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');
            
            if ($learningSkills->promotion_status === 1 || $learningSkills->promotion_status === 2) {
                $nextGrade = Grade::where('id', $gradeId+1)
                ->select('grades.name as grade_name', 'grades.class as grade_class')
                ->first();
                $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
            } 
            elseif ($learningSkills->promotion_status === 3) {
                $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name', 'grades.class as grade_class')
                ->first();
                $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
            }

            $serial = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->orderBy('students.name', 'asc')
                ->select('students.id', 'students.name', 'grades.id as grade_id')
                ->get();

            // Tambahkan nomor urut ke setiap siswa
            $serial->each(function($serial, $index) {
                $serial->serial_number = $index + 1; // Nomor urut mulai dari 1
            });

            foreach ($serial as $student) {
                if ($student->id == $id) {
                    $getSerial = $student->serial_number;
                    break;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            $relation = Student_relationship::where('Student_relationships.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relationships.relationship_id')
                ->select('relationships.name as relationship_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalLate = $attendances->where('late', 1)->count();
                $timesLate = $attendances->whereNotNull('latest')->sum('latest');
                
                return [
                    'days_absent' => $totalAlpha,
                    'total_late' => $totalLate,
                    'times_late' => $timesLate,
                ];
            })->values()->all();

            $comments = comment::where('student_id', $id)
                ->get()
                ->keyBy('student_id');
            $resultsScore = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->leftJoin('subjects', function ($join) {
                    $join->on('subjects.id', '=', 'acars.subject_id')
                    ->select('subjects.name');
                })
                ->where('acars.student_id', $id)
                ->get();
                
                
                $order = [
                    'Religion',
                    'PPKn',
                    'Character Building',
                    'Bahasa Indonesia',
                    'Mathematics',
                    'Science',
                    'General Knowledge',
                    'Art and Craft',
                    'PE',
                    'IT',
                    'English',
                    'Chinese'
                ];
                
                $scoresByStudent = $resultsScore->groupBy('student_id')->map(function ($scores) use($comments, $order) {
                    $student = $scores->first();
                
                    // Sort scores based on the custom order
                    $sortedScores = $scores->sortBy(function($score) use ($order) {
                        return array_search($score->name_subject, $order);
                    });

                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->map(function ($score) {
                            return [
                                'subject_name' => $score->name_subject,
                                'subject_id' => $score->subject_id,
                                'final_score' => $score->final_score,
                                'grades' => $score->grades,
                                'comment' => $score->comment,
                            ];
                        })->all(),
                    ];
                })->values()->all();

            // dd($student->grade_name);
                

            if (strtolower($student->grade_name) === "primary") {
                $resultsSooa = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                    ->where('sooa_primaries.student_id', $id)
                    ->get();
            }
            elseif (strtolower($student->grade_name) === "secondary") {
                $resultsSooa = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                    ->where('sooa_secondaries.student_id', $id)
                    ->get();
            }
    
    
            $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
    
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'academic' => $score->academic,
                            'grades_academic' => $score->grades_academic,
                            'choice' => $score->choice,
                            'grades_choice' => $score->grades_choice,
                            'language_and_art' => $score->language_and_art,
                            'grades_language_and_art' => $score->grades_language_and_art,
                            'self_development' => $score->self_development,
                            'grades_self_development' => $score->grades_self_development,
                            'eca_aver' => $score->eca_aver,
                            'grades_eca_aver' => $score->grades_eca_aver,
                            'behavior' => $score->behavior,
                            'grades_behavior' => $score->grades_behavior,
                            'attendance' => $score->attendance,
                            'grades_attendance' => $score->grades_attendance,
                            'participation' => $score->participation,
                            'grades_participation' => $score->grades_participation,
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $student->date_of_registration = Carbon::parse($student->date_of_registration);

            // dd($learningSkills);

            $data = [
                'student' => $student,
                'classTeacher' => $classTeacher,
                'learningSkills' => $learningSkills,
                'subjectReports' => $scoresByStudent,
                'sooa' => $scoresByStudentSooa,
                'attendance' => $attendancesByStudent,
                'relation' => $relation,
                'serial' => $getSerial,
                'promotionGrade' => $grade,
            ];

            // dd($data);

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.semester2-pdf', $data)->setPaper('a4', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');



            // $dompdf = new Dompdf();
            // $html = View::make('components.report.pdf.semester1-pdf')->render();

            // $dompdf->loadHtml($html);
            

            // // (Optional) Setup the paper size and orientation
            // $dompdf->setPaper('A4', 'portrait');

            // // Render the HTML as PDF
            // $dompdf->render();

            // // Output the generated PDF to Browser
        
            // return $dompdf->stream('tes.pdf');


        } catch (Exception $err) {
            dd($err);
        }
    }
}







