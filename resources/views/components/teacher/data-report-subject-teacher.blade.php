@extends('layouts.admin.master')
@section('content')

<style>
   .full-height {
      height: 60vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
   }
   .icon-wrapper i {
      font-size: 200px;
      color: #ccc;
   }
   .icon-wrapper p {
      position: absolute;
      left: 50%;
      transform: translate(-50%, 0%);
      margin: 0;
      font-size: 1.5rem;
      color: black;
      text-align: center;
   }
</style>

@if (sizeof($data) != 0)
<div class="container-fluid">
   <!-- <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div> -->
   
   <!-- START TABEL -->
      <!-- Display Kindergarten Grades -->
      @if (!$kindergartenGrades->isEmpty())
         <div class="card card-dark mt-2">
            <div class="card-header"> 
                  <h3 class="card-title">Kindergarten Grades</h3>
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
                              <th style="width: 5%">#</th>
                              <th style="width: 10%">Grade</th>
                              <th style="width: 15%">Subject</th>
                              <th style="width: 10%">Action</th>
                              <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($kindergartenGrades as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td>{{ $el->name }} - {{ $el->class }}</td>
                                 <td>{{ $el->name_subject }}</td>
                                 <td>
                                    <a class="btn btn-primary btn" href="{{ url('teacher/dashboard/report/detailSubjectKindergarten') . '/' . $el->grade_id . '/' . $el->subject_id }}">
                                          Scoring
                                    </a>
                                 </td>
                                 <td>{{ $el->status == 1 ? 'Already Submitted Score' : 'Not Submitted' }}</td>
                              </tr>
                        @endforeach
                     </tbody>
                  </table>
            </div>
         </div>
      @endif

      <!-- Display Primary Grades -->
      @if (!$primaryGrades->isEmpty())
         <div class="card card-dark mt-2">
            <div class="card-header"> 
                  <h3 class="card-title">Primary Grades</h3>
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
                              <th style="width: 5%">#</th>
                              <th style="width: 10%">Grade</th>
                              <th style="width: 15%">Subject</th>
                              <th style="width: 10%">Action</th>
                              <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($primaryGrades as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td>{{ $el->name }} - {{ $el->class }}</td>
                                 <td>{{ $el->name_subject }}</td>
                                 <td>
                                    <a class="btn btn-primary btn" href="{{ url('teacher/dashboard/report/detailSubjectPrimary') . '/' . $el->grade_id . '/' . $el->subject_id }}">
                                          Scoring
                                    </a>
                                 </td>
                                 <td>{{ $el->status == 1 ? 'Already Submitted Score' : 'Not Submitted' }}</td>
                              </tr>
                        @endforeach
                     </tbody>
                  </table>
            </div>
         </div>
      @endif

      <!-- Display Secondary Grades -->
      @if (!$secondaryGrades->isEmpty())
         <div class="card card-dark mt-2">
            <div class="card-header"> 
                  <h3 class="card-title">Secondary Grades</h3>
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
                              <th style="width: 5%">#</th>
                              <th style="width: 10%">Grade</th>
                              <th style="width: 15%">Subject</th>
                              <th style="width: 10%">Action</th>
                              <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($secondaryGrades as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td>{{ $el->name }} - {{ $el->class }}</td>
                                 <td>{{ $el->name_subject }}</td>
                                 <td>
                                    <a class="btn btn-primary btn" href="{{ url('teacher/dashboard/report/detailSubjectSecondary') . '/' . $el->grade_id . '/' . $el->subject_id }}">
                                          Scoring
                                    </a>
                                 </td>
                                 <td>{{ $el->status == 1 ? 'Already Submitted Score' : 'Not Submitted' }}</td>
                              </tr>
                        @endforeach
                     </tbody>
                  </table>
            </div>
         </div>
      @endif


   <!-- END TABLE -->
</div>
@else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
            <i class="fa-regular fa-face-laugh-wink"></i>
            <p>Oops.. <br> Maybe you haven't been plotted yet</p>
      </div>
   </div>
@endif

<script>
   function saveSemesterToSession() {
      var semesterSelect = document.getElementById('semester');
      var selectedSemester = semesterSelect.value;
      
      // Simpan nilai semester ke dalam session
      $.ajax({
         url: '{{ route('save.semester.session') }}',
         type: 'POST',
         data: {
            semester: selectedSemester,
            _token: '{{ csrf_token() }}'
         },
         success: function(response) {
            console.log('Semester saved to session:', response.semester);
         },
         error: function(xhr, status, error) {
            console.error('Error saving semester to session:', error);
         }
      });
   }
</script>


@endsection
