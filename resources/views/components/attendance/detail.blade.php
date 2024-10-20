@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    @if (session('role') == 'superadmin')
                        <li class="breadcrumb-item"><a href="{{url('/superadmin/attendances')}}">Attendances</a></li>
                    @elseif (session('role') == 'admin')
                        <li class="breadcrumb-item"><a href="{{url('/admin/attendances')}}">Attendances</a></li>
                    @elseif (session('role') == 'teacher')
                        <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/attendance/class/teacher')}}">Attendances</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Detail Attendances</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <p class="text-xs text-bold">Detail Attendance</p>
            <p class="text-xs">Semester : {{ $data['semester']}}</p> 
            <p class="text-xs">Class : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</p>
            <p class="text-xs">Class Teacher : {{ $data['classTeacher']->teacher_name }}</p>
            <p class="text-xs">Date : {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            @if (session('role') == 'superadmin')
                    <form id="confirmForm" method="POST" action={{route('actionPostScoringAttendance')}}>
                @elseif (session('role') == 'admin')
                    <form id="confirmForm" method="POST" action={{route('actionAdminPostScoringAttendance')}}>
                @elseif (session('role') == 'teacher')
                    <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringAttendance')}}>
                @endif
            @csrf

            @if ($data['status'] == null)
                <div class="row my-2">
                    <div class="input-group-append mx-2">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Submit Score Attendance</button>
                    </div>
                </div>
            @elseif ($data['status']->status != null && $data['status']->status == 1)       
                <div class="row my-2">
                    <div class="input-group-append mx-2">
                        <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                    </div>
                </div>  
            @endif

            <table class="table table-striped table-bordered bg-white" style="min-width: 100%;">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">S/N</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">First Name</th>
                        <th colspan="6" class="text-center" style="vertical-align: middle;">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">P</th>
                        <th class="text-center" style="vertical-align: middle;">A</th>
                        <th class="text-center" style="vertical-align: middle;">S</th>
                        <th class="text-center" style="vertical-align: middle;">L</th>
                        <th class="text-center" style="vertical-align: middle;">PE</th>
                        <th class="text-center" style="vertical-align: middle;">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($data['students']))
                        @foreach ($data['students'] as $student)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $student['student_name'] }}</td>
                
                                <td>{{ $student['total_present'] }}</td>
                                <td>{{ $student['total_alpha'] }}</td>
                                <td>{{ $student['total_sick'] }}</td>
                                <td>{{ $student['total_late'] }}</td>
                                <td>{{ $student['total_pe'] }}</td>
                                <td>
                                    {{ $student['score'] }}
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">
                                    <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $student['score'] }}">
                                </td>
    
                            </tr>
                        @endforeach
                    @else
                        <p class="text-center">No data available</p>
                    @endif
                </tbody>
            </table>
            <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
            <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
            <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">  
            </form>
        </div>
    </div>

    @foreach ($data['totalAttendances']['datesByMonth'] as $month => $dates)
        <div class="row">
            <div class="col">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4 class="card-title">{{ $month }}</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center" style="vertical-align: middle;width:50px">S/N</th>
                                        <th rowspan="2" class="text-center" style="vertical-align: middle;min-width:450px;">First Name</th>
                                        @foreach($dates as $date)
                                            <th class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</th>
                                        @endforeach
                                        <!-- <th colspan="5" class="text-center" style="vertical-align: middle;">Total</th> -->
                                    </tr>
                                    <tr>
                                        @foreach($dates as $date)
                                            <th class="text-center text-xs" style="vertical-align: middle;">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                                            </th>
                                        @endforeach
                                        <!-- <th class="text-center" style="vertical-align: middle;">P</th>
                                        <th class="text-center" style="vertical-align: middle;">A</th>
                                        <th class="text-center" style="vertical-align: middle;">S</th>
                                        <th class="text-center" style="vertical-align: middle;">L</th>
                                        <th class="text-center" style="vertical-align: middle;">PE</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($data['students']))
                                        @foreach ($data['students'] as $student)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $student['student_name'] }}</td>
                                                @foreach($dates as $date)
                                                    @php
                                                        $attendance = $student['attendances']->firstWhere('attendances_date', $date);
                                                    @endphp
                                                    <td>
                                                        @if($attendance)
                                                            @if($attendance['attendances_present'])
                                                                P
                                                            @else
                                                                @if($attendance['attendances_alpha'])
                                                                    A 
                                                                @elseif($attendance['attendances_sick'])
                                                                    S <small>({{ $attendance['attendances_information'] }})</small>
                                                                @elseif($attendance['attendances_permission'])
                                                                    Pe <small>({{ $attendance['attendances_information'] }})</small>
                                                                @elseif($attendance['attendances_late'])
                                                                    L <small>({{ $attendance['attendances_latest'] }} minute)</small>
                                                                @endif
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <!-- <td>{{ $student['total_present'] }}</td>
                                                <td>{{ $student['total_alpha'] }}</td>
                                                <td>{{ $student['total_sick'] }}</td>
                                                <td>{{ $student['total_pe'] }}</td> -->
                                            </tr>
                                        @endforeach
                                    @else
                                        <p class="text-center">No data available</p>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Score Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit score attendance?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Submit</button>
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

@if(session('after_post_attendance_score'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post attendance score report in the database.',
        });
    </script>
@endif

@if(session('success_attend'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully attendance student',
        });
    </script>
@endif



@endsection
