@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Scorings</li>
                </ol>
            </nav>
        </div>
   </div>  

   {{-- <div class="col-12">
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
                               <option value="asc" {{$selectedSort === 'asc' ? 'selected' : ''}}>Ascending</option>
                               <option value="desc" {{$selectedSort === 'desc' ? 'selected' : ''}}>Descending</option>
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
                                       <option {{$selectedOrder === 'created_at'? 'selected' : ''}} value="date">Date</option>
                                       <option {{$selectedOrder === 'name'? 'selected' : ''}} value="grade">Grade</option>
                                       <option {{$selectedOrder === 'gender'? 'selected' : ''}} value="subject">Subject</option>
                                       <option {{$selectedOrder === 'place_birth'? 'selected' : ''}} value="teacher">Teacher</option>
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
   </div> --}}

    @if (session('role') == 'superadmin')
    <form class="row col-12" action="/superadmin/exams">
    @elseif (session('role') == 'admin')
    <form class="row col-12" action="/admin/exams">
    @endif
    {{-- GRADES --}}
        <div class="col-md-3">
            <div class="form-group">
                @php
                    $selectGrades = $form->grades;
                @endphp

                <label>Grade:</label>
                <select name="grade" class="form-control" id="grade-select" onchange="this.form.submit()">
                    <option value="all" {{ $selectGrades === 'all' ? 'selected' : '' }}>All Grades</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade['id'] }}" {{ $selectGrades == $grade['id'] ? 'selected' : '' }}>
                            {{ ucwords($grade['name']) }} - {{$grade['class']}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    {{-- SUBJECTS --}}
        <div class="col-md-3">
            <div class="form-group">
                @php
                    $selectSubjects = $form->subjects;
                @endphp

                <label>Subject:</label>
                <select name="subject" class="form-control" id="subject-select" onchange="this.form.submit()">
                    <option value="all" {{ $selectSubjects === 'all' ? 'selected' : '' }}>All Subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject['id'] }}" {{ $selectSubjects == $subject['id'] ? 'selected' : '' }}>
                            {{ ucwords($subject['name_subject']) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    {{-- TEACHERS --}}
        <div class="col-md-3">
            <div class="form-group">
                @php
                    $selectTeachers = $form->teachers;
                @endphp

                <label>Teacher:</label>
                <select name="teacher" class="form-control" id="teacher-select" onchange="this.form.submit()">
                    <option value="all" {{ $selectSubjects === 'all' ? 'selected' : '' }}>All Teacher</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher['id'] }}" {{ $selectTeachers == $teacher['id'] ? 'selected' : '' }}>
                            {{ ucwords($teacher['name']) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    {{-- TYPE --}}
        <div class="col-md-3">
            <div class="form-group">
                @php
                    $selectType = $form->type;
                @endphp

                <label>Type:</label>
                <select name="type" class="form-control" id="type-select" onchange="this.form.submit()">
                    <option value="all" {{ $selectType === 'all' ? 'selected' : '' }}>All Type</option>
                    @foreach ($type as $type)
                        <option value="{{ $type['id'] }}" {{ $selectType == $type['id'] ? 'selected' : '' }}>
                            {{ ucwords($type['name']) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    {{-- SEARCH --}}
    <div class="col-md-12">
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
    </form>

    <div class="row">
        <a type="button" href="{{url('/' . session('role') . '/exams/create')}}" class="btn btn-success btn mx-2">
            <i class="fa-solid fa-user-plus"></i>
            </i>   
            Add Scorings
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
                        <th style="width: 15%">Grade</th>
                        <th style="width: 10%">Type Exam</th>
                        <th style="width: 15%">Subject</th>
                        <th style="width: 15%">Teacher</th>
                        <th style="width: 10%">Date</th>
                        <th style="width: 20%">Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{$el->grade_name}} - {{ $el->grade_class }}
                        </td>
                        <td>
                            <a>
                                {{$el->type_exam}}
                            </a>
                        </td>
                        <td>
                            {{$el->subject_name}}
                        </td>
                        <td>
                            {{$el->teacher_name}}
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
                            @endif
                        </td>
                        <td>
                            <a>
                                {{$el->name_exam}}
                            </a>
                        </td>
                        <td>
                            @if($el->is_active)
                            <span class="badge badge-success"> Active </span>
                            @else
                            <span class="badge badge-danger"> Done </span>
                            @endif
                        </td>
                        <td class="col">
                            <a class="btn btn-success btn text-sm w-100 mb-1" href="{{url('/exams') . '/score/' . $el->id}}">
                                <i class="fas fa-book"></i>
                                Score
                            </a>
                            <a class="btn btn-primary btn text-sm w-100 mb-1" href="{{url('/' . session('role') . '/exams') . '/' . $el->id}}">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            <a class="btn btn-warning btn text-sm w-100 mb-1" href="{{url('/' . session('role') . '/exams') . '/edit/' . $el->id}}">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn text-sm w-100"
                                id="deleteExam" data-id="{{ $el->id }}">
                                <i class="fas fa-trash">
                                </i>
                                Delete
                            </a>
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

<div class="d-flex justify-cfontent-end my-5">

    <nav aria-label="...">
        <ul class="pagination" max-size="2">
            
            @php
            $role = session('role');
            $link = "/{$role}/exams?search={$form->search}";
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

<script>
      $(document).on('click', '#deleteExam', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete this scoring!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete.exams') }}",
                    type: 'POST',
                    data: {
                        exam_id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Delete Successfull",
                            text: "Scoring already delete",
                            icon: "success"
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error occurred!");
                    }
                });
            }
        });
    })
</script>


@if(session('after_create_exam')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new exam in the database.',
        });
    </script>
@endif

@if(session('after_update_exam')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the exam in the database.',
        });
    </script>
@endif

@if(session('after_done_exam')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully done exam in the database.',
        });
    </script>
@endif

@endsection
