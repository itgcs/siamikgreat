<?php
// Set the maximum execution time to 300 seconds
set_time_limit(300);

// Your script logic here
$pathlogo = public_path('images/logo-school.png');
$typelogo = pathinfo($pathlogo, PATHINFO_EXTENSION);
$datalogo = file_get_contents($pathlogo);
$logo = 'data:image/' . $typelogo . ';base64,' . base64_encode($datalogo);

$pathcambridge = public_path('images/lcn.png');
$typecambridge = pathinfo($pathcambridge, PATHINFO_EXTENSION);
$datacambridge = file_get_contents($pathcambridge);
$cambridge = 'data:image/' . $typecambridge . ';base64,' . base64_encode($datacambridge);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@200..900&display=swap');
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* * {
            font-family: 'Ma Shan Zheng', DejaVu Sans, sans-serif;
            font-size: xx-large;
        } */

        .noto-serif-sc-chinese {
            font-family: "Noto Serif SC", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .noto-serif-sc-simbol {
            font-family: "Noto Serif SC", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
        
        .header {
            margin: 0;
            margin-top: 65px;
            width: 100%;
            text-align: center;
        }
        .header h1, .header h2, .header h5, .header h4, .header h5 {
            font-size: 10px;
            margin: 0;
        }

        .footer {
            margin: 0;
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
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
            font-size: 10px;
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
        <!-- <div style="padding-left:50px;padding-right:50px;margin-bottom:10px;">
            <img src="<?= $logo ?>" style="width:100%;height:10%;" alt="Sample image">
        </div> -->
        <h5 style="margin-bottom:5px;"><u>REPORT CARD</u></h5>
        <h5 style="margin-bottom:2px;"><u>NURSERY</u></h5>
        @if ($mid == 0)
            @if ($semester == 1)
                <h5><u>TERM 1 SEMESTER {{ $semester }}/{{ $academicYear }}</u></h5>
            @elseif ($semester == 2)
                <h5><u>TERM 3 SEMESTER {{ $semester }}/{{ $academicYear }}</u></h5>
            @endif
        @else 
            @if ($semester == 1)
                <h5><u>TERM 2 SEMESTER {{ $semester }}/{{ $academicYear }}</u></h5>
            @elseif ($semester == 2)
                <h5><u>TERM 4 SEMESTER {{ $semester }}/{{ $academicYear }}</u></h5>
            @endif
        @endif
    </div>

    <div style="margin-top:5px;">
        <table class="table border-solid-black" style="border: 1px solid black;">
            <!-- STUDENT STATUS -->
            <tr>
                <td style="text-align:left;width:30%;border: 1px solid black;padding-left:3px;padding-left:3px;">Student Name <span class="noto-serif-sc-chinese">姓名</span></td>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;"><b>{{ $student->student_name }}</b></td>
            </tr>
            <tr>
                <td style="text-align:left;width:30%;border: 1px solid black;padding-left:3px;">Class <span class="noto-serif-sc-chinese">班级</span></td>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;"><b>{{ $student->grade_name }}</b></td>
            </tr>
            <tr>
                <td style="text-align:left;width:30%;border: 1px solid black;padding-left:3px;">Absent <span class="noto-serif-sc-chinese">缺席</span></td>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;">
                @if ($attendance[0]['days_absent'] > 0)
                    {{ $attendance[0]['days_absent']}} day(s)
                @else
                    - day(s)  
                @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:30%;border: 1px solid black;padding-left:3px;">Permission <span class="noto-serif-sc-chinese">请求许可</span></td>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;"> 
                @if ( $attendance[0]['permission'] > 0 )
                   {{ $attendance[0]['permission']}} day(s)
                @else
                    - day(s)  
                @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:30%;border: 1px solid black;padding-left:3px;">Sick <span class="noto-serif-sc-chinese">生病</span></td>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;"> 
                @if ( $attendance[0]['sick'] > 0 )
                    {{ $attendance[0]['sick'] }} day(s)
                @else
                    - day(s)  
                @endif
                </td>
            </tr>
            <!-- END STUDENT STATUS -->
        </table>

        <table class="table border-solid-black" style="margin-top:5px;">
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:15px;font-size:12px;"><b>Able to Understand</b> <span class="noto-serif-sc-chinese">能够理解</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol"><small style="font-size:7px;"><b>Excellent</b></small> <br> <span class="noto-serif-sc-chinese">优</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol"><small style="font-size:7px;"><b>Satisfactory</b></small> <br> <span class="noto-serif-sc-chinese">中</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol"><small style="font-size:7px;"><b>Weak</b></small> <br> <span class="noto-serif-sc-chinese">差</span></td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;">Songs <span class="noto-serif-sc-chinese">歌曲</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->songs == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->songs == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->songs == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;">Prayer <span class="noto-serif-sc-chinese">祈祷文</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->prayer == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->prayer == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->prayer == 3)
                        √
                    @endif
                </td>
            </tr>
        </table>


        <!-- ABLE TO RECOGNIZE -->
        <table class="table border-solid-black" style="margin-top:5px;">
            <tr>
                <td style="text-align:left;width:70%;border:none;padding-left:15px;font-size:12px;"><b>Able to Recognize</b> <span class="noto-serif-sc-chinese">能够理解</span></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Colour <span class="noto-serif-sc-chinese">颜色</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->colour == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->colour == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->colour == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Number <span class="noto-serif-sc-chinese">数字</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->number == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->number == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->number == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Object <span class="noto-serif-sc-chinese">物体</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->object == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->object == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->object == 3)
                        √
                    @endif
                </td>
            </tr>
        </table>
        <!-- END ABLE TO RECOGNIZE -->

        <!-- PHYSICAL SKILL / MOTOR SKILL -->
        <table class="table border-solid-black" style="margin-top:5px;">
            <tr>
                <td style="text-align:left;width:70%;border:none;padding-left:15px;font-size:12px;"><b>Physical Skill / Motor Skill</b> <span class="noto-serif-sc-chinese">肢体技能 / 运动技能</span></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Body Movement <span class="noto-serif-sc-chinese">身体动作</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->body_movement == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->body_movement == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->body_movement == 3)
                        √
                    @endif
                </td>
            </tr>
        </table>
        <!-- END PHYSICAL SKILL / MOTOR SKILL -->

        <!-- Ability Art and Craft -->
        <table class="table border-solid-black" style="margin-top:5px;">
            <tr>
                <td style="text-align:left;width:70%;border:none;padding-left:15px;font-size:12px;"><b>Ability Art and Craft</b> <span class="noto-serif-sc-chinese">美术和手工能力</span></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Colouring <span class="noto-serif-sc-chinese">上色</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->colouring == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->colouring == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->colouring == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Painting <span class="noto-serif-sc-chinese">画画</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->painting == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->painting == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->painting == 3)
                        √
                    @endif
                </td>
            </tr>
        </table>
        <!-- END Ability Art and Craft -->

        <!-- CHINESE -->
        <table class="table border-solid-black" style="margin-top:5px;">
            <tr>
                <td style="text-align:left;width:70%;border:none;padding-left:15px;font-size:12px;"><b>Chinese</b> <span class="noto-serif-sc-chinese">华文</span></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Songs <span class="noto-serif-sc-chinese">歌</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->chinese_songs == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->chinese_songs == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->chinese_songs == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Ability to recognize the objects <span class="noto-serif-sc-chinese">识别物体能力</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->ability_to_recognize_objects == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->ability_to_recognize_objects == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->ability_to_recognize_objects == 3)
                        √
                    @endif
                </td>
            </tr>
        </table>
        <!-- END CHINESE -->

        <!-- SLEF DAN SOCIAL AWARENESS -->
        <table class="table border-solid-black" style="margin-top:5px;">
            <tr>
                <td style="text-align:left;width:70%;border:none;padding-left:15px;font-size:12px;;"><b>Self and Social Awareness</b> <span class="noto-serif-sc-chinese">自我意识</span></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
                <td style="text-align:center;width:10%;border:none;"></td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Able to own up to mistakes <span class="noto-serif-sc-chinese">能够承认错误</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->able_to_own_up_to_mistakes == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->able_to_own_up_to_mistakes == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->able_to_own_up_to_mistakes == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Takes care of personal belongings and property <span class="noto-serif-sc-chinese">保管好自己的东西</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->takes_care_of_personal_belongings_and_property == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->takes_care_of_personal_belongings_and_property == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->takes_care_of_personal_belongings_and_property == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Demonstrates importance of self-control <span class="noto-serif-sc-chinese">自我控制能力</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->demonstrates_importance_of_self_control == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->demonstrates_importance_of_self_control == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->demonstrates_importance_of_self_control == 3)
                        √
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align:left;width:70%;border: 1px solid black;padding-left:3px;;">Understands that having a temper is not acceptable behavior
                for problem-solving <span class="noto-serif-sc-chinese">懂得控制脾气</span></td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->management_emotional_problem_solving == 1)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->management_emotional_problem_solving == 2)
                        √
                    @endif
                </td>
                <td style="text-align:center;width:10%;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                    @if ($score->management_emotional_problem_solving == 3)
                        √
                    @endif
                </td>
            </tr>
        </table>
        <!-- END CHINESE -->

        <div class="footer">
            <img src="<?= $cambridge ?>" style="width:24%;height:5%;">
        </div>
    </div>
    <!-- END PAGE 1 -->

    <div class="page-break"></div>

    <!-- PAGE 2 -->
    <div>
        <table class="table">
            <tr>
                <td style="text-align:center;padding-bottom:15px;" colspan="2">Remarks <span class="noto-serif-sc-chinese">学期的评语</span></td>
            </tr>
            <tr>
                <td style="text-align:justify;border-bottom: 1px solid black" colspan="2">{{$score->remarks}}</td>
            </tr>
            @if ($semester == 2)
                <tr>
                    <td style="text-align:left;justify:center;padding-left:3px" colspan="2"><b>Promoted to</b> <span class="noto-serif-sc-chinese">晋升</span>: {{ $promotionGrade }}</td>
                </tr>
            @endif
            <tr>
                <td style="height:100px;"></td>
                <td style="height:100px;"></td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    <div style="display: inline-block; width: 80%; border-bottom: 1px solid black;">{{ $classTeacher->teacher_name }}</div>
                </td>
                <td style="text-align:center;">
                    <div style="display: inline-block; width: 80%; border-bottom: 1px solid black;">Yuliana Harijanto, B.Eng (Hons)</div>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;"><b>Class Teacher's Signature</b></td>
                <td style="text-align:center;"><b>Principals's Signature</b></td>
            </tr>
            <tr>
                <td style="text-align:center;"><b><span class="noto-serif-sc-chinese">老师签名</span></b></td>
                <td style="text-align:center;"><b><span class="noto-serif-sc-chinese">校长签名</span></b></td>
            </tr>
            <tr>
                <td style="height:100px;" colspan="2"></td>
            </tr>
            <tr>
                @if ($relation == null)
                <td style="text-align:center;" colspan="2">
                    <div style="display: inline-block; width: 45%; border-bottom: 1px solid black;">-</div>
                </td>
                @else
                <td style="text-align:center;" colspan="2">
                    <div style="display: inline-block; width: 45%; border-bottom: 1px solid black;">{{ $relation->relationship_name }}</div>
                </td>
                @endif
            </tr>
            <tr>
                <td style="text-align:center;" colspan="2"><b>Parent's Signature</b></td>
            </tr>
            <tr>
                <td style="text-align:center;" colspan="2"><b><span class="noto-serif-sc-chinese">家长签名</span></b></td>
            </tr>
        </table>
    </div>
    <!-- END PAGE 2 -->
</div>

</body>
</html>
