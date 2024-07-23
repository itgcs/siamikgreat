@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Exam</li>
                </ol>
            </nav>
        </div>
   </div>  

    <h2 class="text-center">Exam Search</h2>
        @if (session('role') == 'superadmin')
        <form class="mt-5" action="/superadmin/exams">
        @elseif (session('role') == 'admin')
        <form class="mt-5" action="/admin/exams">
        @endif
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Result Type:</label>
                                @php
                                    
                                    $selectedType = $form && $form->type ? $form->type : 'name';

                                @endphp
                                <select name="type" class="form-control" required>
                                    <option {{$selectedType === 'name' ? 'selected' : ''}} value="name">Name</option>
                                    <option {{$selectedType === 'place_birth' ? 'selected' : ''}} value="place_birth">Place Birth</option>
                                    <option {{$selectedType === 'nationality' ? 'selected' : ''}} value="nationality">Nationality</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">

                                @php
                                    
                                    $selectedSort = $form->sort ? $form->sort : 'desc';

                                @endphp

                            <label>Sort order: <span style="color: red"></span></label>
                            <select name="sort" class="form-control">
                                <option value="desc" {{$selectedSort === 'desc' ? 'selected' : ''}}>Descending</option>
                                <option value="asc" {{$selectedSort === 'asc' ? 'selected' : ''}}>Ascending</option>
                            </select>                              
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">

                            @php

                                $selectedOrder = $form->order? $form->order : 'created_at';

                            @endphp

                                <label>Sort by:</label>
                                <select name="order" class="form-control">
                                        <option {{$selectedOrder === 'created_at'? 'selected' : ''}} value="created_at">Register</option>
                                        <option {{$selectedOrder === 'name'? 'selected' : ''}} value="name">Name</option>
                                        <option {{$selectedOrder === 'gender'? 'selected' : ''}} value="gender">Gender</option>
                                        <option {{$selectedOrder === 'place_birth'? 'selected' : ''}} value="place_birth">Place Birth</option>
                                        <option {{$selectedOrder === 'status'? 'selected' : ''}} value="status">Status</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                            <label>Status: <span style="color: red"></span></label>

                            @php
                                
                                $selectedStatus = $form->status ? $form->status : 'true';
                                $option = $selectedStatus === 'false' ? 'true' : 'false';

                            @endphp

                            <select name="status" class="form-control">
                                <option  selected value="{{$selectedStatus}}">{{$selectedStatus === 'true' ? 'Active' : 'Inactive'}}</option>
                                <option  value="{{$option}}">{{$option === 'true' ? 'Active' : 'Inactive'}}</option>
                            </select>                              
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input name="search" value="{{$form->search}}" type="search" class="form-control form-control-lg" placeholder="Type your keywords here">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form >

    <div class="row">
        <a type="button" href="{{url('/' . session('role') . '/exams/create')}}" class="btn btn-success btn mx-2">
            <i class="fa-solid fa-user-plus"></i>
            </i>   
            Add Exam
        </a>
    </div>
   
    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Exams</h3>

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
                        <th>#</th>
                        <th style="width: 20%">Name</th>
                        <th style="width: 10%">Date</th>
                        <th style="width: 10%">Grade</th>
                        <th style="width: 15%">Subject</th>
                        <th style="width: 15%">Teacher</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 20%">Action</th>
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
                            {{$el->name_exam}}
                            </a>
                        </td>
                        <td>
                            <a>
                            {{$el->date_exam}}
                            </a>
                            <br>
                            @php
                            $currentDate = now(); // Tanggal saat ini
                            $dateExam = $el->date_exam; // Tanggal exam dari data

                            // Hitung selisih antara tanggal exam dengan tanggal saat ini
                            $diff = strtotime($dateExam) - strtotime($currentDate);
                            $days = floor($diff / (60 * 60 * 24)); // Konversi detik ke hari
                            @endphp
                            @if($el->is_active)
                            <small class="text-muted mb-0"><span class="badge badge-danger">{{$days}} days again</span></small>
                            @else
                            @endif
                        </td>
                        <td>
                            {{$el->grade_name}} - {{ $el->grade_class }}
                        </td>
                        <td>
                            {{$el->subject_name}}
                        </td>
                        <td>
                            {{$el->teacher_name}}
                        </td>
                        <td>
                            @if($el->is_active)
                            <span class="badge badge-success"> Active </span>
                            @else
                            <span class="badge badge-danger"> Done </span>
                            @endif
                        </td>
                        <td class="project-actions text-left toastsDefaultSuccess">
                            <a class="btn btn-primary btn-sm"
                            href="{{url('/' . session('role') . '/exams') . '/' . $el->id}}">
                            <i class="fas fa-eye">
                            </i>
                            View
                            </a>
                            <a class="btn btn-warning btn-sm"
                            href="{{url('/' . session('role') . '/exams') . '/edit/' . $el->id}}">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Edit
                            </a>
                            <!-- <a class="btn btn-success btn-sm"
                            href="{{url('/' . session('role') . '/exams') . '/done/' . $el->id}}">
                            <i class="fas fa-check">
                            </i>
                            Done
                            </a> -->
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

{{-- pagination --}}

<div class="d-flex justify-content-end my-5">

    <nav aria-label="...">
        <ul class="pagination" max-size="2">
            
            @php
            $role = session('role');
            $link = "/{$role}/exams?status={$selectedStatus}&search={$form->search}";
            $previousLink = $link . '&page=' . ($data->currentPage() - 1);
            $nextLink = $link . '&page=' . ($data->currentPage() + 1);
            $firstLink = $link . '&page=1';
            $lastLink = $link . '&page=' . $data->lastPage();
            
            $arrPagination = [];
            $flag = false;
            
            if ($data->lastPage() - 5 > 0) {
                if ($data->currentPage() <= 4) {
                    for ($i = 1; $i <= 5; $i++) {
                        $temp = (object) [
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        array_push($arrPagination, $temp);
                    }
                } else if ($data->lastPage() - $data->currentPage() > 2) {
                    $flag = true;
                    $idx = [$data->currentPage() - 2, $data->currentPage() - 1, $data->currentPage(), $data->currentPage() + 1, $data->currentPage() + 2];
                    foreach ($idx as $value) {
                        $temp = (object) [
                            'page' => $value,
                            'link' => $link . '&page=' . $value,
                        ];
                        array_push($arrPagination, $temp);
                    }
                } else {
                    $arrFirst = [];
                    for ($i = $data->currentPage(); $i <= $data->lastPage(); $i++) {
                        $temp = (object) [
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        array_push($arrFirst, $temp);
                    }
                    
                    $arrLast = [];
                    $diff = $data->currentPage() - (5 - sizeof($arrFirst));
                    for ($i = $diff; $i < $data->currentPage(); $i++) {
                        $temp = (object) [
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        array_push($arrLast, $temp);
                    }
                    
                    $arrPagination = array_merge($arrLast, $arrFirst);
                }
            } else {
                for ($i = 1; $i <= $data->lastPage(); $i++) {
                    $temp = (object) [
                        'page' => $i,
                        'link' => $link . '&page=' . $i,
                    ];
                    array_push($arrPagination, $temp);
                }
            }
            @endphp

            <li class="mr-1 page-item {{$data->previousPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$firstLink}}" tabindex="+1">
                    << First
                </a>
            </li>

            <li class="page-item {{$data->previousPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$previousLink}}" tabindex="-1">
                    Previous
                </a>
            </li>

            @foreach ($arrPagination as $el)
            <li class="page-item {{$el->page === $data->currentPage() ? 'active' : ''}}">
                <a class="page-link" href="{{$el->link}}">
                    {{$el->page}}
                </a>
            </li>
            @endforeach

            <li class="page-item {{$data->nextPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$nextLink}}" tabindex="+1">
                    Next
                </a>
            </li>

            <li class="ml-1 page-item {{$data->nextPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$lastLink}}" tabindex="+1">
                    Last >>
                </a>
            </li>

        </ul>   
    </nav>


</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_exam')) 

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
              title: 'Successfully created new exam in the database.',
        });
        }, 1500);


      </script>

  @endif


  
  
   @if(session('after_update_exam')) 
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
            title: 'Successfully updated the exam in the database.',
      });
      }, 1500);

    
      </script>
   @endif

   @if(session('after_done_exam')) 
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
            title: 'Successfully done exam in the database.',
      });
      }, 1500);

    
    </script>
   @endif

@endsection
