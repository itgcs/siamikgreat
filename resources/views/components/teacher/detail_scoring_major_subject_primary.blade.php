@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid" id="anjayani">
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
            <p class="text-bold">Major Subject Assessment</p>
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
    

    @if ($data['status'] == null)
            @if (!empty($data['students']))
                <div class="row my-2">
                    <div class="input-group-append mx-2">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Submit Comment</button>
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

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form id="confirmForm" method="POST" action={{route('actionPostScoringMajorPrimary')}}>
        @elseif (session('role') == 'admin')
            <form id="confirmForm" method="POST" action={{route('actionAdminCreateExam')}}>
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringMajorPrimary')}}>
        @endif
        @csrf
    
        <table class="table table-striped table-bordered bg-white" style="width:2000px;">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th colspan="{{ $data['grade']->total_homework + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Homework</th>
                    <th colspan="{{ $data['grade']->total_exercise + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Exercise</th>
                    <th colspan="{{ $data['grade']->total_participation + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Participation</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">R (30%)</th>
                    <th colspan="{{ $data['grade']->total_quiz + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Quiz</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">R (30%)</th>
                    <th colspan="1" class="text-center" style="vertical-align : middle;text-align:center;">Final</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">R (40%)</th>
                    <th class="text-center" style="vertical-align : middle;text-align:center;">Total</th>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;width: 20%;">Comment</th>
                </tr>
                <tr>
                    @for ($i=1; $i <= $data['grade']->total_homework; $i++)
                        <td class="text-center">{{ $i }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td style="background-color:beige;" class="text-center">10%</td>
                    @for ($j=1; $j <= $data['grade']->total_exercise; $j++)
                        <td class="text-center">{{ $j }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td style="background-color:beige;" class="text-center">15%</td>
                    @for ($k=1; $k <= $data['grade']->total_participation; $k++)
                        <td class="text-center">{{ $k }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td style="background-color:beige;" class="text-center">5%</td>
                    <td style="background-color:beige;" class="text-center">H+E+P</td>
                    @for ($l=1; $l <= $data['grade']->total_quiz; $l++)
                        <td class="text-center">{{ $l }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td style="background-color:beige;" class="text-center">Quiz</td>
                    <td class="text-center">Exam</td>
                    <td style="background-color:beige;" class="text-center">F.E</td>
                    <td style="background-color:beige;" class="text-center">100%</td>
                </tr>
            </thead>

            <tbody>
            @if (!empty($data['students']))

                @foreach ($data['students'] as $student)
                    
                    <tr>
                        <td  class="text-center" style="vertical-align : middle;text-align:center;">{{ $loop->iteration }}</td>  <!-- nomer -->
                        <td>{{ $student['student_name'] }}</td> <!-- name -->
                    

                        <!-- COUNT HOMEWORK -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == 1)
                                <td class="text-center">{{ $score['score'] }}</td>
                            @endif
                        @endforeach
                        <td>{{ $student['avg_homework'] }} </td>
                        <td style="background-color:beige;">{{ $student['percent_homework'] }} </td>
                        <!-- END HOMEWORK -->


                        <!-- COUNT EXERCISE -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == 2)
                                <td class="text-center">{{ $score['score'] }}</td>
                            @endif
                        @endforeach

                        <td class="text-center">{{ $student['avg_exercise'] }}</td> <!-- nilai rata-rata exercise -->
                        <td style="background-color:beige;" class="text-center">{{ $student['percent_exercise'] }}</td> <!-- 15% dari nilai rata-rata exercise -->
                        <!-- END COUNT EXERCISE -->


                        <!-- COUNT PARTICIPATION -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == 5)
                                <td class="text-center">{{ $score['score'] }}</td> 
                            @endif
                        @endforeach

                        <td class="text-center">{{ $student['avg_participation'] }}</td> <!-- nilai rata-rata exercise -->
                        <td style="background-color:beige;" class="text-center">{{ $student['percent_participation'] }}</td> <!-- 15% dari nilai rata-rata exercise -->
                        
                        <!-- END COUNT PARTICIPATION -->

                        <!-- H+E+P -->
                        <td style="background-color:beige;">{{$student['h+e+p'] }}</td>
                        <!-- END H+E+P -->


                        <!-- COUNT QUIZ -->
                        @foreach ($student['scores'] as $index => $score)
                            @if($score['type_exam'] == 3)
                                <td class="text-center">{{ $score['score'] }}</td> <!-- total jumlah homework -->
                            @endif
                        @endforeach
                        <td>{{ $student['avg_quiz'] }}</td>
                        <td style="background-color:beige;">{{ $student['percent_quiz'] }}</td>
                        <!-- END COUNT QUIZ -->

                        <!-- COUNT F.EXAM -->
                        @php $foundFinalExam = false; @endphp
                        @foreach ($student['scores'] as $score)
                            @if($score['type_exam'] == 4)
                                <td class="text-center">{{ $score['score'] }}</td>
                                @php $foundFinalExam = true; @endphp
                            @endif
                        @endforeach
                        @if(!$foundFinalExam)
                            <td>&nbsp;</td>
                        @endif
                        <td style="background-color:beige;">{{ $student['percent_fe'] ?? '&nbsp;' }}</td>
                        <!-- END COUNT F.EXAM -->
                        

                        <!-- FINAL SCORE -->
                        <td class="text-bold text-center" style="background-color:beige;">{{ $student['total_score'] }}</td>

                        <!-- COMMENT -->
                        <td class="project-actions text-left">
                            <div class="input-group">
                                <input name="student_id[]" type="number" class="form-control d-none" id="student_id-{{$student['student_id']}}" value="{{ $student['student_id'] }}">  
                                <input name="final_score[]" type="number" class="form-control d-none" id="final_score-{{$student['student_id']}}" value="{{ $student['total_score'] }}">  
                                <input name="semester" type="number" class="form-control d-none" id="semester-{{$student['student_id']}}" value="{{ $data['semester'] }}"> 
                                @if ($data['status'] == null) 
                                {{-- <textarea name="comment[]" class="form-control" cols="6" rows="1" id="comment-{{$student['student_id']}}" required></textarea> --}}
                                <input name="comment[]" type="text" class="form-control" id="comment" placeholder="{{ $student['comment'] ? '' : 'Write your comment' }}" value="{{ $student['comment'] ?: '' }}" autocomplete="off" required>
                                <!-- <div class="input-group-append">
                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#editSingleComment">
                                        <i class="fas fa-pen"></i>
                                        Edit
                                    </a>
                                </div> -->
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
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to submit comment?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Submit Comment</button>
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
                        Edit Comment
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
document.addEventListener('DOMContentLoaded', function() {
    const confirmButton = document.getElementById('confirmAccScoring');
    const confirmForm = document.getElementById('confirmForm');
    
    if (confirmButton && confirmForm) {
        confirmButton.addEventListener('click', function() {
            confirmForm.submit();
        });
    }
});

</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var gradeId = @json($data['grade']->id);
            var teacherId = @json($data['subjectTeacher']->teacher_id);
            var subjectId = @json($data['subject']->subject_id);
            var semester = @json($data['semester']);

            // console.log("id=", gradeId, "teacher=", teacherId, "semester=", semester, "subject=", subjectId, academicYear);
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
            text: 'Successfully post final score major subject in the database.',
        });
    </script>
@endif

@endsection
