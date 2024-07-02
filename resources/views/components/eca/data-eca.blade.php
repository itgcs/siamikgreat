@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <a type="button" href="{{ url('/' . session('role') . '/eca/create') }}" class="btn btn-success btn mt-5 mx-2">   <i class="fa-solid fa-user-plus"></i>
         </i>   
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
                     <a class="btn btn-warning btn"
                        href="{{url('/' . session('role') .'/eca') . '/edit/' . $el->id}}">
                        <i class="fas fa-pencil-alt">
                        </i>
                        Edit
                     </a>
                     @if (session('role') == 'superadmin' || session('role') == 'admin')
                        <a class="btn btn-danger btn" data-toggle="modal" data-target="#exampleModalCenter">
                        <i class="fas fa-trash"></i>
                        Delete
                        </a>
                     @endif
                  </td>
               </tr>

               <!-- Modal -->
               <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLongTitle">Delete ECA</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                     </div>
                     <div class="modal-body">
                        Are you sure want to delete eca?
                     </div>
                     <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                     <a class="btn btn-danger btn"  href="{{url('/' . session('role') .'/eca') . '/delete/' . $el->id}}">Yes delete</a>
                     </div>
                  </div>
               </div>
               @endforeach
            @else
               <tr colspan="3">Eksta Culicular Academy Empty</tr>
            @endif
            </tbody>
         </table>

         
      </div>
      <!-- /.card-body -->
   </div>

   @if (count($data['ecaStudent']) === 0)
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
                     Student Name
                  </th>
                  <th style="width: 80%">
                     Student Class
                  </th>
               </tr>
            </thead>
            <tbody>
               @foreach ($data['ecaStudent'] as $es)
               <tr id={{'index_grade_' . $es->id}}>
                  <td>
                        {{ $loop->index + 1 }}
                  </td>
                  <td>
                     <a>
                           {{$el->name}}
                     </a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
      <!-- /.card-body -->
   </div>
   @endif
  
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
