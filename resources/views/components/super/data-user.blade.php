@extends('layouts.admin.master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid">
    <a type="button" href="users/register-user" id="#" class="btn btn-success btn mb-2">
        <i class="fa-solid fa-user-plus me-1"></i>
        </i>
        Add user
    </a>
    <div class="row">
        <div class="col-4">
            <div class="card card-dark mt-3">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
        
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    Username
                                </th>
                                <th>
                                    Created at
                                </th>
                                <th style="width:70%;">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $el)
                            <tr id="index_user_{{ $el->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{$el->username}}</td>
                                <td>{{$el->created_at ? date("d/m/Y", strtotime($el->created_at)) : 'Unknown'}}</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#{{'changePassword' . $el->id}}">
                                        <i class=" fas fa-solid fa-unlock-keyhole"></i>
                                        Change password
                                    </button>
        
                                    <a href="javascript:void(0)" id="delete-user" data-id="{{ $el->id }}"
                                        data-name="{{ $el->username }}" class="btn btn-danger">
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
        
                            <!-- Modal -->
                            <div class="modal fade" id="{{'changePassword' . $el->id}}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Change password - {{$el->username}}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action={{ route('user.editPassword', $el->id) }}
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="">Password</label>
                                                    <input name="password" type="password" class="form-control" id="cpassword-{{$el->id}}">
                                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                                </div>
                                                <div class="form-group">
                                                    <label>Reinput password</label>
                                                    <input name="reinputPassword" type="password" class="form-control" id="rcpassword-{{$el->id}}">
                                                </div>
        
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
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
        <div class="col-4">
            <div class="card card-dark mt-3">
                <div class="card-header">
                    <h3 class="card-title">Students</h3>
        
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    Username
                                </th>
                                <th>
                                    Created at
                                </th>
                                <th style="width:70%;">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $st)
                            <tr id="index_user_student_{{ $st->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{$st->username}}</td>
                                <td>{{$st->created_at ? date("d/m/Y", strtotime($st->created_at)) : 'Unknown'}}</td>
                                <td class="grid">
                                    <button type="button" class="btn btn-info w-full" data-toggle="modal"
                                        data-target="#{{'changePasswordStudent' . $st->id}}">
                                        <i class=" fas fa-solid fa-unlock-keyhole"></i>
                                        Change password
                                    </button>
        
                                    <a href="javascript:void(0)" id="delete-user" data-id="{{ $st->id }}"
                                        data-name="{{ $st->username }}" class="btn btn-danger w-full">
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
        
                            <!-- Modal -->
                            <div class="modal fade" id="{{'changePasswordStudent' . $st->id}}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Change password - {{$st->username}}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action={{ route('user.editPassword', $st->id) }}
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="">Password</label>
                                                    <input name="password" type="password" class="form-control" id="cspassword-{{$st->id}}">
                                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                                </div>
                                                <div class="form-group">
                                                    <label>Reinput password</label>
                                                    <input name="reinputPassword" type="password" class="form-control" id="rcspassword-{{$st->id}}">
                                                </div>
        
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
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
        <div class="col-4">
            <div class="card card-dark mt-3">
                <div class="card-header">
                    <h3 class="card-title">Users Parents</h3>
        
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    Username
                                </th>
                                <th>
                                    Created at
                                </th>
                                <th style="width:70%;">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parents as $pr)
                            <tr id="index_user_parents_{{ $pr->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{$pr->username}}</td>
                                <td>{{$pr->created_at ? date("d/m/Y", strtotime($pr->created_at)) : 'Unknown'}}</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#{{'changePasswordParent' . $pr->id}}">
                                        <i class=" fas fa-solid fa-unlock-keyhole"></i>
                                        Change password
                                    </button>
        
                                    <a href="javascript:void(0)" id="delete-user" data-id="{{ $pr->id }}"
                                        data-name="{{ $pr->username }}" class="btn btn-danger">
                                        <i class="fas fa-trash">
                                        </i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
        
                            <!-- Modal -->
                            <div class="modal fade" id="{{'changePasswordParent' . $pr->id}}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Change password - {{$pr->username}}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action={{ route('user.editPassword', $pr->id) }}
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="">Password</label>
                                                    <input name="password" type="password" class="form-control" id="cppassword-{{$pr->id}}">
                                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                                </div>
                                                <div class="form-group">
                                                    <label>Reinput password</label>
                                                    <input name="reinputPassword" type="password" class="form-control" id="rcppassword-{{$pr->id}}">
                                                </div>
        
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
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

    </div>
</div>

@include('components.super.delete-user')

<!-- sweetalert2 -->
<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('password.success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfuly',
            text: 'Success update password',
        });
    </script>
@endif


@if(session('register.success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Success add users',
        });
    </script>
@endif


@if(session('error.type.password'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Make sure your input password is the same !!!',
        });
    </script>
@endif

@if(session('error.password'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'The password must be at least 5 !!!',
        });
    </script>
@endif

@include('components.super.delete-user')

@endsection
