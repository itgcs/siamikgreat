@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Attendances</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title">Attendance</h3>

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
                        <th > # </th>
                        <th style="width: 15%"> Grades </th>
                        <th style="width: 20%"> Teacher Class </th>
                        <th style="width: 10%;text-align:center;"> Total Student </th>
                        <th style="width: 55%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            <a>
                                {{$el->grade_name . ' - ' . $el->grade_class}}
                            </a>
                        </td>
                        <td>
                            <a>
                                {{$el->teacher_class}}
                            </a>
                        </td>
                        <td style="text-align:center;">
                            <a>
                                {{$el->active_student_count}}
                            </a>
                        </td>
                        </td>
                        @if (session('role') == 'superadmin')
                            <td class="project-actions text-left toastsDefaultSuccess">
                                <a class="btn btn-primary btn"
                                    href="{{ route('super.attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                        <i class="fas fa-eye"> </i>
                                        View
                                </a>
                            </td>   
                        @elseif (session('role') == 'admin')
                            <td class="project-actions text-left toastsDefaultSuccess">
                            <a class="btn btn-primary btn"
                                href="{{ route('attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                    <i class="fas fa-eye"> </i>
                                    View
                            </a>
                            </td> 
                        @endif
                        
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('data_is_empty')) 
      <script> 
         setTimeout(() => {
            Swal.fire({
               icon: 'error',
               title: 'Oops..',
               text: 'Data Attendance is Empty !!!',
            });
         }, 500);
      </script>
   @endif

@endsection
