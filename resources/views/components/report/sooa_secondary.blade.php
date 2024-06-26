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
            <li class="breadcrumb-item active" aria-current="page">Detail Acar</li>
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
            <form method="POST" action={{route('actionAdminPostScoringAcar')}}>
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringSooaSecondary')}}>
        @endif
        @csrf

         @if ($data['status'] == null)
            <div class="row my-2">
            <div class="input-group-append mx-2">
                <button type="submit" class="btn btn-success">Submit SOOA</button>
            </div>
        </div>
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                </div>
            </div>  
        @endif

        <table class="table table-striped table-bordered bg-white" style="width: 2000px">
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
                                <input name="self_development[]" min="0" max="100" type="number" class="form-control" id="self_development" value="{{ old('self_development', '') }}" autocomplete="off" required>
                            @endif
                        </td>
                        <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
                        
                        <!-- ECA Aver -->
                        <td class="text-center">
                            @if(isset($score['eca_aver']))
                                {{ $score['eca_aver'] }}
                            @else
                                <input name="eca_aver[]" min="0" max="100" type="number" class="form-control" id="eca_aver" value="{{ old('eca_aver', '') }}" autocomplete="off" required>
                            @endif
                        </td>
                        <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                        <td class="text-center">
                            @if(isset($score['behavior']))
                                {{ $score['behavior'] }}
                            @else
                                <input name="behavior[]" min="0" max="100" type="number" class="form-control" id="behavior" value="{{ old('behavior', '') }}" autocomplete="off" required>
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
        </table>

    </div>
</div>

@endsection
