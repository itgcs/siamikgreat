@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

    {{-- @if(sizeof($data) <= 0) <div class="row h-100">
        <div class="col-sm-12 my-auto text-center">
            <h3>Payment has never been created. Click the
                button below to get started !!!</h3>
            <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
                <i class="fa-solid fa-plus"></i>
                Create bill for student
            </a>
        </div>
</div>

@else --}}
<h2 class="text-center display-4">Student Search</h2>
<form class="mt-5" action="/admin/list">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Result Type:</label>
                        @php

                        $selected = $form? $form->sort : 'name';

                        @endphp
                        <select name="type" class="form-control" required>
                            <option {{$selected === 'name' ? 'selected' : ''}} value="name">Name</option>
                            <option {{$selected === 'place_birth' ? 'selected' : ''}} value="place_birth">Place Birth
                            </option>
                        </select>

                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">

                        @php

                        $selected = $form ? $form->sort : 'desc';

                        @endphp

                        <label>Sort order: <span style="color: red"></span></label>
                        <select name="sort" class="form-control">
                            <option value="desc" {{$selected === 'desc' ? 'selected' : ''}}>Descending</option>
                            <option value="asc" {{$selected === 'asc' ? 'selected' : ''}}>Ascending</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">

                        @php

                        $selected = $form? $form->order : 'created_at';

                        @endphp

                        <label>Sort by:</label>
                        <select name="order" class="form-control">
                            <option {{$selected === 'created_at'? 'selected' : ''}} value="created_at">Register</option>
                            <option {{$selected === 'name'? 'selected' : ''}} value="name">Name</option>
                            <option {{$selected === 'grade_id'? 'selected' : ''}} value="grade_id">Grade</option>
                            <option {{$selected === 'gender'? 'selected' : ''}} value="gender">Gender</option>
                            <option {{$selected === 'place_birth'? 'selected' : ''}} value="place_birth">Place Birth
                            </option>
                            <option {{$selected === 'status'? 'selected' : ''}} value="status">Status</option>
                        </select>

                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>Status: <span style="color: red"></span></label>

                        @php

                        $selected = $form ? $form->status : 'true';
                        $option = $selected === 'false' ? 'true' : 'false';

                        @endphp

                        <select name="status" class="form-control">
                            <option selected value="{{$selected}}">{{$selected === 'true' ? 'Active' : 'Inactive'}}
                            </option>
                            <option value="{{$option}}">{{$option === 'true' ? 'Active' : 'Inactive'}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-lg">
                    <input name="search" value="{{$form? $form->search : ''}}" type="search" class="form-control form-control-lg"
                        placeholder="Type your keywords here">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<div class="card mt-5">
    <div class="card-header">
        <h3 class="card-title">Student</h3>

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
                    <th style="width: 1%">
                        #
                    </th>
                    <th style="width: 20%">
                        Student
                    </th>
                    <th>
                        Grade
                    </th>
                    <th style="width: 8%">
                        Class
                    </th>
                    <th style="width: 10%">
                        Spp
                    </th>
                    <th>
                        Total set payment
                    </th>
                    
                    <th style="width: 20%">
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $el)
                <tr id={{'index_student_' . $el->id}}>
                    <td>
                        {{ $loop->index + 1 }}
                    </td>
                    <td>
                        {{$el->name}}
                    </td>
                    <td>
                       {{$el->grade->name}}
                     </td>
                     <td>
                        {{$el->grade->class}}
                     <td>
                           <a>
                               @if($el->spp_student)
                               <h1 class="badge badge-success">already set</h1>
                               @else
                               <h1 class="badge badge-danger">not set yet</h1>
                               @endif
                           </a>
                        </td>
                        <td align="left">
                           {{sizeof($el->payment_student)}}
                        </td>
                        <td class="project-actions text-right toastsDefaultSuccess">
                           <a class="btn btn-success "
                               href="/admin/payment-students/create/{{$el->unique_id}}">
                               <i class="fa-solid fa-plus"></i>
                               </i>
                                 Create
                           </a>
                            <a class="btn btn-primary "
                                href="#">
                                <i class="fas fa-folder">
                                </i>
                                 View
                            </a>
                        </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>


{{-- @endif --}}
</div>

@endsection
