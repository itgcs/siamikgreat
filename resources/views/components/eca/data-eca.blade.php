@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item active" aria-current="page">Ekstra Culicular Academy</li>
               </ol>
            </nav>
      </div>
   </div>  

   <div class="row">
      <a type="button" href="{{ url('/' . session('role') . '/eca/create') }}" class="btn btn-success btn mx-2">   
         <i class="fa-solid fa-people-group"></i>
         Add eca
      </a>
   </div>

   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">ECA</h3>

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
                  <th>
                     #
                  </th>
                  <th style="width: 15%">
                     ECA
                  </th>
                  <th style="width: 80%">
                     Action
                  </th>
               </tr>
            </thead>
            @if (!empty($data['data']))
            <tbody>
               @foreach ($data['data'] as $el)
               <tr id={{'index_grade_' . $el->id}}>
                  <td>
                        {{ $loop->index + 1 }}
                  </td>
                  <td>
                     <a>
                           {{$el->name}}
                     </a>
                  </td>
                  
                  <td class="project-actions text-left toastsDefaultSuccess">
                     <a class="btn btn-info btn"
                        href="{{url('/' . session('role') .'/eca') . '/add/' . $el->id}}">
                        <i class="fas fa-user-plus">
                        </i>
                        Add Student
                     </a>
                     <!-- <a class="btn btn-warning btn"
                        href="{{url('/' . session('role') .'/eca') . '/edit/' . $el->id}}">
                        <i class="fas fa-pencil-alt">
                        </i>
                        Edit
                     </a> -->
                     <a class="btn btn-warning btn"
                        href="{{url('/' . session('role') .'/eca') . '/view/' . $el->id}}">
                        <i class="fas fa-pencil">
                        </i>
                        Manage
                     </a>
                     @if (session('role') == 'superadmin' || session('role') == 'admin')
                        <a class="btn btn-danger btn" href="{{url('/' . session('role') .'/eca') . '/delete/' . $el->id}}">
                        <i class="fas fa-trash"></i>
                        Delete
                        </a>
                     @endif
                  </td>
               </tr>
               @endforeach
            @else
               <tr colspan="3">Eksta Culicular Academy Empty</tr>
            @endif
            </tbody>
         </table>

         
      </div>
      <!-- /.card-body -->
   </div>
  
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_eca')) 

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
              title: 'Successfully created new eca in the database.',
        });
        }, 1500);


      </script>

  @endif

  @if(session('after_add_student_eca')) 

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
              title: 'Successfully add student eca in the database.',
        });
        }, 1500);


      </script>

  @endif

  @if(session('after_update_eca')) 
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
            title: 'Successfully updated the eca in the database.',
      });
      }, 1500);

    
    </script>
   @endif

   @if(session('after_delete_eca')) 

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
              title: 'Successfully deleted eca in the database.',
        });
        }, 1500);

      </script>

  @endif

@endsection
