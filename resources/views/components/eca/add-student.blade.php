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
                        <form method="POST" action={{route('actionSuperCreateEca')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateEca')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create ECA</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="student_name">Select Student</label>
                                        <select name="student_name[]" id="student_name" class="js-select2 form-control" multiple="multiple>
                                            <option value="" >-- SELECTED STUDENT --</option>
                                            @foreach ($data as $dt)
                                                <option value="{{ $dt->unique_id }}">{{ $dt->name }} ({{  }})</option>
                                            @endforeach
                                        </select>

                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('student_name')}}</p>
                                       @endif
                                    </div>
                                 </div>
                              
                                 <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                                 </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection
