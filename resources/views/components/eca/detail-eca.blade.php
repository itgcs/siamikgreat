@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="card card-dark mt-2">
      <div class="card-header">
         @foreach ($data['eca'] as $de)
            <h3 class="card-title">Students who take ECA {{ $de->name }}</h3>
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
                  <th>No</th>
                  <th style="width: 15%">Student Name</th>
                  <th style="width: 15%">Student Class</th>
                  <th style="width: 70%">Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($data['student'] as $st)
               <tr id={{'index_grade_' . $st->id}}>
                  <td>{{ $loop->index + 1 }}</td>
                  <td><a>{{$st->student_name}}</a></td>
                  <td><a>{{$st->grade_name}} - {{ $st->grade_class }}</a></td>
                  <td>
                     @if (session('role') == 'superadmin' || session('role') == 'admin')
                        <a class="btn btn-danger btn" href="{{url('/' . session('role') .'/eca') . '/delete/student/' . $st->eca_id . '/' . $st->student_id }}"><i class="fas fa-trash"></i> Delete</a>
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

@if(session('after_delete_student_eca'))
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
         title: 'Successfully deleted student eca in the database.',
      });
   }, 1500);
</script>
@endif

@endsection
