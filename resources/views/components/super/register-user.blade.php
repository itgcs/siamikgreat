@extends('layouts.admin.master')

@section('content')

<div class="row d-flex justify-content-center">
    <div class="register-box m-5">
        <div class="register-logo">
            <a><b>Admin</b> Access</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new user</p>

                @if (session('role') == 'superadmin')
                <form action="/superadmin/users/register-action" method="post" enctype="multipart/form-data">
                @elseif (session('role') == 'admin')
                <form action="/admin/users/register-action" method="post" enctype="multipart/form-data">
                @endif
                    @csrf
                    @method('post')
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" value="{{old('username')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    @if($errors->any())
                        <p style="color: red">{{$errors->first('username')}}</p>
                    @endif   

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" value="{{old('password')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <p style="color: red">{{$errors->first('password')}}</p>
                    @endif

                    <div class="input-group mb-3">
                        <input value="{{old('reinputPassword')}}" type="password" class="form-control" placeholder="Retype password" name="reinputPassword">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <p style="color: red">{{$errors->first('reinputPassword')}}</p>
                    @endif

                    @php
                        $allRole = $data['dataRole'];
                        $dataTeacher = $data['dataTeacher'];
                        $dataStudent = $data['dataStudent'];
                        $dataParent = $data['dataParent'];
                    @endphp

                    <div class="input-group mb-3">
                        <select id="role" name="role" class="form-control form-control">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>--- SELECT ROLE ---</option>
                            @foreach ($allRole as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name == 'superadmin' ? 'Super Admin' : ucwords($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->any())
                            <p style="color: red">{{$errors->first('role')}}</p>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-regular fa-address-card"></span>
                            </div>
                        </div>
                    </div>

                    <div id="teacherDropdown" class="input-group mb-3" style="display:none">
                        <select id="teacher" name="teacher" class="form-control form-control">
                            <option value="" disabled {{ old('teacher') ? '' : 'selected' }}>--- SELECT TEACHER ---</option>
                            @foreach ($dataTeacher as $teacher)
                                @if ($teacher->user_id == null)
                                    <option value="{{ $teacher->id }}" {{ old('teacher') == $teacher->id ? 'selected' : '' }}>
                                        {{ ucwords($teacher->name) }}
                                    </option>        
                                @endif
                            @endforeach
                        </select>
                        @if($errors->any())
                            <p style="color: red">{{$errors->first('teacher')}}</p>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-regular fa-address-card"></span>
                            </div>
                        </div>
                    </div>

                    <div id="studentDropdown" class="input-group mb-3" style="display:none">
                        <select id="student" name="student" class="form-control form-control">
                            <option value="" disabled {{ old('student') ? '' : 'selected' }}>--- SELECT STUDENT ---</option>
                            @foreach ($dataStudent as $student)
                                @if ($student->user_id == null)
                                    <option value="{{ $student->id }}" {{ old('student') == $student->id ? 'selected' : '' }}>
                                        {{ ucwords($student->name) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @if($errors->any())
                            <p style="color: red">{{$errors->first('student')}}</p>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-regular fa-address-card"></span>
                            </div>
                        </div>
                    </div>

                    <div id="parentDropdown" class="input-group mb-3" style="display:none">
                        <select id="parent" name="parent" class="form-control form-control">
                            <option value="" disabled {{ old('parent') ? '' : 'selected' }}>--- SELECT PARENT ---</option>
                            @foreach ($dataParent as $parent)
                                @if ($parent->user_id == null)
                                    <option value="{{ $parent->id }}" {{ old('parent') == $parent->id ? 'selected' : '' }}>
                                        {{ ucwords($parent->name) }}
                                    </option>   
                                @endif
                            @endforeach
                        </select>
                        @if($errors->any())
                            <p style="color: red">{{$errors->first('parent')}}</p>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-regular fa-address-card"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="term" required>
                                <label for="agreeTerms">
                                    I agree to the terms
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <button type="submit" class="mt-3 btn btn-primary btn-block">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
       role = this.value;
       console.log(role);

        if (role === '3') {
            document.getElementById('teacherDropdown').style.display = '';
            document.getElementById('studentDropdown').style.display = 'none';
            document.getElementById('parentDropdown').style.display = 'none'; 
        } else if (role === '4') {
            document.getElementById('teacherDropdown').style.display = 'none';
            document.getElementById('studentDropdown').style.display = '';
            document.getElementById('parentDropdown').style.display = 'none'; 
        } else if (role === '5') {
            document.getElementById('teacherDropdown').style.display = 'none';
            document.getElementById('studentDropdown').style.display = 'none';
            document.getElementById('parentDropdown').style.display = ''; 
        } else {
            document.getElementById('teacherDropdown').style.display = 'none';
            document.getElementById('studentDropdown').style.display = 'none';
            document.getElementById('parentDropdown').style.display = 'none'; 
        }
    });
</script>

@endsection
