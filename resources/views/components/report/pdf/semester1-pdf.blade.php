<?php
// Set the maximum execution time to 300 seconds
set_time_limit(300);

// Your script logic here
$pathlogo = public_path('images/logo-school.png');
$typelogo = pathinfo($pathlogo, PATHINFO_EXTENSION);
$datalogo = file_get_contents($pathlogo);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($datalogo);

$pathcambridge = public_path('images/cambridge.png');
$typecambridge = pathinfo($pathcambridge, PATHINFO_EXTENSION);
$datacambridge = file_get_contents($pathcambridge);
$cambridge = 'data:image/' . $type . ';base64,' . base64_encode($datacambridge);

if (strtolower($student->grade_name) === "primary") {
    $eca = round((($sooa[0]['scores'][0]['language_and_art'])+($sooa[0]['scores'][0]['choice']))/2);
    if ($eca >= 95 && $eca <= 100) {
        $eca_grade = 'A+';
    } elseif ($eca >= 85 && $eca <= 94) {
        $eca_grade = 'A';
    } elseif ($eca >= 75 && $eca <= 84) {
        $eca_grade = 'B';
    } elseif ($eca >= 65 && $eca <= 74) {
        $eca_grade = 'C';
    } elseif ($eca >= 45 && $eca <= 64) {
        $eca_grade = 'D';
    } else {
        $eca_grade = 'R';
    }
    
    $marks = round(($sooa[0]['scores'][0]['academic'] + $eca + $sooa[0]['scores'][0]['behavior'] + $sooa[0]['scores'][0]['attendance'] + $sooa[0]['scores'][0]['participation'])/5);
    if ($marks >= 95 && $marks <= 100) {
        $marks_grade = 'A+';
    } elseif ($marks >= 85 && $marks <= 94) {
        $marks_grade = 'A';
    } elseif ($marks >= 75 && $marks <= 84) {
        $marks_grade = 'B';
    } elseif ($marks >= 65 && $marks <= 74) {
        $marks_grade = 'C';
    } elseif ($marks >= 45 && $marks <= 64) {
        $marks_grade = 'D';
    } else {
        $marks_grade = 'R';
    }
}
elseif (strtolower($student->grade_name) === "secondary") {
    $eca = round((($sooa[0]['scores'][0]['eca_1'])+($sooa[0]['scores'][0]['eca_2']))/2);
    if ($eca >= 95 && $eca <= 100) {
        $eca_grade = 'A+';
    } elseif ($eca >= 85 && $eca <= 94) {
        $eca_grade = 'A';
    } elseif ($eca >= 75 && $eca <= 84) {
        $eca_grade = 'B';
    } elseif ($eca >= 65 && $eca <= 74) {
        $eca_grade = 'C';
    } elseif ($eca >= 45 && $eca <= 64) {
        $eca_grade = 'D';
    } else {
        $eca_grade = 'R';
    }
    
    $marks = round(($sooa[0]['scores'][0]['academic'] + $eca + $sooa[0]['scores'][0]['behavior'] + $sooa[0]['scores'][0]['attendance'] + $sooa[0]['scores'][0]['participation'])/5);
    if ($marks >= 95 && $marks <= 100) {
        $marks_grade = 'A+';
    } elseif ($marks >= 85 && $marks <= 94) {
        $marks_grade = 'A';
    } elseif ($marks >= 75 && $marks <= 84) {
        $marks_grade = 'B';
    } elseif ($marks >= 65 && $marks <= 74) {
        $marks_grade = 'C';
    } elseif ($marks >= 45 && $marks <= 64) {
        $marks_grade = 'D';
    } else {
        $marks_grade = 'R';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
        }
        .header h1, .header h2 ,.header h3, .header h4, .header h5 {
            margin: 0;
        }

        .footer {
            margin: 0;
        }

        .mid {
            display: flex;
            justify-content: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table td {
            font-size:12px;
        }
        .signature {
            text-align: center;
            margin-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        .watermark {
            position: absolute;
            top: 30%;
            z-index: -1;
        }
    </style>
</head>
<body>
<div class="container"> 
    <!-- PAGE 1 -->
        <div class="header">
            <div style="padding-left:50px;padding-right:50px;margin-bottom:20px;">
                <img src="<?= $logo ?>" style="width:100%;height:10%;" alt="Sample image">
            </div>
            <h3>Report Card</h3>
            <h3>Semester I School Year 2023 – 2024</h3>
        </div>

        <div>
            <table class="table">
                <!-- STUDENT STATUS -->
                <tr>
                    <th colspan="6" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;"><strong>Student Status</strong></th>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: none;">Name:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="2">{{  $student->student_name }}</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Date:</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: none;" colspan="2">{{ \Carbon\Carbon::now()->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: none;">Class:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="2">{{ $student->grade_name}} - {{ $student->grade_class }}</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Class Teacher</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: none;" colspan="2">{{ $classTeacher->teacher_name }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: none;">Serial:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="2">{{ $serial }}</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Date of Registration</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: none;" colspan="2">{{ $student->date_of_registration->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: none;">Days Absent:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="2">{{ $attendance[0]['days_absent'] }} day(s)</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Total Days Absent:</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: none;" colspan="2">{{ $attendance[0]['days_absent'] }}  day(s)</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: none;">Times Late:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="2">{{ $attendance[0]['times_late'] }} minute</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Total Times Late:</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: none;" colspan="2">{{ $attendance[0]['times_late'] }} minute</td>
                </tr>
                <!-- END STUDENT STATUS -->

                <!-- DESCRIPTION OF GRADES -->
                <tr>
                    <th colspan="6" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;"><strong>Description of Grades</strong></th>
                </tr>
                <tr>
                    <th style="text-align:center;border: 1px dotted black;border-left: none;">Scores</th>
                    <th style="text-align:center;border: 1px dotted black;">Grade</th>
                    <th style="text-align:center;border: 1px dotted black;border-right: none;" colspan="4">Achievement of the Curriculum Expectations</th>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: none;">95 – 100</td>
                    <td style="border: 1px dotted black;text-align:center;">A<sup>+</sup></td>
                    <td style="border: 1px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated excellent knowledge and skills, <br> Achievement far exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: none;">85 – 94</td>
                    <td style="border: 1px dotted black;text-align:center;">A</td>
                    <td style="border: 1px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated the required knowledge and skills <br> Achievement exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: none;">75 – 84</td>
                    <td style="border: 1px dotted black;text-align:center;">B</td>
                    <td style="border: 1px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated most of the required knowledge and skills <br> Achievement meets the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: none;">65 – 74</td>
                    <td style="border: 1px dotted black;text-align:center;">C</td>
                    <td style="border: 1px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated some of the required knowledge and skills <br> Achievement approaches the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: none;">45 – 64</td>
                    <td style="border: 1px dotted black;text-align:center;">D</td>
                    <td style="border: 1px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated some of the required knowledge and skills in limited ways. <br> Achievement falls much below the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: none;">&lt; 44</td>
                    <td style="border: 1px dotted black;text-align:center;">E</td>
                    <td style="border: 1px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has failed to demonstrate the required knowledge and skills. <br> Extensive remediation is required.</td>
                </tr>
                <!-- END DESCRIPTION OF GRADES -->

                <!-- LEARNING SKILLS -->
                <tr>
                    <th  colspan="6" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;"><strong>Learning Skills</strong></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px solid black;border-left: none;"><strong>Legend:</strong></td>
                    <td colspan="5" style="text-align:center;border: 1px solid black;border-right: none;"><strong>E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</strong></td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;width:20%;border-left: none;">Independent Work</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->independent_work) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;width:20%;">Use of information</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->use_of_information) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;width:20%;">Class participation</td>
                    <td style="border: 1px dotted black;text-align:center;border-right: none;"> {{ strtoUpper($learningSkills->class_participation) }} </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: none;">Initiative</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->initiative) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Cooperation with others</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->cooperation_with_other) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Problem solving</td>
                    <td style="border: 1px dotted black;text-align:center;border-right: none;"> {{ strtoUpper($learningSkills->problem_solving) }} </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;border-bottom: 1.5px solid black;padding-right:8px;border-left: none;">Homework completion</td>
                    <td style="border: 1px dotted black;border-bottom: 1.5px solid black;text-align:center;"> {{ strtoUpper($learningSkills->homework_completion) }} </td>
                    <td style="text-align:right;border: 1px dotted black;border-bottom: 1.5px solid black;padding-right:8px;">Conflict resolution</td>
                    <td style="border: 1px dotted black;border-bottom: 1.5px solid black;text-align:center;"> {{ strtoUpper($learningSkills->conflict_resolution) }} </td>
                    <td style="text-align:right;border: 1px dotted black;border-bottom: 1.5px solid black;padding-right:8px;">Goal setting to improve work</td>
                    <td style="border: 1px dotted black;border-bottom: 1.5px solid black;text-align:center;border-right: none;"> {{ strtoUpper($learningSkills->goal_setting_to_improve_work) }} </td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1px dotted black;padding-left: 20px;border-left: none;border-right: none;">Strengths/Weaknesses/Next Steps</td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1px dotted black;text-align:left;padding-left: 20px;border-left: none;border-right: none;">{{ $learningSkills->strength_weakness_nextstep }}</td>
                </tr>
                <!-- END LEARNING SKILLS -->

                <!-- SIGNATURE -->
                <tr>
                    <td style="text-align:left;height:100px;padding-left:20px;text-decoration:underline;" colspan="2"></td>
                    <td style="text-align:center;height:100px;" colspan="2"></td>
                    <td style="text-align:right;height:100px;padding-right:20px" colspan="2"></td>
                </tr>
                <tr>
                <td style="text-align:left;padding-left:20px;text-decoration:underline;" colspan="2">{{ $classTeacher->teacher_name }}</td>
                    <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                    <td style="text-align:right;padding-right:20px;text-decoration:underline;" colspan="2">{{ $relation->relationship_name }}</td>
                </tr>
                <tr>
                    <th style="text-align:left;border-bottom: 3px solid black;width:33%;padding-left:20px;" colspan="2"><strong>Class Teacher's Signature</strong></td>
                    <th style="text-align:center;border-bottom: 3px solid black;width:33%;" colspan="2"><strong>Principal's Signature</strong></td>
                    <th style="text-align:right;border-bottom: 3px solid black;width:33%;padding-right:20px" colspan="2"><strong>Parent's Signature</strong></td>
                </tr>
                <!-- END SIGNATURE -->
                <tr>
                    <td colspan="2" style="text-align:left;">{{ \Carbon\Carbon::now()->format('m/d/Y') }}</td>
                    <td colspan="2" style="text-align:center;padding-top: 8px;"> <img src="<?= $cambridge ?>" style="width:40%;" alt="Sample image"></td>
                    <td colspan="2" style="text-align:right;">Page 1 of 2</td>
                </tr>
            </table>
        </div>
    <!-- END PAGE 1 -->
    

    <div class="page-break"></div>


    <!-- PAGE 2 -->
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;">Subjects Report</th>
                    </tr>
                    <tr style="text-align:center;border-bottom: 1px solid black;">
                        <th style="text-align:center;border: 1px dotted black;border-left:none;width:10%">Subjects</th>
                        <th style="text-align:center;border: 1px dotted black;width:10%">Marks</th>
                        <th style="text-align:center;border: 1px dotted black;width:10%">Grades</th>
                        <th style="text-align:center;border: 1px dotted black;border-right:none;width:70%" colspan="5">Strengths/Weaknesses/Next Steps</th>
                    </tr>
                </thead>
                <tbody>

                @foreach ($subjectReports[0]['scores'] as $scores)
                    <!-- SUBJECT REPORT -->
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: none;">{{ $scores['subject_name'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;">{{ $scores['final_score'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{ $scores['grades'] }}</td>
                        <td style="text-align:left;border: 1px dotted black;padding-left:8px;border-right: none;" colspan="5">{{ $scores['comment'] }}</td>
                    </tr>
                    <!-- END SUBJECT REPORT -->
                @endforeach
                    
                <!-- ECA -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;">Extra-Curricular Activity</th>
                    </tr>
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">ECA (Language & Art)</td>
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-left:8px;" colspan="2">Grade</td>
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;" colspan="2">ECA (Singing)</td>
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-left:8px;border-right: none;" colspan="2">Grade</td>
                    </tr>
                    <tr>
                        @if ($student->grade_name === "primary" || $student->grade_name === "Primary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['language_and_art'] }}</td>
                        @elseif ($student->grade_name === "secondary" || $student->grade_name === "Secondary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['eca_1'] }}</td>
                        @endif

                        @if ($student->grade_name === "primary" || $student->grade_name === "Primary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['grades_language_and_art'] }}</td>
                        @elseif ($student->grade_name === "secondary" || $student->grade_name === "Secondary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['grades_eca_1'] }}</td>
                        @endif
                        
                        @if ($student->grade_name === "primary" || $student->grade_name === "Primary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['choice'] }}</td>
                        @elseif ($student->grade_name === "secondary" || $student->grade_name === "Secondary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['eca_2'] }}</td>
                        @endif

                        @if ($student->grade_name === "primary" || $student->grade_name === "Primary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['grades_choice'] }}</td>
                        @elseif ($student->grade_name === "secondary" || $student->grade_name === "Secondary" )
                        <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: none;" colspan="2">{{  $sooa[0]['scores'][0]['grades_eca_2'] }}</td>
                        @endif
                    </tr>
                <!-- END ECA -->

                <!-- OVERALL MARK -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;">Overall Mark</th>
                    </tr>
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: none;width:12.5%;border-bottom: 1px solid black;">Academic</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;width:12.5%;border-bottom: 1px solid black;">ECA</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;width:12.5%;border-bottom: 1px solid black;">Behaviour</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;width:12.5%;border-bottom: 1px solid black;">Attendance</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;width:12%;border-bottom: 1px solid black;">Participation</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;width:12.5%;border-bottom: 1px solid black;">Marks</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;width:12.5%;border-bottom: 1px solid black;">Grade</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;border-right: none;width:12.5%;border-bottom: 1px solid black;">Rank</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: none;">{{  $sooa[0]['scores'][0]['academic'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;"><?= $eca ?></td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{  $sooa[0]['scores'][0]['behavior'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;">{{  $sooa[0]['scores'][0]['attendance'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{  $sooa[0]['scores'][0]['participation'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;"><?= $marks ?></td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;"><?= $marks_grade ?></td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;border-right: none;">{{  $sooa[0]['ranking'] }}</td>
                    </tr>
                <!-- END OVERALL MARK -->

                <!-- REMARKS -->
                    <tr style="border-top: 3px solid black;border-bottom: 1px dotted black;">
                        <th style="text-align:left;">Remarks : </th>
                        <td colspan="7">{{ $learningSkills->remarks }}</td>
                    </tr>
                <!-- END RAMARKS -->

                <!-- SIGNATURE -->
                    <tr>
                        <td style="text-align:left;height:100px;padding-left:20px;text-decoration:underline;" colspan="3"></td>
                        <td style="text-align:center;height:100px;" colspan="2"></td>
                        <td style="text-align:right;height:100px;padding-right:20px" colspan="3"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;padding-left:20px;text-decoration:underline;" colspan="3">{{ $classTeacher->teacher_name }}</td>
                        <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                        <td style="text-align:right;padding-right:20px;text-decoration:underline;" colspan="3">{{ $relation->relationship_name }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left;border-bottom: 3px solid black;width:26%;padding-left:20px;" colspan="3"><strong>Class Teacher's Signature</strong></td>
                        <th style="text-align:center;border-bottom: 3px solid black;width:26%;" colspan="2"><strong>Principal's Signature</strong></td>
                        <th style="text-align:right;border-bottom: 3px solid black;width:26%;padding-right:20px" colspan="3"><strong>Parent's Signature</strong></td>
                    </tr>
                <!-- END SIGNATURE -->

                    <tr>
                        <td colspan="3" style="text-align:left;">{{ \Carbon\Carbon::now()->format('m/d/Y') }}</td>
                        <td colspan="2" style="text-align:center;padding-top: 8px;"> <img src="<?= $cambridge ?>" style="width:60%;" alt="Sample image"></td>
                        <td colspan="3" style="text-align:right;">Page 2 of 2</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <!-- END PAGE 2 -->
</div>

</body>
</html>
