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
            <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/report/subject/teacher')}}">Reports </a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail Report {{ $data['subject']->subject_name }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
        <div class="col">
            <p class="text-bold">Minor Subject Assessment</p>
            <table>
                <tr>
                    <td>Subject</td>
                    <td> : {{ $data['subject']->subject_name }}</td>
                </tr>
                <tr>
                    <td>Subject Teacher</td>
                    <td> : {{ $data['subjectTeacher']->teacher_name }}</td>
                </tr>
                <tr>
                    <td>Class</td>
                    <td> : {{ $data['grade']->name }} - {{ $data['grade']->class }}</td>
                </tr>
                <tr>
                    <td>Class Teacher</td>
                    <td> : {{ $data['classTeacher']->teacher_name }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td> : {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form id="confirmForm" method="POST" action={{route('actionPostScoringMinorPrimary')}}>
        @elseif (session('role') == 'admin')
            <form id="confirmForm" method="POST" action={{route('actionAdminCreateExam')}}>
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringMinorPrimary')}}>
        @endif

        @csrf

        @if ($data['status'] == null)
            @if (!empty($data['students']))
                <div class="row my-2">
                    <div class="input-group-append mx-2">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Acc Comment</button>
                    </div>
                </div>
            @endif
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}</a>
                    @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                    <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline Comment</a>
                    @endif
                </div>
            </div>  
        @endif
        
        <table class="table table-striped table-bordered bg-white border-black" style=" width: 2000px;">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th rowspan="2 class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th colspan="{{ $data['grade']->total_homework + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Homework (20%)</th>
                    <th colspan="{{ $data['grade']->total_exercise + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Exercise (35%)</th>
                    <th colspan="{{ $data['grade']->total_participation + 1 }} class="text-center" style="vertical-align : middle;text-align:center;">Attendance / Participation (10%)</th>
                    <th colspan="{{ $data['grade']->total_final_exam + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Project/Practical/Final Assessment (35%)</th>
                    <th class="text-center">Total</th>
                    {{-- <th class="text-center">Marks</th> --}}
                    <th rowspan="2" class="text-center" style="width: 25%;vertical-align : middle;text-align:center;">Comment</th>
                </tr>
                <tr>
                    @for ($i=1; $i <= $data['grade']->total_homework; $i++)
                        <td class="text-center">{{ $i }}</td>
                    @endfor
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    @for ($j=1; $j <= $data['grade']->total_exercise; $j++)
                        <td class="text-center">{{ $j }}</td>
                    @endfor
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    @for ($k=1; $k <= $data['grade']->total_participation; $k++)
                        <td class="text-center">{{ $k }}</td>
                    @endfor
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    @for ($l=1; $l <= $data['grade']->total_final_exam; $l++)
                        <td class="text-center">{{ $l }}</td>
                    @endfor
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    <td style="background-color:beige;" class="text-center">100%</td>
                    {{-- <td>&nbsp;</td> --}}
                </tr>
            </thead>

            <tbody>
            @if (!empty($data['students']))

                @foreach ($data['students'] as $student)
                    
                    <tr>
                        <td>{{ $loop->iteration }}</td>  <!-- nomer -->
                        <td>{{ $student['student_name'] }}</td> <!-- name -->
                    

                        <!-- COUNT HOMEWORK -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['homework'])
                                <td class="text-center">{{ $score['score'] }}</td>
                            @endif
                        @endforeach
                        <td style="background-color:beige;" class="text-center">{{ $student['percent_homework'] }} </td>
                        <!-- END HOMEWORK -->


                        <!-- COUNT EXERCISE -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['exercise'])
                                <td class="text-center">{{ $score['score'] }}</td>
                            @endif
                        @endforeach

                        <td style="background-color:beige;" class="text-center">{{ $student['percent_exercise'] }}</td> <!-- nilai rata-rata exercise -->
                        <!-- END COUNT EXERCISE -->


                        <!-- COUNT PARTICIPATION -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['participation'])
                                <td class="text-center">{{ $score['score'] }}</td> 
                            @endif
                        @endforeach

                        <td style="background-color:beige;" class="text-center">{{ $student['percent_participation'] }}</td> <!-- nilai rata-rata participation -->
                        
                        <!-- END COUNT PARTICIPATION -->

                        <!-- COUNT PROJECT / PRACTICAL / FINAL ASESSMENT -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == $data['project'])
                                <td class="text-center">{{ $score['score'] }}</td> 
                            @elseif($score['type_exam'] == $data['practical'])
                                <td class="text-center">{{ $score['score'] }}</td> 
                            @elseif($score['type_exam'] == $data['finalAssessment'])
                                <td class="text-center">{{ $score['score'] }}</td> 
                            @elseif($score['type_exam'] == $data['finalExam'])
                                <td class="text-center">{{ $score['score'] }}</td> 
                            @endif
                        @endforeach
                        <td style="background-color:beige;" class="text-center">{{ $student['percent_fe'] }}</td>
                        <!-- END COUNT PROJECT / PRACTICAL / FINAL ASESSMENT -->
                        

                        <!-- FINAL SCORE -->
                        <td style="background-color:beige;" class="text-center text-bold">{{ $student['total_score'] }}</td>

                        <!-- MARKS -->
                        {{-- <td class="text-center">{{ $student['grades'] }}</td> --}}

                        <!-- COMMENT -->
                        <td class="project-actions text-left">
                            <div class="input-group">
                                <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">  
                                <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $student['total_score'] }}">  
                                <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
                                @if ($data['status'] == null) 
                                <input name="comment[]" type="text" class="form-control" id="comment" placeholder="{{ $student['comment'] ? '' : 'Write your comment' }}" value="{{ $student['comment'] ?: '' }}" autocomplete="off" required>
                                {{-- <div class="input-group-append">
                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#editSingleComment">
                                        <i class="fas fa-pen"></i>
                                        Edit
                                    </a>
                                </div> --}}
                                @else
                                {{ $student['comment'] }}
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

                    <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->id }}">  
                    <input name="subject_id" type="number" class="form-control d-none" id="subject_id" value="{{ $data['subject']->subject_id }}">  
                    <input name="subject_teacher" type="number" class="form-control d-none" id="subject_teacher" value="{{ $data['subjectTeacher']->teacher_id }}">  
                </form>
                @else
                <tr>
                    <td colspan="15" class="text-center">
                        You haven't added a assessment... <br>
                        <a href="/teacher/dashboard/exam/teacher" class="text-red">Create Exam</a>        
                    </td>    
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Acc Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to acc comment?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Acc Comment</button>
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
                    Are you sure want to edit comment?
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
        var comments = document.querySelectorAll('input[name="comment[]"]');
        var allFilled = true;
        
        // Memeriksa setiap komentar apakah kosong atau tidak
        comments.forEach(function(comment) {
            if (comment.value.trim() === '') {
                allFilled = false;
                // Menambahkan kelas untuk memberikan highlight pada input yang kosong
                comment.classList.add('is-invalid');
            } else {
                // Menghapus kelas jika input tidak kosong
                comment.classList.remove('is-invalid');
            }
        });
        
        // Jika semua komentar terisi, submit form
        if (allFilled) {
            document.getElementById('confirmForm').submit();
        } else {
            // Menampilkan pesan peringatan
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'All comments must be filled before submitting the form!',
            });
        }
    });
</script>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Comment {{ $data['grade']->name }} - {{ $data['grade']->class }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline comment {{ $data['grade']->name }} - {{ $data['grade']->class }} ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes decline</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var gradeId = @json($data['grade']->id);
            var teacherId = @json($data['subjectTeacher']->teacher_id);
            var subjectId = @json($data['subject']->subject_id);
            var semester = @json($data['semester']);

            var confirmDecline = document.getElementById('confirmDecline');

            confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/scoring/decline') }}/" + gradeId + "/" + teacherId + "/" + subjectId + "/" + semester;
        });
    });
</script>


<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


@if(session('after_post_final_score')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post comment subject in the database.',
        });
    </script>
@endif

@endsection
