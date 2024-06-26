@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <a type="button" href="{{ url('/' . session('role') . '/subjects/create') }}" class="btn btn-success btn mt-5 mx-2">   <i class="fa-solid fa-user-plus"></i>
         </i>   
         Add subject
      </a>
   </div>

    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Subjects</h3>

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
                           Subjects
                        </th>
                        <th style="width: 80%">
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
                                 {{$el->name_subject}}
                           </a>
                        </td>
                        
                        <td class="project-actions text-left toastsDefaultSuccess">
                           <a class="btn btn-info btn"
                              href="{{url('/' . session('role') .'/subjects') . '/edit/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
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

                    @endforeach
                </tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Delete subject</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
                  </div>
                  <div class="modal-body">
                     Are you sure want to delete subject?
                  </div>
                  <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                  <a class="btn btn-danger btn"  href="{{url('/' . session('role') .'/subjects') . '/delete/' . $el->id}}">Yes delete</a>
                  </div>
               </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_subject')) 

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
              title: 'Successfully created new subject in the database.',
        });
        }, 1500);


      </script>

  @endif


  
  @if(session('after_update_subject')) 
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
            title: 'Successfully updated the subject in the database.',
      });
      }, 1500);

    
    </script>
   @endif

   @if(session('after_delete_subject')) 

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
              title: 'Successfully deleted subject in the database.',
        });
        }, 1500);

      </script>

  @endif

@endsection
