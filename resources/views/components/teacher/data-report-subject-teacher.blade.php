@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div>
   
   <!-- START TABEL -->
         
         @if (!empty($data))

          <!-- Display Kindergartern Grades -->
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
                                 <th style="width: 10%">
                                    #
                                 </th>
                                 <th style="width: 20%">
                                    Grade
                                 </th>
                                 <th style="width: 20%">
                                    Subject
                                 </th>
                                 <th>
                                    Action
                                 </th>
                              </tr>
                        </thead>
                        <tbody>
                           @foreach ($kindergartenGrades as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>
                                    {{ $loop->index + 1 }}
                                 </td>
                                 <td>
                                    {{ $el->name }} - {{ $el->class }}
                                 </td>
                                 <td>
                                    <a>
                                       {{ $el->name_subject }}
                                    </a>
                                 </td>
                                 <td>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report/detailSubjectKindergarten') . '/' . $el->grade_id . '/' . $el->subject_id}}">
                                       </i>
                                       Scoring
                                    </a>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            @else
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
                                 <th style="width: 10%">
                                    #
                                 </th>
                                 <th style="width: 20%">
                                    Grade
                                 </th>
                                 <th style="width: 20%">
                                    Subject
                                 </th>
                                 <th>
                                    Action
                                 </th>
                              </tr>
                        </thead>
                        <tbody>
                           @foreach ($primaryGrades as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>
                                    {{ $loop->index + 1 }}
                                 </td>
                                 <td>
                                    {{ $el->name }} - {{ $el->class }}
                                 </td>
                                 <td>
                                    <a>
                                       {{ $el->name_subject }}
                                    </a>
                                 </td>
                                 <td>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report/detailSubjectPrimary') . '/' . $el->grade_id . '/' . $el->subject_id}}">
                                       </i>
                                       Scoring
                                    </a>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            @else
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
                                 <th style="width: 10%">
                                    #
                                 </th>
                                 <th style="width: 20%">
                                    Grade
                                 </th>
                                 <th style="width: 20%">
                                    Subject
                                 </th>
                                 <th>
                                    Action
                                 </th>
                              </tr>
                        </thead>
                        <tbody>
                           @foreach ($secondaryGrades as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>
                                    {{ $loop->index + 1 }}
                                 </td>
                                 <td>
                                    {{ $el->name }} - {{ $el->class }}
                                 </td>
                                 <td>
                                    <a>
                                       {{ $el->name_subject }}
                                    </a>
                                 </td>
                                 <td>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report/detailSubjectSecondary') . '/' . $el->grade_id . '/' . $el->subject_id}}">
                                       </i>
                                       Scoring
                                    </a>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            @else
            @endif

         @else
         <p>Data Kosong</p>
         @endif
      <!-- END TABLE -->
   <!-- END TABEL -->
</div>

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
