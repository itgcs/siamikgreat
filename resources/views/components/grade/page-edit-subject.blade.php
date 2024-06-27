@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateGradeSubjectTeacher', $data->teacher_subject_id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateGradeSubjectTeacher', $data->teacher_subject_id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit Subject Teacher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $data->grade_id }}" class="d-none" name="grade_id" id="grade_id">
                                        <input type="text" value="{{ $data->subject_id }}" class="d-none" name="before_subject_id" id="before_subject_id">
                                        <label for="name">Subject<span style="color: red"> *</span></label>
                                        <select name="subject" id="subject" class="form-control">
                                            <option value="{{ $data->subject_id }}" selected>{{ $data->subject_name }}</option>
                                            @foreach ($subject as $sub)
                                                <option value="{{$sub->id}}">{{ $sub->name_subject }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <label for="name">Teacher<span style="color: red"> *</span></label>
                                        <select name="teacher" id="teacher" class="form-control">
                                            <option value="{{ $data->teacher_id }}" selected>{{ $data->teacher_name }}</option>
                                            @foreach ($teacher as $teacher)
                                                <option value="{{$teacher->id}}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('teacher')}}</p>
                                        @endif
                                    </div>

                                 </div>
                                </div>
                                
                                <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@if(session('after_update_subject_grade')) 
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
            title: 'Successfully updated the subject grade in the database.',
      });
      }, 1500);

    
      </script>
   @endif
@endsection