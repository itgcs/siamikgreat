<?php
// Set the maximum execution time to 300 seconds
set_time_limit(300);

// Your script logic here
$pathlogo = public_path('images/logo-school.png');
$typelogo = pathinfo($pathlogo, PATHINFO_EXTENSION);
$datalogo = file_get_contents($pathlogo);
$logo = 'data:image/' . $typelogo . ';base64,' . base64_encode($datalogo);

$pathcambridge = public_path('images/cambridge.png');
$typecambridge = pathinfo($pathcambridge, PATHINFO_EXTENSION);
$datacambridge = file_get_contents($pathcambridge);
$cambridge = 'data:image/' . $typecambridge . ';base64,' . base64_encode($datacambridge);

$grade_name = $student->grade_name;
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
            margin-top: -30px;
            text-align: center;
        }
        .header h1, .header h2 ,.header h5, .header h4, .header h5 {
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
            font-size:10px;
        }
        .table th {
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
            <div style="padding-left:50px;padding-right:50px;margin-bottom:5px;">
                <img src="<?= $logo ?>" style="width:90%;height:8%;" alt="Sample image">
            </div>
            <h5>Report Card</h5>
            <h5>Semester II School Year {{ $academicYear }}</h5>
        </div>

        <div>
            <table class="table">
                <!-- STUDENT STATUS -->
                <tr>
                    <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;"><b>Student Status</b></th>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">Name:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="3">{{  $student->student_name }}</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;"  colspan="2">Date:</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ \Carbon\Carbon::now()->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">Class:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="3">{{ $student->grade_name}} - {{ $student->grade_class }}</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;"  colspan="2">Class Teacher</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $classTeacher->teacher_name }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">Serial:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="3">{{ $serial }}</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;"  colspan="2">Date of Registration</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $student->date_of_registration->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">Days Absent:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="3">{{ $attendance[0]['days_absent'] }} day(s)</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;" colspan="2">Total Days Absent:</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $attendance[0]['days_absent'] }}  day(s)</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">Times Late:</td>
                    <td style="border: 1px dotted black;padding-left:8px;" colspan="3">{{ $attendance[0]['times_late'] }} minute</td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;"  colspan="2">Total Times Late:</td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $attendance[0]['times_late'] }} minute</td>
                </tr>
                <!-- END STUDENT STATUS -->

                <!-- PROMOTION STATUS -->
                <tr>
                    <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-left: 1px solid black;border-right: 1px solid black"><strong>Promotion</strong></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: none;border-left: 1px solid black;" rowspan="3" colspan="1"><strong>Promotion Status</strong></td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: 1px solid black;" colspan="7">
                        @if ($learningSkills->promotion_status === 1)
                        Progressing well towards promotion
                        @else
                        <s>Progressing well towards promotion</s>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: 1px solid black;" colspan="7">
                        <div style="display: flex; align-items: center;">
                            @if ($learningSkills->promotion_status === 2)
                            Progressing with some difficulty towards promotion
                            @else
                            <s>Progressing with some difficulty towards promotion</s>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: 1px solid black;" colspan="7">
                        <div style="display: flex; align-items: center;">
                            @if ($learningSkills->promotion_status === 3)
                            No promotion
                            @else
                            <s>No promotion</s>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: 1px solid black;"colspan="1"><strong>Next Grade</strong></td>
                    <td style="border: 1px dotted black;padding-left:8px;border-right: 1px solid black;" colspan="7">{{ $promotionGrade }}</td>
                </tr>
                <!-- END PROMOTION STATUS -->

                <!-- DESCRIPTION OF GRADES -->
                <tr>
                    <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;"><b>Description of Grades</b></th>
                </tr>
                <tr>
                    <th style="text-align:center;border: 1px dotted black;border-left: solid 1px black;">Scores</th>
                    <th style="text-align:center;border: 1px dotted black;">Grade</th>
                    <th style="text-align:center;border: 1px dotted black;border-right: solid 1px black;" colspan="6">Achievement of the Curriculum Expectations</th>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: solid 1px black;">95 – 100</td>
                    <td style="border: 1px dotted black;text-align:center;">A<sup>+</sup></td>
                    <td style="border: 1px dotted black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated excellent knowledge and skills, <br> Achievement far exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: solid 1px black;">85 – 94</td>
                    <td style="border: 1px dotted black;text-align:center;">A</td>
                    <td style="border: 1px dotted black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated the required knowledge and skills <br> Achievement exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: solid 1px black;">75 – 84</td>
                    <td style="border: 1px dotted black;text-align:center;">B</td>
                    <td style="border: 1px dotted black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated most of the required knowledge and skills <br> Achievement meets the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: solid 1px black;">65 – 74</td>
                    <td style="border: 1px dotted black;text-align:center;">C</td>
                    <td style="border: 1px dotted black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated some of the required knowledge and skills <br> Achievement approaches the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: solid 1px black;">45 – 64</td>
                    <td style="border: 1px dotted black;text-align:center;">D</td>
                    <td style="border: 1px dotted black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated some of the required knowledge and skills in limited ways. Achievement falls much below the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px dotted black;text-align:center;border-left: solid 1px black;">&lt; 44</td>
                    <td style="border: 1px dotted black;text-align:center;">E</td>
                    <td style="border: 1px dotted black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has failed to demonstrate the required knowledge and skills. <br> Extensive remediation is required.</td>
                </tr>
                <!-- END DESCRIPTION OF GRADES -->

                <!-- LEARNING SKILLS -->
                <tr>
                    <th  colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;"><b>Learning Skills</b></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px solid black;border-left: solid 1px black;"><b>Legend:</b></td>
                    <td colspan="7" style="text-align:center;border: 1px solid black;border-right: solid 1px black;"><b>E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</b></td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;" colspan="2">Independent Work</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->independent_work) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;">Use of information</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->use_of_information) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;" colspan="2">Class participation</td>
                    <td style="border: 1px dotted black;text-align:center;border-right: solid 1px black;"> {{ strtoUpper($learningSkills->class_participation) }} </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;" colspan="2">Initiative</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->initiative) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;" >Cooperation with others</td>
                    <td style="border: 1px dotted black;text-align:center;"> {{ strtoUpper($learningSkills->cooperation_with_other) }} </td>
                    <td style="text-align:right;border: 1px dotted black;padding-right:8px;" colspan="2">Problem solving</td>
                    <td style="border: 1px dotted black;text-align:center;border-right: solid 1px black;"> {{ strtoUpper($learningSkills->problem_solving) }} </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px dotted black;border-bottom: 1.5px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">Homework completion</td>
                    <td style="border: 1px dotted black;border-bottom: 1.5px solid black;text-align:center;"> {{ strtoUpper($learningSkills->homework_completion) }} </td>
                    <td style="text-align:right;border: 1px dotted black;border-bottom: 1.5px solid black;padding-right:8px;">Conflict resolution</td>
                    <td style="border: 1px dotted black;border-bottom: 1.5px solid black;text-align:center;"> {{ strtoUpper($learningSkills->conflict_resolution) }} </td>
                    <td style="text-align:right;border: 1px dotted black;border-bottom: 1.5px solid black;padding-right:8px;" colspan="2">Goal setting to improve work</td>
                    <td style="border: 1px dotted black;border-bottom: 1.5px solid black;text-align:center;border-right: solid 1px black;"> {{ strtoUpper($learningSkills->goal_setting_to_improve_work) }} </td>
                </tr>
                <!-- END LEARNING SKILLS -->

                <!-- SIGNATURE -->
                <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                    <td style="text-align:left;height:30px;text-decoration:underline;" colspan="3"></td>
                    <td style="text-align:center;height:0px;" colspan="2"></td>
                    <td style="text-align:right;height:30px;padding-right:20px" colspan="3"></td>
                </tr>
                <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                    <td style="text-align:center;text-decoration:underline;" colspan="3">{{ $classTeacher->teacher_name }}</td>
                    <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                    <td style="text-align:center;text-decoration:underline;" colspan="3">
                        @if ($relation == null)
                        <p><b>-</b></p>
                        @else
                        <p><b>{{ $relation['relationship_name'] }}</b></p>
                        @endif
                    </td>
                </tr>
                <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                    <th style="text-align:center;border-bottom: 3px solid black;" colspan="3"><b>Class Teacher's Signature</b></td>
                    <th style="text-align:center;border-bottom: 3px solid black;" colspan="2"><b>Principal's Signature</b></td>
                    <th style="text-align:center;border-bottom: 3px solid black;" colspan="3"><b>Parent's Signature</b></td>
                </tr>
                <!-- END SIGNATURE -->
                <tr>
                    <td colspan="2" style="text-align:left;">{{ \Carbon\Carbon::now()->format('m/d/Y') }}</td>
                    <td colspan="4" style="text-align:center;padding-top: 8px;"> <img src="<?= $cambridge ?>" style="width:40%;" alt="Sample image"></td>
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
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Subjects Report</th>
                    </tr>
                    <tr style="text-align:center;border-bottom: 1px solid black;">
                        <th style="text-align:center;border: 1px dotted black;border-left:solid 1px black;width:10%">Subjects</th>
                        <th style="text-align:center;border: 1px dotted black;width:10%">Marks</th>
                        <th style="text-align:center;border: 1px dotted black;width:10%">Grades</th>
                        <th style="text-align:center;border: 1px dotted black;border-right:solid 1px black;width:70%" colspan="5">Strengths/Weaknesses/Next Steps</th>
                    </tr>
                </thead>
                <tbody>

                @foreach ($subjectReports[0]['scores'] as $scores)
                    <!-- SUBJECT REPORT -->
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">{{ $scores['subject_name'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;">{{ $scores['final_score'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{ $scores['grades'] }}</td>
                        <td style="text-align:left;border: 1px dotted black;padding-left:8px;border-right: solid 1px black;" colspan="5">{{ $scores['comment'] }}</td>
                    </tr>
                    <!-- END SUBJECT REPORT -->
                @endforeach
                    
                <!-- ECA -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Extra-Curricular Activity</th>
                    </tr>

                    @if (strtolower($grade_name) == "primary")
                        <tr>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">ECA (Language & Art)</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-left:8px;" colspan="2">Grade</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;" colspan="2">
                                ECA @if (empty($eca))
                                @else
                                    ({{ $eca['eca_1'] }})
                                @endif
                            </td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">Grade</td>
                        </tr>
                    @elseif (strtolower($grade_name) == "secondary")
                        <tr>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">
                                ECA @if (empty($eca))
                                @else
                                    ({{ $eca['eca_1'] }})
                                @endif
                            </td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-left:8px;" colspan="2">Grade</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;" colspan="2">
                                ECA @if (empty($eca) || count($eca) == 3)
                                ({{ $eca['eca_2'] }})
                                @else
                                -
                                @endif
                            </td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">Grade</td>
                        </tr>
                    @endif
                    <tr>
                        @if (strtolower($grade_name) == "primary")
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">{{ $sooa[0]['scores'][0]['language_and_art'] }}</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: dotted 1px black;" colspan="2">{{  $sooa[0]['scores'][0]['grades_language_and_art'] }}</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: dotted 1px black;" colspan="2">
                                @if ($sooa[0]['scores'][0]['choice'] == 0)
                                    -
                                @else
                                    {{  $sooa[0]['scores'][0]['choice'] }}
                                @endif
                            </td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: dottted 1px black;border-right: 1px solid black;" colspan="2">{{  $sooa[0]['scores'][0]['grades_choice'] }}</td>
                        @elseif (strtolower($grade_name) == "secondary")
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">{{ $sooa[0]['scores'][0]['eca_1'] }}</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: dotted 1px black;" colspan="2">{{  $sooa[0]['scores'][0]['grades_eca_1'] }}</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: dotted 1px black;" colspan="2">{{  $sooa[0]['scores'][0]['eca_2'] }}</td>
                            <td style="text-align:center;border: 1px dotted black;border-bottom: 1px solid black;padding-right:8px;border-left: dotted 1px black;border-right: 1px solid black;" colspan="2">{{  $sooa[0]['scores'][0]['grades_eca_2'] }}</td>
                        @endif
                    </tr>
                <!-- END ECA -->

                <!-- OVERALL MARK -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Overall Mark</th>
                    </tr>
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;border-left: solid 1px black;width:12.5%;border-bottom: 1px solid black;">Academic</td>
                        <td style="text-align:center;border: 1px dotted black;width:11.5%;border-bottom: 1px solid black;">ECA</td>
                        <td style="text-align:center;border: 1px dotted black;width:12.5%;border-bottom: 1px solid black;">Behaviour</td>
                        <td style="text-align:center;border: 1px dotted black;width:15.5%;border-bottom: 1px solid black;">Attendance</td>
                        <td style="text-align:center;border: 1px dotted black;width:15.5%;border-bottom: 1px solid black;">Participation</td>
                        <td style="text-align:center;border: 1px dotted black;width:11.5%;border-bottom: 1px solid black;">Marks</td>
                        <td style="text-align:center;border: 1px dotted black;width:10.5%;border-bottom: 1px solid black;">Grade</td>
                        <td style="text-align:center;border: 1px dotted black;border-right: solid 1px black;width:10.5%;border-bottom: 1px solid black;">Rank</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;border-left: solid 1px black;">{{  $sooa[0]['scores'][0]['academic'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;">{{ $sooa[0]['scores'][0]['eca_aver'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{  $sooa[0]['scores'][0]['behavior'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;">{{  $sooa[0]['scores'][0]['attendance'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{  $sooa[0]['scores'][0]['participation'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;">{{ $sooa[0]['scores'][0]['final_score'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-right:8px;">{{ $sooa[0]['scores'][0]['grades_final_score'] }}</td>
                        <td style="text-align:center;border: 1px dotted black;padding-left:8px;border-right: solid 1px black;">{{  $sooa[0]['ranking'] }}</td>
                    </tr>
                <!-- END OVERALL MARK -->

                <!-- FINAL SCORE -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Final Score</th>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:center;border-left:1px solid black;border-bottom: 1px dotted black;border-right: 1px dotted black;"><b>Average Mark</b></td>
                        <td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px dotted black;border-left: 1px dotted black;"><b>Grade</b></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:center;border-left:1px solid black;border-bottom: 1px solid black;border-right: 1px dotted black;">{{ $tcop[0]['final_score'] }}</td>
                        <td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;border-left: 1px dotted black;">{{ $tcop[0]['grades_final_score'] }}</td>
                    </tr>
                <!-- END FINAL SCORE -->


                <!-- SIGNATURE -->
                    <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                        <td style="height:50px;" colspan="3"></td>
                        <td style="height:50px;" colspan="2"></td>
                        <td style="height:50px;" colspan="3"></td>
                    </tr>
                    <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                        <td style="text-align:center;text-decoration:underline;" colspan="3">{{ $classTeacher->teacher_name }}</td>
                        <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                        <td style="text-align:center;text-decoration:underline;" colspan="3">
                            @if ($relation == null)
                            <p><b>-</b></p>
                            @else
                            <p><b>{{ $relation['relationship_name'] }}</b></p>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                        <th style="text-align:left;border-bottom: 3px solid black;" colspan="3"><b>Class Teacher's Signature</b></td>
                        <th style="text-align:center;border-bottom: 3px solid black;" colspan="2"><b>Principal's Signature</b></td>
                        <th style="text-align:center;border-bottom: 3px solid black;" colspan="3"><b>Parent's Signature</b></td>
                    </tr>
                <!-- END SIGNATURE -->

                <tr>
                    <td colspan="2" style="text-align:left;">{{ \Carbon\Carbon::now()->format('m/d/Y') }}</td>
                    <td colspan="4" style="text-align:center;padding-top: 8px;"> <img src="<?= $cambridge ?>" style="width:40%;" alt="Sample image"></td>
                    <td colspan="2" style="text-align:right;">Page 2 of 2</td>
                </tr>
                </tbody>
            </table>
        </div>
    <!-- END PAGE 2 -->
</div>

</body>
</html>
