@extends('layouts.admin.master')
@section('content')

<style>
    .full-height {
        height: 50vh;
        display: flex;
        flex-direction: column; /* Ensure the content stacks vertically */
        justify-content: center;
        align-items: center;
        position: relative;
    }
    .icon-wrapper {
        text-align: center;
    }
    .icon-wrapper i {
        font-size: 200px;
        color: #ccc;
    }
    .icon-wrapper p {
        margin: 0; /* Add margin for spacing */
        font-size: 1.5rem;
        color: black;
        text-align: center;
    }
    .btn-container {
        margin-top: 2px; /* Add margin for spacing */
    }
</style>

<!-- Content Wrapper. Contains page content -->
 
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Scorings</li>
                </ol>
            </nav>
        </div>
    </div>
    @if (sizeof($data) != 0)
        <div class="row">
            <a type="button" href="{{ url('/teacher/dashboard/exam/create') }}" class="btn btn-danger btn mx-2">
                <i class="fa-solid fa-plus"></i>
                </i>   
                Add Scoring
            </a>
        </div>

        <div class="card card-dark mt-2">
            <div class="card-header">
                <h3 class="card-title">Scorings</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0" style="overflow-x:auto;">
                <table class="table table-striped projects" >
                    <thead>
                        <tr>
                            <th >No</th>
                            <th style="width: 15%">Subject</th>
                            <th style="width: 10%">Grade</th>
                            <th style="width: 15%">Type Scoring</th>
                            <th style="width: 20%">Name</th>
                            <th style="width: 15%">Deadline</th>
                            <th style="width: 5%">Status</th>
                            <th style="width: 15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $el)
                        <tr id={{'index_grade_' . $el->id}}>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{$el->subject_name}}</td>
                            <td>{{$el->grade_name}} - {{ $el->grade_class }}</td>
                            <td>{{$el->type_exam}}</td>
                            <td>{{$el->name_exam}}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($el->date_exam)->format('l, d F Y') }}
                            <!-- <br>
                            @php
                                $currentDate = now(); // Tanggal saat ini
                                $dateExam = $el->date_exam; // Tanggal exam dari data

                                // Hitung selisih antara tanggal exam dengan tanggal saat ini
                                $diff = strtotime($dateExam) - strtotime($currentDate);
                                $days = floor($diff / (60 * 60 * 24)); // Konversi detik ke hari
                            @endphp
                            <small class="text-muted mb-0"><span class="badge badge-danger">{{$days}} days again</span></small> -->
                            </td>
                            <td>
                                @if($el->is_active)
                                <span class="badge badge-success"> Active </span>
                                @else
                                <span class="badge badge-danger"> Inactive </span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-success btn text-sm w-100 mb-1"
                                    href="{{url('teacher/dashboard/exam') . '/score/' . $el->id}}">
                                    <i class="fas fa-book">
                                    </i>
                                    Score
                                </a>
                                <a class="btn btn-primary btn text-sm w-100 mb-1"
                                    href="{{url('teacher/dashboard/exam') . '/detail/' . $el->id}}">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                                <a class="btn btn-warning btn text-sm w-100 mb-1"
                                    href="{{url('teacher/dashboard/exam') . '/edit/' . $el->id}}">
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
        </div>

        {{-- pagination --}}

        <div class="d-flex justify-content-end my-5">

            <nav aria-label="...">
                <ul class="pagination" max-size="2">
                    
                    @php
                    $role = session('role');
                    $link = "/teacher/dashboard/exam/teacher?";
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
    @else
        <div class="container-fluid full-height">
            <div class="m-0">
                <p class="text-red my-b-2">Oops.. You dont create any scorings</p>
            </div>
            <div class="icon-wrapper">
                <i class="fa-regular fa-face-laugh-beam"></i>
                <p class="my-2">Students are happy to get scoring from you</p>
            </div>
            <div class="btn-container">
                <a type="button" href="{{ url('/teacher/dashboard/exam/create') }}" class="btn btn-secondary btn">
                    <i class="fa-solid fa-plus"></i> Scoring
                </a>
            </div>
        </div>
    @endif
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
                    url: "{{ route('delete.exam') }}",
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
                text: 'Successfully created new scoring in the database.',
            });
        </script>
    @endif
  
    @if(session('after_update_exam')) 
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Successfully',
                text: 'Successfully updated the scoring in the database.',
            });
        </script>
   @endif

    @if(session('after_update_score')) 
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully',
                    text: 'Successfully update score',
                });
            </script>
    @endif
@endsection
