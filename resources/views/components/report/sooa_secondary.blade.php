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
              <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Reports</a></li>
            @elseif (session('role') == 'admin')
            <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Reports</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail Sooa</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
        <div class="col">
            <p class="text-xs font-bold">Summary of Academic Assessment</p>
            <p class="text-xs">Class Teacher: {{ $data['grade']->teacher_name }}</p>
            <p class="text-xs">Class : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} </p>
            <p class="text-xs">Date : {{date('d-m-Y')}}</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form method="POST" action={{route('actionPostScoringSooaSecondary')}}>
        @elseif (session('role') == 'admin')
            <form method="POST" action={{route('actionAdminPostScoringSooaSecondary')}}>
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringSooaSecondary')}}>
        @endif
        @csrf

        @if ($data['status'] == null)
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Submit SOOA</button>
                </div>
            </div>
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline SOOA</a>
                    @endif
                </div>
            </div>  
        @endif

        <table class="table table-striped table-bordered bg-white" style="width: 2000px">
            @if ($data['status'] == null)

                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Academic</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 1</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 2</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Self-Development</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA Aver</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Behavior</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Attendance</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Participation</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Total</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grades</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Rank</th>
                        </tr>

                        <tr>
                            <!-- Major Subjects -->
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <!-- END MAJOR SUBJECTS -->
                            
                            <!-- MINOR SUBJECTS -->
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <!-- END MINOR SUBJECTS -->
                            
                            <!-- SUPPLEMENTARY SUBJECTS -->
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <!-- END SUPPLEMENTARY SUBJECTS -->
                        </tr>
                    </thead>

                    @if (!empty($data['students']))
                    <tbody>
                    @foreach ($data['students'] as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student['student_name'] }}</td>

                            @if (!empty($student['scores']))
                                @foreach ($student['scores'] as $index => $score)

                                    <!-- ACADEMIC -->
                                    <td class="text-center">{{ $score['academic'] }}</td>
                                    <td class="text-center">{{ $score['grades_academic'] }}</td>

                                    <!-- ECA 1 -->
                                    <td class="text-center">
                                        <input name="eca_1[]" min="0" max="100" type="number" class="form-control" id="eca_1" value="{{ $score['eca_1'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_1'] }}</td>
                                    
                                    <!-- ECA 2 -->  
                                    <td class="text-center">
                                        <input name="eca_2[]"  min="0" max="100" type="number" class="form-control" id="eca_2" value="{{ $score['eca_2'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_2'] }}</td>

                                    <!-- Self-Development -->
                                    <td class="text-center">
                                        <input name="self_development[]" min="0" max="100" type="number" class="form-control" id="self_development" value="{{ $score['self_development'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
                                    
                                    <!-- ECA Aver -->
                                    <td class="text-center">
                                        <input name="eca_aver[]" min="0" max="100" type="number" class="form-control" id="eca_aver" value="{{ $score['eca_aver'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                                    <td class="text-center">
                                        <input name="behavior[]" min="0" max="100" type="number" class="form-control" id="behavior" value="{{ $score['behavior'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>

                                    <!-- Attendance -->
                                    <td class="text-center">{{ $score['attendance'] }}</td>
                                    <td class="text-center">{{ $score['grades_attendance'] }}</td>

                                    <!-- Participation -->
                                    <td class="text-center">
                                        <input name="participation[]"  min="0" max="100" type="number" class="form-control" id="participation"value="{{ $score['participation'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_participation'] }}</td>
                                    
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">

                                    <td class="text-center">{{ $score['final_score'] }}</td>
                                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                                @endforeach
                            @else
                                @foreach ($student['scores'] as $index => $score)

                                    <!-- ACADEMIC -->
                                    <td class="text-center">{{ $score['academic'] }}</td>
                                    <td class="text-center">{{ $score['grades_academic'] }}</td>

                                    <!-- ECA 1 -->
                                    <td class="text-center">
                                        @if($score['eca_1'])
                                            {{ $score['eca_1'] }}
                                        @else
                                            <input name="eca_1[]" min="0" max="100" type="number" class="form-control" id="eca_1" value="{{ $score['eca_1'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_1'] }}</td>

                                    <!-- ECA 2 -->  
                                    <td class="text-center">
                                        @if($score['eca_2'])
                                            {{ $score['eca_2'] }}
                                        @else
                                            <input name="eca_2[]"  min="0" max="100" type="number" class="form-control" id="eca_2" value="{{ $score['eca_2'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_2'] }}</td>

                                    <!-- Self-Development -->
                                    <td class="text-center">
                                        @if(isset($score['self_development']))
                                            {{ $score['self_development'] }}
                                        @else
                                            <input name="self_development[]" min="0" max="100" type="number" class="form-control" id="self_development" value="{{ $score['self_development'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>

                                    <!-- ECA Aver -->
                                    <td class="text-center">
                                        @if(isset($score['eca_aver']))
                                            {{ $score['eca_aver'] }}
                                        @else
                                            <input name="eca_aver[]" min="0" max="100" type="number" class="form-control" id="eca_aver" value="{{ $score['eca_aver'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                                    <td class="text-center">
                                        @if(isset($score['behavior']))
                                            {{ $score['behavior'] }}
                                        @else
                                            <input name="behavior[]" min="0" max="100" type="number" class="form-control" id="behavior" value="{{ $score['behavior'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>

                                    <!-- Attendance -->
                                    <td class="text-center">{{ $score['attendance'] }}</td>
                                    <td class="text-center">{{ $score['grades_attendance'] }}</td>

                                    <!-- Participation -->
                                    <td class="text-center">
                                        @if(isset($score['participation']))
                                            {{ $score['participation'] }}
                                        @else
                                            <input name="participation[]"  min="0" max="100" type="number" class="form-control" id="participation"value="{{ $score['participation'] ?: '' }}" autocomplete="off" required></td>
                                        @endif
                                    <td class="text-center">{{ $score['grades_participation'] }}</td>

                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">

                                    <td class="text-center">{{ $score['final_score'] }}</td>
                                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                                @endforeach
                            @endif

                            <td class="text-center">{{ $student['ranking'] }}</td>
                        @endforeach
                        </tr>
                        <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                        <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">    
                        <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">    
                    @else
                        <p>Data Kosong</p>
                    @endif
                    </tbody>

            @elseif ($data['status']->status != null && $data['status']->status == 1)       
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Academic</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 1</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 2</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Self-Development</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA Aver</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Behavior</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Attendance</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Participation</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Total</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grades</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Rank</th>
                    </tr>

                    <tr>
                        <!-- Major Subjects -->
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <!-- END MAJOR SUBJECTS -->
                        
                        <!-- MINOR SUBJECTS -->
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <!-- END MINOR SUBJECTS -->
                        
                        <!-- SUPPLEMENTARY SUBJECTS -->
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <!-- END SUPPLEMENTARY SUBJECTS -->
                    </tr>
                </thead>

                <tbody>
                @if (!empty($data['students']))
                    @foreach ($data['students'] as $student)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student['student_name'] }}</td>

                        @foreach ($student['scores'] as $index => $score)

                            <!-- ACADEMIC -->
                            <td class="text-center">{{ $score['academic'] }}</td>
                            <td class="text-center">{{ $score['grades_academic'] }}</td>

                            <!-- ECA 1 -->
                            <td class="text-center">
                                @if($score['eca_1'])
                                    {{ $score['eca_1'] }}
                                @else
                                    <input name="eca_1[]" min="0" max="100" type="number" class="form-control" id="eca_1" value="{{ $score['eca_1'] ?: '' }}" autocomplete="off" required>
                                @endif
                            </td>
                            <td class="text-center">{{ $score['grades_eca_1'] }}</td>
                            
                            <!-- ECA 2 -->  
                            <td class="text-center">
                                @if($score['eca_2'])
                                    {{ $score['eca_2'] }}
                                @else
                                    <input name="eca_2[]"  min="0" max="100" type="number" class="form-control" id="eca_2" value="{{ $score['eca_2'] ?: '' }}" autocomplete="off" required>
                                @endif
                            </td>
                            <td class="text-center">{{ $score['grades_eca_2'] }}</td>

                            <!-- Self-Development -->
                            <td class="text-center">
                                @if(isset($score['self_development']))
                                    {{ $score['self_development'] }}
                                @else
                                    <input name="self_development[]" min="0" max="100" type="number" class="form-control" id="self_development" value="{{ $score['self_development'] ?: '' }}" autocomplete="off" required>
                                @endif
                            </td>
                            <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
                            
                            <!-- ECA Aver -->
                            <td class="text-center">
                                @if(isset($score['eca_aver']))
                                    {{ $score['eca_aver'] }}
                                @else
                                    <input name="eca_aver[]" min="0" max="100" type="number" class="form-control" id="eca_aver" value="{{ $score['eca_aver'] ?: '' }}" autocomplete="off" required>
                                @endif
                            </td>
                            <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                            <td class="text-center">
                                @if(isset($score['behavior']))
                                    {{ $score['behavior'] }}
                                @else
                                    <input name="behavior[]" min="0" max="100" type="number" class="form-control" id="behavior" value="{{ $score['behavior'] ?: '' }}" autocomplete="off" required>
                                @endif
                            </td>
                            <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>

                            <!-- Attendance -->
                            <td class="text-center">{{ $score['attendance'] }}</td>
                            <td class="text-center">{{ $score['grades_attendance'] }}</td>

                            <!-- Participation -->
                            <td class="text-center">
                                @if(isset($score['participation']))
                                    {{ $score['participation'] }}
                                @else
                                    <input name="participation[]"  min="0" max="100" type="number" class="form-control" id="participation"value="{{ $score['participation'] ?: '' }}" autocomplete="off" required></td>
                                @endif
                            <td class="text-center">{{ $score['grades_participation'] }}</td>
                            

                            <td class="text-center">{{ $score['final_score'] }}</td>
                            <td class="text-center">{{ $score['grades_final_score'] }}</td>
                        @endforeach

                        <td class="text-center">{{ $student['ranking'] }}</td>
                    @endforeach
                    </tr>
                    <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                    <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">    
                    <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">    
                @else
                    <p>Data Kosong</p>
                @endif
                </tbody>
            @endif
        </table>

        <!-- Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Submit Score</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to submit score sooa?
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                        <a class="btn btn-succes btn" id="confirmSooaScoring">Yes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decline -->
        <div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Decline Report Card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} Semester 1</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">Are you sure want to decline report card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} semester 1?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a class="btn btn-danger btn" id="confirmDecline">Yes decline</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('input', function(event) {
            let value = parseInt(input.value, 10);
            if (value < 0 || value > 100) {
                input.value = '';
                alert('Please enter a number between 0 and 100.');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = @json($data['grade']->grade_id);
            var teacherId = @json($data['classTeacher']->teacher_id);
            var semester = @json($data['semester']);

            var confirmDecline = document.getElementById('confirmDecline');
            confirmDecline.href = "{{ url('/' . session('role') . '/reports/sooa/decline') }}/" + id + "/" + teacherId + "/" + semester;
        });
    });
</script>

@if(session('after_post_sooa'))
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
            title: 'Successfully post sooa in the database.',
        });
    }, 1500);
</script>
@endif

@if(session('after_decline_sooa'))
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
            title: 'Successfully decline SOOA.',
        });
    }, 1500);
</script>
@endif

@endsection
