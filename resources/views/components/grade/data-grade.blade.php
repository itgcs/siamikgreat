@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <a type="button" href="{{ url('/' . session('role') . '/grades/create') }}" class="btn btn-success btn mt-5 mx-2">   <i class="fa-solid fa-user-plus"></i>
         </i>   
         Add grade
      </a>
   </div>

   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Grades</h3>

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
                     <th style="width: 10%">
                        #
                     </th>
                     <th style="width: 20%">
                        Grades
                     </th>
                     <th>
                        Total student
                     </th>
                     <th>
                        Teacher Class
                     </th>
                     <th>
                        Total subject
                     </th>
                     <th>
                        Total exam
                     </th>
                     <th style="width: 30%">
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
                        {{$el->active_exam_count}}
                     </td>
                     
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a class="btn btn-primary btn"
                           href="{{url('/' . session('role') .'/grades') . '/' . $el->id}}">
                           <i class="fas fa-folder">
                           </i>
                           View
                        </a>
                        <a class="btn btn-info btn"
                           href="{{url('/' . session('role') .'/grades') . '/edit/' . $el->id}}">
                           {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                           <i class="fas fa-pencil-alt">
                           </i>
                           Edit
                        </a>
                        @if (session('role') == 'superadmin')
                        <a class="btn btn-danger btn"
                           href="{{url('/' . session('role') .'/grades') . '/delete/' . $el->id}}">
                           <i class="fas fa-trash">
                           </i>
                           Hapus
                        </a>
                        @endif
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

   @if(session('after_delete_grade')) 

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
              title: 'Successfully delete grade in the database.',
        });
        }, 1500);


      </script>

   @endif

@endsection
