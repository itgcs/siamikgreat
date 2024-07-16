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
        <p class="text-xs">Semester : {{ $data['semester']}}</p> 
            <p class="text-xs">Subject Teacher : {{ $data['subjectTeacher']->teacher_name }}</p>    
            <p class="text-xs">Class Teacher : {{ $data['classTeacher']->teacher_name }}</p>
            <p class="text-xs">Class : {{ $data['grade']->name }} - {{ $data['grade']->class }}</p>
            <p class="text-xs">Date  : {{date('d-m-Y')}}</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        @if (session('role') == 'superadmin')
            <form id="confirmForm" method="POST" action={{route('actionPostScoringSecondary')}}>
        @elseif (session('role') == 'admin')
            <form id="confirmForm" method="POST" action={{route('actionAdminCreateExam')}}>
        @elseif (session('role') == 'teacher')
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringSecondary')}}>
        @endif
        @csrf
        @if ($data['status'] == null)
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Acc Scoring</button>
                </div>
            </div>
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline ACAR</a>
                    @endif
                </div>
            </div>  
        @endif
        <table class="table table-striped table-bordered" style=" width: 1400px;">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th rowspan="2 class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th colspan="{{ $data['grade']->total_tasks + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;"> Tasks (Homework/Small Project/Presentation)</th>
                    <th colspan="{{ $data['grade']->total_mid + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Quiz/Practical Exam/Project</th>
                    <th colspan="{{ $data['grade']->total_final_exam + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Final Exam (Written Tes/Big Project)</th>
                    <th class="text-center">Total</th>
                    <th rowspan="2" class="text-center" style="width: 25%;" style="vertical-align : middle;text-align:center;">Comment</th>
                </tr>
                <tr>
                    <!-- TASKS -->
                    @for ($i=1; $i <= $data['grade']->total_tasks; $i++)
                        <td class="text-center">{{ $i }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td class="text-center">25%</td>
                    <!-- END TASKS -->

                    <!-- QUIZ -->
                    @for ($j=1; $j <= $data['grade']->total_mid; $j++)
                        <td class="text-center">{{ $j }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td class="text-center">35%</td>
                    <!-- END QUIZ -->

                    <!-- FINAL EXAM -->
                    @for ($j=1; $j <= $data['grade']->total_final_exam; $j++)
                        <td class="text-center">{{ $j }}</td>
                    @endfor
                    <td class="text-center">Avg</td>
                    <td class="text-center">40%</td>
                    <!-- END FINAL EXAM -->

                    <td class="text-center">100%</td>
                </tr>
            </thead>

            <tbody>
            @if (!empty($data['students']))

                @foreach ($data['students'] as $student)
                    
                    <tr>
                        <td>{{ $loop->iteration }}</td>  <!-- nomer -->
                        <td>{{ $student['student_name'] }}</td> <!-- name -->
                
                        <!-- COUNT TASKS -->
                        @foreach ($student['scores'] as $index => $score)
                            @if(in_array($score['type_exam'], $data['tasks']))
                                <td class="text-center">{{ $score['score'] }}</td>
                            @endif
                        @endforeach

                        <td>{{ $student['avg_tasks'] }} </td>
                        <td>{{ $student['percent_tasks'] }} </td>
                        <!-- END TASKS -->


                        <!-- COUNT QUIZ -->
                        @foreach ($student['scores'] as $index => $score)
                            @if(in_array($score['type_exam'], $data['mid']))
                                <td class="text-center">{{ $score['score'] }}</td>
                            @endif
                        @endforeach

                        <td class="text-center">{{ $student['avg_mid'] }}</td> <!-- nilai rata-rata exercise -->
                        <td class="text-center">{{ $student['percent_mid'] }}</td> <!-- 15% dari nilai rata-rata exercise -->
                        <!-- END COUNT QUIZ -->


                        <!-- COUNT F.EXAM -->
                        @php $foundFinalExam = false; @endphp
                        @foreach ($student['scores'] as $score)
                            @if(in_array($score['type_exam'], $data['finalExam']))
                                <td class="text-center">{{ $score['score'] }}</td>
                                @php $foundFinalExam = true; @endphp
                            @endif
                        @endforeach
                        <td>{{ $student['avg_fe'] ?? '&nbsp;' }}</td>
                        <td>{{ $student['percent_fe'] ?? '&nbsp;' }}</td>
                        <!-- END COUNT F.EXAM -->
                        

                        <!-- FINAL SCORE -->
                        <td>{{ $student['total_score'] }}</td>

                        <!-- COMMENT -->
                        <td class="project-actions text-left">
                            @if ($data['status'] == null)
                                <div class="input-group">
                                    <input name="comment[]" type="text" class="form-control" id="comment" placeholder="{{ $student['comment'] ? '' : 'Write your comment' }}" value="{{ $student['comment'] ?: '' }}" autocomplete="off" required>
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">  
                                    <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $student['total_score'] }}">  
                                    <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
                                    <div class="input-group-append">
                                        <a class="btn btn-danger btn" data-toggle="modal" data-target="#editSingleComment">
                                            <i class="fas fa-pen"></i>
                                            Edit
                                        </a>
                                    </div>    
                                </div>
                            @elseif ($data['status'] != null && $data['status']->status == 1)       
                                {{ $student['comment'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach

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

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


@if(session('after_post_final_score')) 

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
              title: 'Successfully post final score major subject in the database.',
        });
        }, 1500);


      </script>

  @endif

@endsection
