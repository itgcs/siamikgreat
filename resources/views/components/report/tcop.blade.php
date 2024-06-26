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
            <p class="text-xs text-bold">The Certificate of Promotion</p>
            <p class="text-xs">Class Teacher : {{ $data['grade']->teacher_name }}</p>
            <p class="text-xs">Class: {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} </p>
            <p class="text-xs">Date  : {{date('d-m-Y')}}</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form id="confirmForm"  method="POST" action={{route('actionPostScoringSooaPrimary')}}>
        @elseif (session('role') == 'admin')
            <form id="confirmForm" method="POST" action={{route('actionAdminPostScoringSooaPrimary')}}>
        @endif
        @csrf
        
        <div class="row my-2">
            <div class="input-group-append mx-2">
                <button type="submit" class="btn btn-success">Acc TCOP</button>
            </div>
        </div>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Sem 1</th>
                    <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Sem 2</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Average</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Marks</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grade</th>
                    <th colspan="1" class="text-center" style="vertical-align : middle;text-align:center;">Promotion</th>
                </tr>

                <tr>
                    <!-- Major Subjects -->
                    <td class="text-center">SM</td>
                    <td class="text-center">SG</td>
                    <td class="text-center">SM</td>
                    <td class="text-center">SG</td>
                    <td class="text-center">(T/F)</td>
                    <!-- END MAJOR SUBJECTS -->
                </tr>
            </thead>

            <tbody>
            @if (!empty($data['students']))

                @foreach ($data['students'] as $student)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student['student_name'] }}</td>

                    @foreach ($student['scores'] as $index => $score)

                    <!-- Semester 1 -->
                    <td class="text-center">{{ $score['academic'] }}</td>
                    <td class="text-center">{{ $score['grades_academic'] }}</td>

                    <!-- Ssemester 2 -->
                    <td class="text-center">{{ $score['academic'] }}</td>
                    <td class="text-center">{{ $score['grades_academic'] }}</td>

                    @endforeach

                    <td class="text-center">{{ $score['final_score'] }}</td>
                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                    <td class="text-center">1</td>
                    <td class="text-center">(T/F)</td>
                </tr>
                    
                @endforeach
            @else
                <p>Data Kosong</p>
            @endif
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="submitScore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <a class="btn btn-succes btn" href="{{url('/' . session('role') .'/reports') . '/updateSooaPrimary/' . $data['grade']->grade_id}}">Yes</a>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
