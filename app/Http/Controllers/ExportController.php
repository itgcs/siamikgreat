<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Master_academic;
use App\Models\Type_exam;
use App\Models\Major_subject;
use App\Models\Minor_subject;
use App\Models\Supplementary_subject;
use App\Models\Acar;
use App\Models\Sooa_primary;
use App\Models\Sooa_secondary;
use App\Models\Student_eca;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExportController extends Controller
{
    private function assessmentStudent($grade)
    {
        $semester  = Master_academic::first()->value('now_semester');
        $homework  = Type_exam::where('name', 'homework')->value('id');
        $exercise  = Type_exam::where('name', 'exercise')->value('id');
        $quiz      = Type_exam::where('name', 'quiz')->value('id');
        $project   = Type_exam::where('name', 'project')->value('id');
        $practical = Type_exam::where('name', 'practical')->value('id');

        $student = Student::where('grade_id', $grade)->pluck('id');
        $grade_name = Grade::where('id', $grade)->value('name');
            

        $homework  = Type_exam::where('name', 'homework')->value('id');
        $exercise  = Type_exam::where('name', 'exercise')->value('id');
        $quiz      = Type_exam::where('name', 'quiz')->value('id');
        $project   = Type_exam::where('name', 'project')->value('id');
        $practical = Type_exam::where('name', 'practical')->value('id');

        if(strtolower($grade_name) === "primary"){
            $checkReligion = Student::where('id', $id)->value('religion');

            if ($checkReligion == "Islam") {
                $religion = "Religion Islamic";
            }
            elseif ($checkReligion == "Catholic Christianity") {
                $religion = "Religion Catholic";
            }
            elseif ($checkReligion == "Protestant Christianity") {
                $religion = "Religion Christian";
            }
            elseif ($checkReligion == "Buddhism") {
                $religion = "Religion Buddhism";
            }
            elseif ($checkReligion == "Hinduism") {
                $religion = "Religion Hinduism";
            }
            elseif ($checkReligion == "Confucianism") {
                $religion = "Religion Confucianism";
            }

            $order = [
                'English',
                'Chinese',
                'Mathematics',
                'Science',
                $religion,
                'Bahasa Indonesia',
                'Character Building',
                'PE',
                'IT',
                'General Knowledge',
                'PPKn',
                'Art and Craft',
                'Health Education'
            ];

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
                ->leftJoin('subjects', function($join){
                    $join->on('subjects.id', '=', 'subject_exams.subject_id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'exams.id as exam_id',
                    'exams.type_exam as type_exam',
                    'scores.score as score',
                    'subjects.name_subject as subject_name',
                )
                ->where('grades.id', $grade)
                ->where('exams.semester', $semester)
                ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                $student = $scores->first();
                $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
            
                    $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                    $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                    $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                    $projectScores = $subjectScores->where('type_exam', $project)->pluck('score');
                    $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
            
                    return [
                        'subject_name' => $subjectScores->first()->subject_name,
                        'scores' => [
                            'homework' => $homeworkScores->all(),
                            'exercise' => $exerciseScores->all(),
                            'quiz' => $quizScores->all(),
                            'project' => $projectScores->all(),
                            'practical' => $practicalScores->all()
                        ],
                    ];
                });
            
                // Urutkan subjek berdasarkan urutan dalam $order
                $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                    return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                });
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'subjects' => $orderedSubjects->values()->all(),
                ];
            })->values()->all();
            return $scoresByStudent;
        } 
        elseif (strtolower($grade_name) === "secondary") {
            $chineseLower  = Chinese_lower::where('student_id', $id)->exists();
            $chineseHigher = Chinese_higher::where('student_id', $id)->exists();

            $checkReligion = Student::where('id', $id)->value('religion');

            if ($checkReligion == "Islam") {
                $religion = "Religion Islamic";
            }
            elseif ($checkReligion == "Catholic Christianity") {
                $religion = "Religion Catholic";
            }
            elseif ($checkReligion == "Protestant Christianity") {
                $religion = "Religion Christian";
            }
            elseif ($checkReligion == "Buddhism") {
                $religion = "Religion Buddhism";
            }
            elseif ($checkReligion == "Hinduism") {
                $religion = "Religion Hinduism";
            }
            elseif ($checkReligion == "Confucianism") {
                $religion = "Religion Confucianism";
            }
            // dd($chineseHigher);
        
            if ($chineseLower) {
                $chinese = "Chinese Lower";
            }
            elseif ($chineseHigher) {
                $chinese = "Chinese Higher";
            }
            
            $order = [
                'English',
                $chinese,
                'Mathematics',
                'Science',
                $religion,
                'Bahasa Indonesia',
                'Character Building',
                'PE',
                'IT',
                'Art and Design',
                'PPKn',
                'IPS',
            ];

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
                ->leftJoin('subjects', function($join){
                    $join->on('subjects.id', '=', 'subject_exams.subject_id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'exams.id as exam_id',
                    'exams.type_exam as type_exam',
                    'scores.score as score',
                    'subjects.name_subject as subject_name',
                )
                ->where('grades.id', $grade)
                ->where('exams.semester', $semester)
                ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                $student = $scores->first();
                $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
            
                    $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                    $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                    $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                    $projectScores = $subjectScores->where('type_exam', $project)->pluck('score');
                    $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
            
                    return [
                        'subject_name' => $subjectScores->first()->subject_name,
                        'scores' => [
                            'homework' => $homeworkScores->all(),
                            'exercise' => $exerciseScores->all(),
                            'quiz' => $quizScores->all(),
                            'project' => $projectScores->all(),
                            'practical' => $practicalScores->all()
                        ],
                    ];
                });
            
                // Urutkan subjek berdasarkan urutan dalam $order
                $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                    return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                });
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'subjects' => $orderedSubjects->values()->all(),
                ];
            })->values()->all();
            return $scoresByStudent;
        }
        elseif (strtolower($grade_name) === "kindergarten") {
            
            $order = [
                'English',
                'Chinese',
                'Mathematics',
                'Science',
                'Character Building',
                'Art and Craft',
                'IT',
                'Phonic',
            ];

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
                ->leftJoin('subjects', function($join){
                    $join->on('subjects.id', '=', 'subject_exams.subject_id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'exams.id as exam_id',
                    'exams.type_exam as type_exam',
                    'scores.score as score',
                    'subjects.name_subject as subject_name',
                )
                ->where('grades.id', $grade)
                ->where('exams.semester', $semester)
                ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                $student = $scores->first();
                $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
            
                    $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                    $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                    $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                    $projectScores = $subjectScores->where('type_exam', $project)->pluck('score');
                    $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
            
                    return [
                        'subject_name' => $subjectScores->first()->subject_name,
                        'scores' => [
                            'homework' => $homeworkScores->all(),
                            'exercise' => $exerciseScores->all(),
                            'quiz' => $quizScores->all(),
                            'project' => $projectScores->all(),
                            'practical' => $practicalScores->all()
                        ],
                    ];
                });
            
                // Urutkan subjek berdasarkan urutan dalam $order
                $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                    return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                });
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'subjects' => $orderedSubjects->values()->all(),
                ];
            })->values()->all();
            return $scoresByStudent;
        }
    }

    private function acarStudent($grade)
    {
        $semester  = Master_academic::first()->value('now_semester');
        $grade_name = Grade::where('id', $grade)->value('name');

        if(strtolower($grade_name) === "primary"){
            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.grade_id', $grade)
                ->where('acars.semester', $semester)
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
                $majorSubject = Major_subject::pluck('subject_id')->toArray();
                $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                $supplementarySubject = Supplementary_subject::where('subject_id', ['6','19','18', '17', '16', '15'])->pluck('subject_id')->toArray();

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
                    // 'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();
            return $scoresByStudent;
        } 
        elseif (strtolower($grade_name) === "secondary") {
            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.grade_id', $grade)
                ->where('acars.semester', $semester)
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
                $majorSubject = Major_subject::pluck('subject_id')->toArray();
                $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                $supplementarySubject = Supplementary_subject::where('subject_id', ['6','18','33', '16'])->pluck('subject_id')->toArray();

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
                    // 'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();
            return $scoresByStudent;
        }
    }

    private function sooaStudent($grade)
    {
        $semester  = Master_academic::first()->value('now_semester');
        $grade_name = Grade::where('id', $grade)->value('name');

        if(strtolower($grade_name) === "primary"){
            $results = Sooa_primary::leftJoin('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $grade)
                ->where('sooa_primaries.semester', $semester)
                ->get();
    
            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                if (Student_eca::where('student_id', $student->student_id)->exists()) {
                    $haveEca = 1;
                    $nameEca = Student_eca::where('student_ecas.student_id', $student->student_id)
                        ->leftJoin('ecas', 'ecas.id', 'student_ecas.eca_id')
                        ->get()->value('name');
                } else{
                    $haveEca = 0;
                    $nameEca = "Not Choice";
                }
    
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    'haveEca' => $haveEca,
                    'nameEca' => $nameEca,
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
            return $scoresByStudent;
        } 
        elseif (strtolower($grade_name) === "secondary") {
            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $grade)
                ->where('sooa_secondaries.semester', $semester)
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                if (Student_eca::where('student_id', $student->student_id)->exists()) {
                    $haveEca = 1;
                    $ecaData = Student_eca::where('student_ecas.student_id', $student->student_id)
                        ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                        ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

                    $groupedEcaData = [];
                    $counter = 1;

                    // dd(count($ecaData));

                    if (count($ecaData) == 1) {
                        $groupedEcaData['student_id'] = $ecaData[0]->student_id;
                        $groupedEcaData['eca_1'] = $ecaData[0]->eca_name;
                        $groupedEcaData['eca_2'] = "Not Choice";
                    }
                    elseif (count($ecaData) == 2) {
                        for ($i=0; $i < 2; $i++) { 
                            $groupedEcaData['student_id'] = $ecaData[$i]->student_id;
                            $groupedEcaData['eca_' . $i+1] = $ecaData[$i]->eca_name;
                        }
                    }

                } else{
                    $haveEca = 0;
                    $groupedEcaData = "Not Choice";
                }

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    'haveEca' => $haveEca,
                    'nameEca' => $groupedEcaData,
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
            return $scoresByStudent;
        }
    }

    private function tcopStudent($grade)
    {
        $semester  = Master_academic::first()->value('now_semester');
        $grade_name = Grade::where('id', $grade)->value('name');

        if(strtolower($grade_name) === "primary"){
            $results = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $grade)
                ->get();

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
            return $scoresByStudent;
        } 
        elseif (strtolower($grade_name) === "secondary") {
            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $grade)
                ->get();

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
            return $scoresByStudent;
        }
    }

    private function reportStudent($grade)
    {
        $semester  = Master_academic::first()->value('now_semester');
        $grade_name = Grade::where('id', $grade)->value('name');

        if(strtolower($grade_name) === "primary"){
            $results = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $grade)
                ->get();

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
            return $scoresByStudent;
        } 
        elseif (strtolower($grade_name) === "secondary") {
            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $grade)
                ->get();

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
            return $scoresByStudent;
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

    public function grades()
    {
        $academic = Master_academic::first();

        $grades = Grade::leftJoin('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
            ->leftJoin('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->select(
                'teachers.name as class_teacher',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name")
            )
            ->get();

        $subjectTeacher = Teacher_subject::leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
            ->leftJoin('teachers as class_teacher', 'class_teacher.id', '=', 'teacher_grades.teacher_id')
            ->orderBy('grades.id', 'asc')
            ->orderBy('subjects.id', 'asc')
            ->select(
                'subjects.name_subject as subject_name',
                'teachers.name as teacher_name',
                'class_teacher.name as class_teacher',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name_class")
            )
            ->get();

        $gradeSubjectTeacher = $subjectTeacher->groupBy('grade_name_class')->map(function ($grades) {
            return [
                'class_teacher' => $grades->pluck('class_teacher')->unique()->first(),
                'subjects' => $grades->pluck('subject_name')->all(),
                'teachers' => $grades->pluck('teacher_name')->all(),
            ];
        })->toArray(); // Menggunakan toArray untuk memastikan hasil berupa array

        // dd($gradeSubjectTeacher);

        $data = [
            'grades' => $grades,
            'gradeSubjectTeacher' => $gradeSubjectTeacher,
            'semester' => $academic->now_semester,
            'academicYear' => $academic->academic_year,
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->loadView('components.export.grades-pdf', $data)->setPaper('a4', 'protrait');
        return $pdf->stream('grades.pdf');
    }

    public function assessment()
    {
        $academic = Master_academic::first();
        $grades = Grade::whereNotIn('name', ['toddler', 'nursery'])
            ->select(
                'grades.id as id',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name_class")
            )
            ->pluck('grade_name_class', 'id')
            ->toArray();
        
        $gradeIds = array_keys($grades);
        // dd($grades);

        $scores = [];

        foreach ($grades as $grade => $value) {
           $scores[$value] = $this->assessmentStudent($grade);
        }

        $data = [
            'scores'       => $scores,
            'semester'     => $academic->now_semester,
            'academicYear' => $academic->academic_year,
        ];

        dd($data);

        $pdf = app('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->loadView('components.export.assessment-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('grades.pdf');

    }

    public function acar()
    {
        $academic = Master_academic::first();
        $grades = Grade::whereNotIn('name', ['toddler', 'nursery', 'kindergarten', 'igcse'])
            // ->leftJoin('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->select(
                'grades.id as id',
                // 'teachers.name as class_teacher',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name_class")
            )
            ->pluck('grade_name_class', 'id')
            ->toArray();
        
        $gradeIds = array_keys($grades);
        // dd($grades);

        $scores = [];

        foreach ($grades as $grade => $value) {
           $scores[$value] = $this->acarStudent($grade);
        }

        $data = [
            'scores'       => $scores,
            'semester'     => $academic->now_semester,
            'academicYear' => $academic->academic_year,
        ];

        dd($data);

        $pdf = app('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->loadView('components.export.acar-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('grades.pdf');

    }

    public function sooa()
    {
        $academic = Master_academic::first();
        $grades = Grade::whereNotIn('name', ['toddler', 'nursery', 'kindergarten', 'igcse'])
            ->select(
                'grades.id as id',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name_class")
            )
            ->pluck('grade_name_class', 'id')
            ->toArray();
        
        $gradeIds = array_keys($grades);
        // dd($grades);

        $scores = [];

        foreach ($grades as $grade => $value) {
           $scores[$value] = $this->sooaStudent($grade);
        }

        $data = [
            'scores'       => $scores,
            'semester'     => $academic->now_semester,
            'academicYear' => $academic->academic_year,
        ];

        dd($data);

        $pdf = app('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->loadView('components.export.assessment-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('grades.pdf');

    }

    public function tcop()
    {
        $academic = Master_academic::first();
        $grades = Grade::whereNotIn('name', ['toddler', 'nursery', 'kindergarten', 'igcse'])
            ->select(
                'grades.id as id',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name_class")
            )
            ->pluck('grade_name_class', 'id')
            ->toArray();
        
        $gradeIds = array_keys($grades);
        // dd($grades);

        $scores = [];

        foreach ($grades as $grade => $value) {
           $scores[$value] = $this->tcopStudent($grade);
        }

        $data = [
            'scores'       => $scores,
            'semester'     => $academic->now_semester,
            'academicYear' => $academic->academic_year,
        ];

        dd($data);

        $pdf = app('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->loadView('components.export.assessment-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('grades.pdf');

    }

    public function report()
    {
        $academic = Master_academic::first();
        $grades = Grade::whereNotIn('name', ['igcse'])
            ->select(
                'grades.id as id',
                DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name_class")
            )
            ->pluck('grade_name_class', 'id')
            ->toArray();
        
        $gradeIds = array_keys($grades);
        // dd($grades);

        $scores = [];

        foreach ($grades as $grade => $value) {
           $scores[$value] = $this->reportStudent($grade);
        }

        $data = [
            'scores'       => $scores,
            'semester'     => $academic->now_semester,
            'academicYear' => $academic->academic_year,
        ];

        dd($data);

        $pdf = app('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->loadView('components.export.assessment-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('grades.pdf');
    }

}
