@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
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
                        <th style="width: 12%"> Grades </th>
                        <th style="width: 16%"> Teacher Class </th>
                        <th style="width: 12%;text-align:center;"> Total Student </th>
                        <th style="width: 12%;text-align:center;"> Total Subject </th>
                        <th style="width: 48%">Action</th>
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
                        <td style="text-align:center;">
                            <a>
                                {{$el->active_subject_count}}
                            </a>
                        </td>
                        
                        <td class="project-actions text-left toastsDefaultSuccess">
                           <a class="btn btn-primary btn"
                            href="{{ route('attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                <i class="fas fa-folder"> </i>
                                View
                           </a>
                        </td>
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

   @if(session('after_create_grade')) 

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
              title: 'Successfully created new grade in the database.',
        });
        }, 1500);


      </script>

   @endif

   @if(session('after_update_grade')) 
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
            title: 'Successfully updated the grade in the database.',
      });
      }, 1500);

    
      </script>
   @endif

@endsection
