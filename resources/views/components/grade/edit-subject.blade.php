@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 ">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades')}}">Grade</a></li>
                <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/edit/' . $data[0]['id'])}}">Edit {{ $data[0]['name'] }} - {{ $data[0]['class'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Subject & Teacher {{ $data[0]['name'] }} - {{ $data[0]['class'] }}</li>
            </ol>
        </nav>
        </div>
    </div>

    <div class="row my-3">
    @foreach ($data as $da)
        <a type="button" href="{{ url('/' . session('role') . '/grades/manageSubject/addSubject') . '/' . $da->id }}" class="btn btn-success btn mx-2">   <i class="fa-solid fa-user-plus"></i>
        </i>   
        Add Subject & Teacher
        </a>
    @endforeach
   </div>

    <div class="card card-dark">
        <div class="card-header">
            @foreach ($data as $da)
                <h3 class="card-title">Subject Teacher {{$da->name}} - {{ $da->class }}</h3>
            @endforeach

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th >
                           #
                        </th>
                        <th style="width: 20%">
                           Grade Subject
                        </th>
                        <th style="width: 20%">
                           Subject Teacher
                        </th>
                        <th style="width: 60%">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjectGrade as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                           <a>
                                {{$el->subject_name}}
                           </a>
                        </td>
                        <td>
                           <a>
                                {{$el->teacher_name}}
                           </a>
                        </td>
                        
                        <td class="project-actions text-left toastsDefaultSuccess">
                            <a class="btn btn-info btn"
                              href="{{url('/' . session('role') .'/grades/manageSubject/teacher') . '/edit/' . $el->grade_id . '/' . $el->subject_id . '/' . $el->teacher_id}}">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                            </a>
                            @if (session('role') == 'superadmin' || session('role') == 'admin')
                                <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteTypeSchedule" data-subject-id="{{ $el->subject_id }}" data-teacher-id="{{ $el->teacher_id }}" data-grade-id="{{ $el->grade_id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalDeleteTypeSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Delete subject</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">Are you sure want to delete this subject?</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a class="btn btn-danger btn" id="confirmDelete">Yes delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDeleteTypeSchedule').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var gradeId = button.data('grade-id');
            var subjectId = button.data('subject-id');
            var teacherId = button.data('teacher-id');
            var confirmDelete = document.getElementById('confirmDelete');
            confirmDelete.href = "{{ url('/' . session('role') . '/grades/subject/delete') }}" + '/' + gradeId + '/' + subjectId + '/' + teacherId;
        });
    });
</script>

@if(session('after_add_subject_grade')) 
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
                title: 'Successfully created new subject grade in the database.',
        });
        }, 1500);
    </script>
@endif

@if(session('after_delete_subject_grade')) 
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
                title: 'Successfully delete subject grade in the database.',
        });
        }, 1500);
    </script>
@endif

@endsection
