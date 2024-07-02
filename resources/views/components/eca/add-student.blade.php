@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperAddStudent')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminAddStudent')}}>
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
                                        <label for="Eca">ECA</label>
                                        <select name="eca" id="eca" class="form-control">
                                            @foreach ($eca as $ec)
                                                <option value="{{ $ec->id }}" selected>{{ $ec->name }}</option>
                                            @endforeach
                                        </select>

                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('eca')}}</p>
                                       @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="student_id">Select Student</label>
                                        <select name="student_id[]" id="student_id" class="js-select2 form-control" multiple="multiple>
                                            <option value="" >-- SELECTED STUDENT --</option>
                                            @foreach ($data as $dt)
                                                <option value="{{ $dt->id }}">{{ $dt->name }} ({{ $dt->grade_name }} - {{ $dt->grade_class }})</option>
                                            @endforeach
                                        </select>

                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('student_id')}}</p>
                                       @endif
                                    </div>
                                </div>
                              
                                <div class="row d-flex justify-content-center">
                                <input role="button" type="submit" class="btn btn-success center col-12 m-3">
                                </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection
