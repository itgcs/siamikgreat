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
            @elseif (session('role') == 'teacher')
            <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/report/subject/teacher')}}">Reports</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail Report {{ $data['subject']->subject_name }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
        <div class="col">
            <p class="text-xs">Semester : {{ $data['semester']}}</p> 
            <p class="text-xs">Subject Teacher : {{ $data['subjectTeacher']->teacher_name }}</p>    
            <p class="text-xs">Class Teacher : {{ $data['classTeacher']->teacher_name }}</p>
            <p class="text-xs">Class : {{ $data['grade']->name }} - {{ $data['grade']->class }}</p>
            <p class="text-xs">Date  : {{date('d-m-Y')}}</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form id="confirmForm" method="POST" action={{route('actionPostScoringKindergarten')}}>
        @elseif (session('role') == 'admin')
            <form id="confirmForm" method="POST" action={{route('actionAdminScoringKindergarten')}}>
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringKindergarten')}}>
        @endif
        @csrf
        @if ($data['status'] == null)
            <div class="row mx-2">
                <div class="input-group-append my-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Acc Scoring</button>
                </div>
            </div>
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline Scoring</a>
                    @endif
                </div>
            </div>  
        @endif
        <table class="table table-striped table-bordered bg-white" style=" width: 1400px;">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Name</th>
                    <th colspan="{{ $data['grade']->total_exercise }}" class="text-center" style="vertical-align : middle;text-align:center;"> Exercise</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Avg (40%)</th>
                    <th colspan="{{ $data['grade']->total_quiz }}" class="text-center" style="vertical-align : middle;text-align:center;">Quiz</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Avg (40%)</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Participation <br>Daily Performance</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Participation (30%)</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Total (100%)</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Round <br> Mark</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grade</th>
                </tr>
                <tr>
                    <!-- EXERCISE -->
                    @if ($data['grade']->total_exercise !== 0)
                        @for ($i=1; $i <= $data['grade']->total_exercise; $i++)
                            <td class="text-center">{{ $i }}</td>
                        @endfor
                    @else
                    <td>&nbsp;</td>
                    @endif
                    <!-- END EXERCISE -->

                    <!-- QUIZ -->
                    @if ($data['grade']->total_quiz !== 0)
                        @for ($j=1; $j <= $data['grade']->total_quiz; $j++)
                            <td class="text-center">{{ $j }}</td>
                        @endfor
                    @else
                    <td>&nbsp;</td>
                    @endif
                    <!-- END QUIZ -->
                </tr>
            </thead>

            <tbody>
            @if (!empty($data['students']))

                @foreach ($data['students'] as $student)
                    
                    <tr>
                        <td>{{ $loop->iteration }}</td>  <!-- nomer -->
                        <td>{{ $student['student_name'] }}</td> <!-- name -->
                
                        <!-- EXERCISE -->
                        @php $foundExercise = false; @endphp
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['exercise'])
                                <td class="text-center">{{ $score['score'] }}</td>
                                @php $foundExercise = true; @endphp
                            @endif
                        @endforeach
                        @if(!$foundExercise)
                            <td>&nbsp;</td>
                        @endif

                        <td>{{ $student['percent_exercise'] }} </td>
                        <!-- END EXERCISE -->


                        <!-- COUNT QUIZ -->
                        @php $foundQuiz = false; @endphp
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['quiz'])
                                <td class="text-center">{{ $score['score'] }}</td>
                                @php $foundQuiz = true; @endphp
                            @endif
                        @endforeach
                        @if(!$foundQuiz)
                            <td>&nbsp;</td>
                        @endif

                        <td class="text-center">{{ $student['percent_quiz'] }}</td>
                        <!-- END COUNT QUIZ -->


                        <!-- COUNT PARTICIPATION -->
                        @php $foundParticipation = false; @endphp
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['participation'])
                                <td class="text-center">{{ $score['score'] }}</td>
                                @php $foundParticipation = true; @endphp
                            @endif
                        @endforeach
                        @if(!$foundParticipation)
                            <td>&nbsp;</td>
                        @endif
                        <td class="text-center">{{ $student['percent_participation'] }}</td> 
                        <!-- END COUNT PARTICIAPTION -->
                        

                        <!-- FINAL SCORE -->
                        <td>{{ $student['total_score'] }}</td>
                        <td>{{ $student['total_score_mark'] }}</td>
                        <td>{{ $student['grade'] }}</td>
                        <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">  
                        <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $student['total_score'] }}">          
                    </tr>
                @endforeach
                    <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
                    <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->id }}">  
                    <input name="subject_id" type="number" class="form-control d-none" id="subject_id" value="{{ $data['subject']->subject_id }}">  
                    <input name="subject_teacher" type="number" class="form-control d-none" id="subject_teacher" value="{{ $data['subjectTeacher']->teacher_id }}">  
                </form>
            @else 
                <p>data kosong</p>
            @endif
                
            </tbody>
        </table>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Acc Scoring</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to acc scoring?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Acc Scoring</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editSingleComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Are you sure want to delete subject?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
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


@if(session('after_post_final_score')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post final score kindergarten subject in the database.',
        });
    </script>
@endif

@endsection
