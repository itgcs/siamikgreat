@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

   <div class="card card-dark mt-5">
      <div class="card-header">
         <h3 class="card-title">Schedule Grade</h3>

         <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
         </div>
      </div>
      <div class="card-body">
         <table class="table table-striped projects">
               <thead>
                  <tr>
                     <th >
                        #
                     </th>
                     <th>
                        Grades
                     </th>
                     <th>
                        Total student
                     </th>
                     <th>
                        Total teacher
                     </th>
                     <th>
                        Total subject
                     </th>
                     <th>
                        Total schedule
                     </th>
                     <th >
                     </th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data['grade'] as $el)
                  <tr id={{'index_grade_' . $el->id}}>
                     <td>
                           {{ $loop->index + 1 }}
                     </td>
                     <td>
                        <a>
                              {{$el->name . ' - ' . $el->class}}
                        </a>
                     </td>
                     <td>
                        <a>
                              {{$el->active_student_count}}
                        </a>
                     </td>
                     <td>
                        <a>
                              {{$el->active_teacher_count}}
                        </a>
                     </td>
                     <td>
                        <a>
                              {{$el->active_subject_count}}
                        </a>
                     </td>
                     <td>
                        {{$el->active_schedule_count}}
                     </td>
                     
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a class="btn btn-warning btn"
                           href="{{url('/' . session('role') .'/schedules/manage') . '/' . $el->id}}">
                           <i class="fas fa-folder">
                           </i>
                           Manage
                        </a>
                        <a class="btn btn-primary btn"
                           href="{{url('/' . session('role') .'/schedules/detail') . '/' . $el->id}}">
                           <i class="fas fa-folder">
                           </i>
                           View
                        </a>
                        <a class="btn btn-success btn"
                           href="{{url('/' . session('role') .'/schedules/grade/create') . '/' . $el->id}}">
                           <i class="fas fa-calendar-plus">
                           </i>
                           Add
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

   @if(session('after_update_schedule')) 
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
            title: 'Successfully updated the schedule in the database.',
      });
      }, 1500);

    
      </script>
   @endif

@endsection
