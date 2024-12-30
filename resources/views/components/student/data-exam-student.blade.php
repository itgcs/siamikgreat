@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
        <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Scorings</li>
            </ol>
        </nav>
        </div>
    </div>

    <form class="row col-12" action="{{ route('student.dashboard.exam') }}" method="GET">
        <div class="col-2">
            <div class="form-group">
                @php
                    $selectedOrder = $form->sort;
                @endphp
    
                <label>Sort by:</label>
                <select name="order" class="form-control" id="subject-select" onchange="this.form.submit()">
                    <option value="all" {{ $selectedOrder === 'all' ? 'selected' : '' }}>All Subject</option>
                    @foreach ($subjects->subject as $subject)
                        <option value="{{ $subject['id'] }}" {{ $selectedOrder == $subject['id'] ? 'selected' : '' }}>
                            {{ ucwords($subject['name_subject']) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    
        {{-- <div class="col-2">
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
        <div class="col-8">
            <div class="form-group">
                <label>Search :</label>
                <div class="form-control">
                    <input name="search" value="{{$form->search}}" type="search" placeholder="Type your keywords here">
                    {{-- <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div> --}}
                {{-- </div>
            </div>
        </div> --}}
    
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title">Scorings</h3>

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
                        <th style="width:5%;">
                           #
                        </th>
                        <th style="width:10%;">
                            student
                        </th>
                        <th style="width:10%;">
                          Type Exam
                        </th>
                        <th style="width:10%;">
                          Date
                        </th>
                        <th style="width:10%;">
                           Grade
                        </th>
                        <th style="width:15%;">
                           Subject
                        </th>
                        <th style="width:5%;">
                           Score
                        </th>
                        <th style="width:10%;">
                           Status
                        </th>
                        <th>
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data) !== 0)
                        @foreach ($data as $el)
                        <tr id={{'index_grade_' . $el->id}}>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                            <a>
                                {{$el->type_exam}}
                            </a>
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
                                    $dateExam = $el->date_exam; // Tanggal ujian dari data

                                    // Buat objek DateTime dari tanggal saat ini dan tanggal ujian
                                    $currentDateTime = new DateTime($currentDate);
                                    $dateExamDateTime = new DateTime($dateExam);

                                    // Hitung selisih antara kedua tanggal
                                    $interval = $currentDateTime->diff($dateExamDateTime);

                                    // Ambil jumlah hari dari selisih tersebut
                                    $days = $interval->days;

                                    // Jika tanggal ujian lebih kecil dari tanggal saat ini, buat selisih menjadi negatif
                                    if ($dateExamDateTime < $currentDateTime) {
                                        $days = -$days;
                                    } else if ($dateExamDateTime > $currentDateTime && $days == 0) {
                                        // Jika tanggal ujian di masa depan dan selisih kurang dari 1 hari, anggap 1 hari
                                        $days = 1;
                                    }
                            @endphp
                            @if ($el->is_active)
                            <span class="badge badge-danger">{{$days}} days again</span>
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
                            {{$el->score}}
                            </td>
                            <td>
                            @if ($el->is_active)
                            <span class="badge badge-danger">On Progress</span>
                            @else
                            <span class="badge badge-success"> Done </span>
                            @endif
                            </td>
                            <td class="project-actions text-left toastsDefaultSuccess">
                            <a class="btn btn-primary btn" id="view" data-id="{{ $el->id }}">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="9" class="text-center text-red">
                            No Exam 
                        </td>    
                    </tr>
                    @endif
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
            $link = "/{$role}/dashboard/exam?";
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

<script>
   document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('#view').forEach(function(button) {
         button.addEventListener('click', function() {
               var assessmentId = this.getAttribute('data-id');
               var sessionRole = @json(session('role'));
               var url;
                if (sessionRole === "parent") {
                    url = "{{ route('set.assessment.id') }}";
                } else if (sessionRole === "student") {
                    url = "{{ route('set.assessment.id.student') }}";
                }
               
               $.ajax({
                  url: url,
                  method: 'POST',
                  data: {
                     id: assessmentId,
                     _token: '{{ csrf_token() }}'
                  },
                  success: function(response) {
                     if (response.success) {
                           window.location.href = '/' + sessionRole + '/dashboard/exam/detail';
                     } else {
                           alert('Failed to set exam ID in session.');
                     }
                  },
                  error: function(xhr, status, error) {
                     alert('Error: ' + error);
                  }
               });
         });
      });
   });
</script>



@endsection
