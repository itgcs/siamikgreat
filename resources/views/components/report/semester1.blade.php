@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            @if (session('role') == 'superadmin')
              <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Report Card</a></li>
            @elseif (session('role') == 'admin')
            <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Report Card</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail Report Card</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
        <div class="col">
            <p class="text-xs text-bold">Report Card Semester 1</p>
            <p class="text-xs">Class Teacher : {{ $data['grade']->teacher_name }}</p>
            <p class="text-xs">Class: {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} </p>
            <p class="text-xs">Date  : {{date('d-m-Y')}}</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form id="confirmForm"  method="POST">
        @elseif (session('role') == 'admin')
            <form id="confirmForm" method="POST">
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostReportCard1')}}>
        @endif
        @csrf
        
        @if ($data['status'] == null)
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Acc REPORT CARD</button>
                </div>
            </div>
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                </div>
            </div>  
        @endif

        @if (!empty($data['students']))
        
        <table class="table table-striped table-bordered bg-white" style=" width: 2000px;">
            @if ($data['status'] == null)
            <thead>
                <tr>
                    <th colspan="2" style="vertical-align : middle;text-align:center;">Legend</th>
                    <th colspan="10" style="vertical-align : middle;text-align:left;">E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</th>
                </tr>
                <tr>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Independent work</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Initiative</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Homework Completion</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Use of Information</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Cooperation with Others</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Conflict Resolution</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Class Participation</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Problem Solving</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Goal setting to improve work</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Strengths/Weeakness/Next Steps</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Remarks</th>
                </tr>
            </thead>

            @elseif ($data['status']->status != null && $data['status']->status == 1)
            <thead>
                <tr>
                    <th colspan="2" style="vertical-align : middle;text-align:center;">Legend</th>
                    <th colspan="11" style="vertical-align : middle;text-align:left;">E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</th>
                </tr>
                <tr>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Independent work</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Initiative</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Homework Completion</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Use of Information</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Cooperation with Others</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Conflict Resolution</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Class Participation</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Problem Solving</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Goal setting to improve work</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Strengths/Weeakness/Next Steps</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Remarks</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Print Report Card</th>
                </tr>
            </thead>
            @endif

            <tbody>
            @if(!empty($data['result']))
                @foreach ($data['result'] as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student['student_name'] }}</td>
                        @foreach ($student['scores'] as $index => $score)
                            <!-- Independent Work -->
                            <td class="text-center">{{ $score['independent_work'] }}</td>

                            <!-- Initiative -->
                            <td class="text-center">{{ $score['initiative'] }}</td>

                            <!-- Homework_completion -->
                            <td class="text-center">{{ $score['homework_completion'] }}</td>
        

                            <!-- Use_of_information -->
                            <td class="text-center">{{ $score['use_of_information'] }}</td>
        

                            <!-- Cooperation_with_other -->
                            <td class="text-center">{{ $score['cooperation_with_other'] }}</td>
        

                            <!-- Conflict_resolution -->
                            <td class="text-center">{{ $score['conflict_resolution'] }}</td>
        

                            <!-- Class_participation -->
                            <td class="text-center">{{ $score['class_participation'] }}</td>

                            <!-- Problem_solving -->
                            <td class="text-center">{{ $score['problem_solving'] }}</td>
        

                            <!-- Goal_setting_to_improve_work -->
                            <td class="text-center">{{ $score['goal_setting_to_improve_work'] }}</td>
        

                            <!-- Strengths/weakness/nextstep -->
                            <td class="text-left">{{ $score['strength_weakness_nextstep'] }}</td>

                            <td class="text-center">{{ $score['remarks'] }}</td>

                            @if ($data['status'] !== null)
                            <td>
                                <a class="btn btn-primary btn"
                                    href="{{url('teacher/dashboard/report/semester1/print') . '/' . $student['student_id']}}">
                                    Print
                                </a>
                            </td>
                            @endif
                        @endforeach
                @endforeach
            @else
                @foreach ($data['students'] as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student['name'] }}</td>

                        <!-- Independent_work -->
                        <td class="text-center">
                            <input name="independent_work[]" type="text" class="form-control" id="iw" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>

                        <!-- Initiative -->
                        <td class="text-center">
                            <input name="initiative[]" type="text" class="form-control" id="in" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>

                        <!-- Homework_completion -->
                        <td class="text-center">
                        <input name="homework_completion[]" type="text" class="form-control" id="hc" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>


                        <!-- Use_of_information -->
                        <td class="text-center">
                        <input name="use_of_information[]" type="text" class="form-control" id="uoi" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>


                        <!-- Cooperation_with_other -->
                        <td class="text-center">
                        <input name="cooperation_with_other[]" type="text" class="form-control" id="cwo" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>


                        <!-- Conflict_resolution -->
                        <td class="text-center">
                        <input name="conflict_resolution[]" type="text" class="form-control" id="cr" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>


                        <!-- Class_participation -->
                        <td class="text-center">
                        <input name="class_participation[]" type="text" class="form-control" id="cp" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>

                        <!-- Problem_solving -->
                        <td class="text-center">
                        <input name="problem_solving[]" type="text" class="form-control" id="ps" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>


                        <!-- Goal_setting_to_improve_work -->
                        <td class="text-center">
                        <input name="goal_setting_to_improve_work[]" type="text" class="form-control" id="gstiw" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>


                        <!-- Strengths/weakness/nextstep -->
                        <td class="text-center">
                        <input name="strength_weakness_nextstep[]" type="text" class="form-control"  autocomplete="off" required></td>

                        <td class="text-center">
                        <input name="remarks[]" type="text" class="form-control"  autocomplete="off"></td>
                
                        <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['id'] }}">
                </tr>
                @endforeach
                <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                <input name="teacher_id" type="number" class="form-control d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}"> 
            @endif
                    
            </tbody>
        </table>
        @else
            <p>Empty Data Student !!!</p>
        @endif
        
        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Report Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to submit report card?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Acc</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('confirmAccScoring').addEventListener('click', function() {
        document.getElementById('confirmForm').submit();
    });
</script>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    function validateInput(input) {
        var validChars = ['E', 'G', 'S', 'N'];
        var value = input.value.toUpperCase();
        if (!validChars.includes(value) && value !== '') {
            input.value = '';
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter only "E", "G", "S", or "N".'
            });
        }
    }
</script>

@if(session('after_post_report_card1'))
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    setTimeout(() => {
        Toast.fire({
            icon: 'success',
            title: 'Successfully post report card semester 1 in the database.',
        });
    }, 1500);
</script>
@endif


@endsection
