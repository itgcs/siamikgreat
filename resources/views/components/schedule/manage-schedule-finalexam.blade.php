@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <div class="col">
         <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
               <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item">Home</li>
               <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/midexams')}}">Mid Exam Schedule</a></li>
               <li class="breadcrumb-item active" aria-current="page">Manage Schedule Mid Exam {{ $data[0]['grade_name'] }} - {{ $data[0]['grade_class'] }}</li>
               </ol>
         </nav>
      </div>
   </div>
   
   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Schedule Final Exam</h3>

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
                        Subject
                     </th>
                     <th>
                        Teacher
                     </th>
                     <th>
                        Companion Teacher
                     </th>
                     <th>
                        Note
                     </th>
                     <th>
                        Day
                     </th>
                     <th>
                        Start_time
                     </th>
                     <th>
                        End_time
                     </th>
                     <th>
                        Semester
                     </th>
                     <th style="width: 25%;">
                        Action
                     </th>
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
                           {{$el->subject_name}}
                        </a>
                     </td>
                     <td>
                        <a>
                           {{$el->teacher_name}}
                        </a>
                     </td>
                     <td style="width:20%;">
                        <a>
                           {{$el->teacher_companion_name}}
                        </a>
                     </td>
                     <td>
                        <a>
                           {{$el->note}}
                        </a>
                     </td>
                     <td  class="text-center">
                        <a>
                           {{$el->day}}
                        </a>
                     </td>
                     <td  class="text-center">
                        {{$el->start_time}}
                     </td>
                     <td  class="text-center">
                        {{$el->end_time}}
                     </td>
                     <td class="text-center">
                        {{$el->semester}}
                     </td>
                     
                     <td class="project-actions text-left toastsDefaultSuccess">
                        <a class="btn btn-primary btn"
                           href="{{url('/' . session('role') .'/schedules/edit/finalexam') . '/'. $el->grade_id .'/' . $el->id}}">
                           <i class="fas fa-pen">
                           </i>
                           Edit
                        </a>
                        <a class="btn btn-danger btn"
                           href="{{url('/' . session('role') .'/schedules/delete/finalexam') . '/' . $el->id}}">
                           <i class="fas fa-trash">
                           </i>
                           Delete
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
   @if(session('after_update_finalexam_schedule')) 
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
            title: 'Successfully updated the mid exam schedule in the database.',
      });
      }, 1500);

    
      </script>
   @endif

   @if(session('after_edit_finalexam_schedule')) 

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
            title: 'Successfully edit data mid exam schedule in the database.',
      });
      }, 1500);


   </script>

   @endif

   @if(session('after_delete_finalexam')) 
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
            title: 'Successfully deleted mid exam schedule in the database.',
      });
      }, 1500);

    
      </script>
   @endif

   @if(session('after_delete_schedule_subtitute')) 
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
            title: 'Successfully deleted the subtitute schedule in the database.',
      });
      }, 1500);

    
      </script>
   @endif

@endsection
