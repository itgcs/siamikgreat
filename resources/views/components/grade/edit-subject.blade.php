@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="card card-dark mt-2">
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
                                <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteTypeSchedule" data-id="{{ $el->subject_id }}">
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
        var id = button.data('id');
        var confirmDelete = document.getElementById('confirmDelete');
        confirmDelete.href = "{{ url('/' . session('role') . '/typeSchedules/delete') }}/" + id;
    });
});
</script>

    @if(session('after_create_typeSchedule')) 

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
              title: 'Successfully created new type schedule in the database.',
        });
        }, 1500);


      </script>

    @endif
  
    @if(session('after_update_typeSchedule')) 
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
                title: 'Successfully updated the type schedule in the database.',
        });
        }, 1500);

        </script>
    @endif

    @if(session('after_delete_typeSchedule')) 
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
                title: 'Successfully delete the type schedule in the database.',
        });
        }, 1500);

        
        </script>
    @endif

@endsection
