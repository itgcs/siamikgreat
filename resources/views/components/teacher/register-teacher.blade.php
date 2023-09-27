@extends('layouts.admin.master')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('actionRegisterTeacher')}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Student</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="name">Fullname<span style="color: red">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter name" value="{{old('name')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nuptk">NUPTK<span style="color: red">*</span></label>
                                        <input name="nuptk" type="text" class="form-control" id="nuptk"
                                            placeholder="Enter name" value="{{old('nuptk')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nuptk')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Gender<span style="color: red">*</span></label>
                                            <select name="gender" class="form-control" required>
                                                @php
                                                $arrGender = array('Female', 'Male');
                                                @endphp

                                                <option selected {{ old('gender')? '' : 'disabled'}}>
                                                    {{ old('gender')? old('gender') : '--- Please Select One ---'}}
                                                </option>

                                                @if (old('gender'))

                                                @foreach($arrGender as $gender)

                                                @if (old('gender') !== $gender)
                                                <option>{{$gender}}</option>
                                                @endif

                                                @endforeach
                                                @else
                                                <option>Male</option>
                                                <option>Female</option>
                                                @endif
                                            </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('gender')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="place_birth">Place of Birth<span style="color: red">*</span></label>
                                        <input name="place_birth" type="text" class="form-control" id="place_birth"
                                            placeholder="Enter city" value="{{old('place_birth')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('place_birth')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">

                                        <label>Date of Birth<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input name="date_birth" type="text"
                                                class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                                data-target="#reservationdate" data-inputmask-alias="datetime"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                value="{{old('date_birth')}}"
                                                required />

                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('date_birth')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="nationality">Nationality<span style="color: red">*</span></label>
                                        <input name="nationality" type="text" class="form-control" id="nationality"
                                            placeholder="Enter city" value="{{old('nationality')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nationality')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                       <label for="home_address">Home Address<span style="color: red">*</span></label>
                                       <input name="home_address" type="text" class="form-control" id="home_address"
                                           placeholder="Enter ID/Passport" value="{{old('home_address')}}" required>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('home_address')}}</p>
                                       @endif
                                   </div>
                                   <div class="col-md-4">
                                      <div class="form-group">
                                         <label>Religion<span style="color: red">*</span></label>
                                         <select name="religion" class="form-control" required>

                                               @php
                                               $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic
                                               Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');
                                               @endphp

                                               <option selected {{ old('religion')? '' : 'disabled'}}>
                                                     {{ old('religion')? old('religion') : '--- Please Select One ---'}}
                                               </option>

                                               @if (old('religion'))

                                               @foreach($arrReligion as $religion)

                                               @if (old('religion') !== $religion)
                                               <option>{{$religion}}</option>
                                               @endif

                                               @endforeach
                                               @else
                                               @foreach($arrReligion as $religion)
                                               <option>{{$religion}}</option>
                                               @endforeach
                                               @endif
                                           </select>
                                           @if($errors->any())
                                           <p style="color: red">{{$errors->first('religion')}}</p>
                                           @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                        </div>
                        <!-- /.card-body students -->


                        <!-- /.card-body Brother or sisters -->

                        <div class="d-flex justify-content-center my-5">
                            <button type="submit" class="col-11 btn btn-success">Submit</button>
                        </div>
                    </form>

                </div>
                <!-- /.card -->

                <!-- general form elements -->
            </div>
            <!--/.col (right) -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
