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
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            font-size: 14px;
            margin: 0;
        }

        .footer {
            margin: 0;
            bottom: 0;
            width: 100%;
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
            font-size: 12px;
        }
        table td {
            font-size: 12px;
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
        <!-- <div style="padding-left:30px;padding-right:30px;margin-bottom:10px;">
            <img src="<?= $logo ?>" style=width:100%;height:10%;" alt="Sample image">
        </div> -->
        <h5 style="margin-bottom:5px;"><u>SEMESTER {{ $semester }} REPORT CARD</u></h5>
        <h5 style="margin-bottom:5px;"><u>{{ $academicYear }}</u></h5>
    </div>

    <div style="padding-right:60px;padding-left:60px;">
        <table class="" style="border:none;">
            <!-- STUDENT STATUS -->
            <tr>
                <td style="text-align:left;padding-left:3px;padding-left:3px;"><b>Student Name</b><span class="noto-serif-sc-chinese">姓名</span></td>
                <td style="text-align:left;padding-left:3px;"><b>: {{ ucwords(strtolower($student->student_name)) }}</b></td>
            </tr>
            <tr>
                <td style="text-align:left;padding-left:3px;"><b>Class</b> <span class="noto-serif-sc-chinese">班级</span></td>
                <td style="text-align:left;padding-left:3px;"><b>: {{ $student->grade_name }} - {{ $student->grade_class }}</b></td>
            </tr>
            <!-- END STUDENT STATUS -->
        </table>

        <table class="table" style="margin-top:5px;">
            <tr>
                <td style="width:50%;text-align:center;border: 1px solid black;" colspan="2"><b>Subject</b> <br> <span class="noto-serif-sc-chinese">主题</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>Grade</b> <br> <span class="noto-serif-sc-chinese">年级</span></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>English</b> <span class="noto-serif-sc-chinese">英语</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $english }}</b></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Mathematics</b> <span class="noto-serif-sc-chinese">数学</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $mathematics }}</b></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Chinese</b> <span class="noto-serif-sc-chinese">中文</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $chinese }}</b></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Science</b> <span class="noto-serif-sc-chinese">科学</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $science }}</b></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Character Building</b> <span class="noto-serif-sc-chinese">性格塑造</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $character_building }}</b></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Art & Craft</b> <span class="noto-serif-sc-chinese">艺术和工艺</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $art_and_craft }}</b></td>
            </tr>
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>IT</b> <span class="noto-serif-sc-chinese">信息学</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $it }}</b></td>
            </tr>
            @if ($semester == 1)
                <tr>
                    <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Phonic</b> <span class="noto-serif-sc-chinese">语音</span></td>
                    <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $phonic }}</b></td>
                </tr>
            @else
            @endif
            <tr>
                <td style="width:50%;text-align:left;border: 1px solid black;padding-left:5px;" colspan="2"><b>Conduct</b> <span class="noto-serif-sc-chinese">进行</span></td>
                <td style="width:50%;text-align:center;border: 1px solid black;"><b>{{ $conduct }}</b></td>
            </tr>
            <tr>
                <td style="text-align:justify;border: 1px solid black;padding-left:5px;padding-right:5px;" colspan="3">
                    <b>Remarks :</b> 
                        {{$score['remarks']}}
                </td>
            </tr>
            <tr>
                <td style="text-align:left;padding-left:5px;" colspan="3"><small><b>*Grades </b><span class="noto-serif-sc-chinese">成绩</span> : A+ >95-99; A >85-94; B >75-84; C >65-74; D >45-64</small></td>
            </tr>
        </table>

        <table style="margin-top:10px;">
            <tr>
                <td style="font-size:10px;"><b>Absent</b></td>
                <td style="font-size:10px;"><span class="noto-serif-sc-chinese" style="text-align:right">旷课</span>
                    @if ($attendance[0]['days_absent'] > 0)
                    <b> : {{ $attendance[0]['days_absent'] }} days</b>
                    @else
                    <b> : - days</b>    
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size:10px;"><b>Sick</b></td>
                <td style="font-size:10px;"><span class="noto-serif-sc-chinese">病号</span>
                    @if ($attendance[0]['sick'] > 0)
                    <b> : {{ $attendance[0]['sick'] }} days</b>
                    @else
                    <b> : - days</b>    
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size:10px;"><b>Permission</b></td>
                <td style="font-size:10px;"><span class="noto-serif-sc-chinese">允许</span>
                    @if ($attendance[0]['permission'] > 0)
                    <b> : {{ $attendance[0]['permission'] }} days</b>
                    @else
                    <b> : - days</b>    
                    @endif
                </td>
            </tr>
        </table>
        
        <table class="table">
            @if ($semester == 2)
            <tr>
                <td style="text-align:right;" colspan="3"><small><b>Next Grade</b> <span class="noto-serif-sc-chinese">晋升</span><b>: {{ $promotionGrade }}</b></small></td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <table class="table" style="margin-top:15px;">
            <tr>
                <td style="text-align:center;"><small><b>Teacher's Signature</b></small></td>
                <td style="text-align:center;"><small><b>Principal's Signature</b></small></td>
                <td style="text-align:center;"><small><b>Parent's Signature</b></small></td>
            </tr>
            <tr>
                <td style="text-align:center;"><span class="noto-serif-sc-chinese">老师签名</span></td>
                <td style="text-align:center;"><span class="noto-serif-sc-chinese">校长签名</span></td>
                <td style="text-align:center;"><span class="noto-serif-sc-chinese">家长签名</span></td>
            </tr>
            <tr>
                <td style="height:30px;"></td>
                <td style="height:30px;"></td>
                <td style="height:30px;"></td>
            </tr>
            <tr>
                <td style="font-size:10px;text-align:center;"><p><b>{{ $classTeacher['teacher_name'] }}</b></p></td>
                <td style="font-size:10px;text-align:center;"><p><b>Yuliana Harijanto, B.Eng (Hons)</b></p></td>
                <td style="font-size:10px;text-align:center;">
                    @if ($relation == null)
                    <p><b>-</b></p>
                    @else
                    <p><b>{{ ucwords(strtolower($relation['relationship_name'])) }}</b></p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- END PAGE 1 -->

</div>

</body>
</html>
