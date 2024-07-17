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
            <p class="text-xs text-bold">Report Card Kindergarten Semester {{ $data['semester'] }}</p>
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
            <form id="confirmForm" method="POST" action={{route('actionTeacherPostReportCardKindergarten')}}>
        @endif
        @csrf
        
        @if ($data['status'] == null)
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Acc Report Card Kindergarten</button>
                </div>
            </div>
        @elseif ($data['status']->status != null && $data['status']->status == 1)       
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <a  class="btn btn-success">Already Submit in {{ $data['status']->created_at }}</a>
                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline Report Card Kindergarten</a>
                    @endif
                </div>
            </div>  
        @endif

        @if (!empty($data['students']))
        
        <table class="table table-striped table-bordered bg-white" style=" width: 1800px;">
            @if ($data['status'] == null)
                <!-- JIKA DATA BELUM DI SUBMIT OLEH TEACHER  -->
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">S/N</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">First Name</th>
                        @if (session('semester') == 1)
                        <th class="text-left" style="vertical-align : middle;text-align:center;" colspan="8">Grades :  A+ >95-99; A >85-94; B >75-84; C >65-74; D >45-64</th>
                        @elseif (session('semester') == 2)
                        <th class="text-left" style="vertical-align : middle;text-align:center;" colspan="7">Grades :  A+ >95-99; A >85-94; B >75-84; C >65-74; D >45-64</th>
                        @endif
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Conduct</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Remarks</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">English</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Mathematics</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Chinese</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Science</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Character Building</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Art & Craft</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">IT</th>
                        @if (session('semester') == 1)
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Phonic</th>
                        @endif
                    </tr>
                </thead>

                <!-- JIKA TEACHER MEMINTA EDIT SETELAH SUBMIT -->
                @if(!empty($data['result']))
                    <tbody>
                        @foreach ($data['result'] as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student['student_name'] }}</td>
                            @foreach ($student['scores'] as $index => $score)
                                @foreach (['english', 'mathematics', 'chinese', 'science', 'character_building', 'art_and_craft', 'it', 'phonic'] as $field)
                                    <td class="text-center">
                                        {{ $score[$field] }}
                                    </td>
                                @endforeach
                            @endforeach

                            <td>
                                <div class="form-check me-2 mx-2">
                                    <input id="conduct_excellent_{{ $student['student_id'] }}" name="conduct[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="Excellent">
                                    <label class="form-check-label" for="conduct_excellent_{{ $student['student_id'] }}">
                                        Excellent
                                    </label>
                                </div>
                                <div class="form-check me-2 mx-2">
                                    <input id="conduct_good_{{ $student['student_id'] }}" name="conduct[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="Good">
                                    <label class="form-check-label" for="conduct_good_{{ $student['student_id'] }}">
                                        Good
                                    </label>
                                </div>
                                <div class="form-check me-2 mx-2">
                                    <input id="conduct_weak_{{ $student['student_id'] }}" name="conduct[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="Weak">
                                    <label class="form-check-label" for="conduct_weak_{{ $student['student_id'] }}">
                                        Weak
                                    </label>
                                </div>
                            </td>

            

                            <td class="text-center">
                                <input name="remarks[{{ $student['student_id'] }}]" type="text" class="form-control" autocomplete="off" value="{{ $score['remarks'] }}">
                            </td>
                            <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">
                        </tr>
                        @endforeach
                    </tbody>
                    <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                    <input name="teacher_id" type="number" class="form-control d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                    <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">
                
                <!-- JIKA TEACHER BELUM INPUT NILAI -->
                @else 
                <tbody>
                    @foreach ($data['students'] as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student['name'] }}</td>

                        @foreach (['english', 'mathematics', 'chinese', 'science', 'character_building', 'art_and_craft', 'phonic','it'] as $field)
                            <td class="text-center">
                                {{ $score[$field] }}
                            </td>
                        @endforeach
                        <td>
                            <div class="form-check me-2 mx-2">
                                <input id="conduct_excellent_{{ $student['student_id'] }}" name="conduct[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="Excellent">
                                <label class="form-check-label" for="conduct_excellent_{{ $student['student_id'] }}">
                                    Excellent
                                </label>
                            </div>
                            <div class="form-check me-2 mx-2">
                                <input id="conduct_good_{{ $student['student_id'] }}" name="conduct[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="Good">
                                <label class="form-check-label" for="conduct_good_{{ $student['student_id'] }}">
                                    Good
                                </label>
                            </div>
                            <div class="form-check me-2 mx-2">
                                <input id="conduct_weak_{{ $student['student_id'] }}" name="conduct[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="Weak">
                                <label class="form-check-label" for="conduct_weak_{{ $student['student_id'] }}">
                                    Weak
                                </label>
                            </div>
                        </td>

                        <td class="text-center">
                            <input name="remarks[{{ $student['student_id'] }}]" type="text" class="form-control" autocomplete="off">
                        </td>
                        <input name="student_id[]" type="number" class="form-control d-none" value="{{ $student['student_id'] }}">
                    </tr>
                    @endforeach
                </tbody>
                <input name="grade_id" type="number" class="form-control d-none" value="{{ $data['grade']->grade_id }}">
                <input name="teacher_id" type="number" class="form-control d-none" value="{{ $data['classTeacher']->teacher_id }}">
                <input name="semester" type="number" class="form-control d-none" value="{{ $data['semester'] }}">

                @endif


            <!-- JIKA DATA SUDAH DI SUBMiT OLEH TEACHER -->
            @elseif ($data['status']->status != null && $data['status']->status == 1)
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">S/N</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">First Name</th>
                        @if (session('semester') == 1)
                        <th class="text-left" style="vertical-align : middle;text-align:center;" colspan="8">Grades :  A+ >95-99; A >85-94; B >75-84; C >65-74; D >45-64</th>
                        @elseif (session('semester') == 2)
                        <th class="text-left" style="vertical-align : middle;text-align:center;" colspan="7">Grades :  A+ >95-99; A >85-94; B >75-84; C >65-74; D >45-64</th>
                        @endif
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Conduct</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Remarks</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">English</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Mathematics</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Chinese</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Science</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Character Building</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Art & Craft</th>
                        <th class="text-center" style="vertical-align : middle;text-align:center;">IT</th>
                        @if (session('semester') == 1)
                        <th class="text-center" style="vertical-align : middle;text-align:center;">Phonic</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                @if(!empty($data['result']))
                    @foreach ($data['result'] as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student['student_name'] }}</td>
                            @foreach ($student['scores'] as $index => $score)
                                @php
                                    $fields = session('semester') == 1
                                        ? ['english', 'mathematics', 'chinese', 'science', 'character_building', 'art_and_craft', 'it', 'phonic']
                                        : ['english', 'mathematics', 'chinese', 'science', 'character_building', 'art_and_craft', 'it'];
                                @endphp
                                @foreach ($fields as $field)
                                    <td class="text-center">
                                        {{ $score[$field] }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ $score['conduct'] }}
                                </td>

                                <td class="text-justify">
                                   {{ $score['remarks'] }}
                                </td>

                                @if ($data['status'] !== null)
                                <td>
                                    <a class="btn btn-primary btn"
                                        href="{{url('teacher/dashboard/report/kindergarten/print') . '/' . $student['student_id']}}">
                                        Print
                                    </a>
                                </td>
                                @endif
                            @endforeach
                    @endforeach
                @endif        
                </tbody>
            @endif

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
                        Are you sure you want to submit report card toddler?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Acc</button>
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
    document.getElementById('confirmAccScoring').addEventListener('click', function() {
        // Mengambil semua input komentar
        var comments = document.querySelectorAll('input[name="remarks[]"]');
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
        
        // Mengambil semua baris siswa
        var studentRows = document.querySelectorAll('tbody tr');
        
        // Memeriksa setiap baris siswa apakah semua radio button tercentang
        studentRows.forEach(function(row) {
            var radiosInRow = row.querySelectorAll('.status-type');
            var groups = {};
            var rowFilled = true;

            radiosInRow.forEach(function(radio) {
                var name = radio.name;
                if (!groups[name]) {
                    groups[name] = false;
                }
                if (radio.checked) {
                    groups[name] = true;
                }
            });

            for (var key in groups) {
                if (!groups[key]) {
                    rowFilled = false;
                    break;
                }
            }

            if (!rowFilled) {
                allFilled = false;
                // Menambahkan kelas untuk memberikan highlight pada input yang belum tercentang
                row.classList.add('is-invalid');
            } else {
                // Menghapus kelas jika semua input tercentang
                row.classList.remove('is-invalid');
            }
        });
        
        // Jika semua komentar dan radio button terisi, submit form
        if (allFilled) {
            document.getElementById('confirmForm').submit();
        } else {
            // Menampilkan pesan peringatan
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'All comments and scores must be filled before submitting the form!',
            });
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = @json($data['grade']->grade_id);
            var teacherId = @json($data['classTeacher']->teacher_id);
            var semester = @json($data['semester']);

            console.log("id=", id, "teacher=", teacherId, "semester=", semester);
            var confirmDecline = document.getElementById('confirmDecline');
            confirmDecline.href = "{{ url('/' . session('role') . '/reports/reportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
        });
    });
</script>

@if(session('after_post_report_card_kindergarten'))
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
            title: `Successfully post report card nursery semester ${semester} in the database.`,
        });
    }, 1500);
</script>
@endif

@if(session('after_decline_report_card'))
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
            title: 'Successfully decline report card toddler.',
        });
    }, 1500);
</script>
@endif


@endsection
